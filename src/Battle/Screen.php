<?php
declare(strict_types=1);

namespace Shanhai\Battle;

use LogicException;
use Shanhai\Cursor;
use Shanhai\World\{Controls, Menu, PartyMenu, World};

/**
 * A battle with a wild beast as the player goes through it, with no drawing: step() takes an update's Controls.
 * Lines are read one at a time, confirm going on; between turns the menu asks what to do. What is shown of each
 * side (which beast, its hp, its status) changes with the line that tells it, not when the turn is worked out.
 * The player is side 0, and chooses the next beast when one falls. The wild beast uses any move it has left.
 * When it falls, the player's beasts that fought it and still stand gain experience (docs/numbers.md 7.3), and one
 * that comes to a move knowing four already is asked which to forget. A seal from the bag tries to bind the wild
 * beast (docs/numbers.md 8): bound, it is $caught and the battle ends; else the turn is the wild beast's.
 */
final class Screen
{
    /** The menu between turns, in its order. */
    public const MENU = ['fight' => '战斗', 'party' => '异兽', 'bag' => '背包', 'flee' => '逃跑'];
    /** What is said when a seal fails, by how many times it seemed to hold, from none to three. */
    public const BROKE = ['啊，%s挣脱了！', '可惜，还以为结契了！', '啧，就差一点！', '太可惜了，只差最后一下！'];
    public const STATS = ['atk' => '攻击', 'def' => '防御', 'spa' => '特攻', 'spd' => '特防', 'spe' => '速度', 'acc' => '命中', 'eva' => '闪避'];
    public const STATUS = ['poison' => '毒', 'burn' => '灼', 'paralyze' => '麻', 'sleep' => '眠'];

    /** text: a line is showing; menu, moves, party, bag, forget: a list is, with $cursor on one of it. */
    public string $phase = 'text';
    public Cursor $cursor;
    public ?string $line = null;
    /** @var array{0: array{which: int, hp: int, status: ?string, level: int, exp: int}, 1: array} what is shown of each side: which beast, and its hp, status, level and experience */
    public array $shown;
    /** won, lost, fled or caught, once it is known; $done when its last line has been read. */
    public ?string $outcome = null;
    /** The wild beast, once a seal has bound it. */
    public ?Fighter $caught = null;
    public bool $done = false;

    /** @var list<array{0: string, 1: array}> the lines still to read, each with what is shown once it is */
    private array $queue = [];
    private int $fleeTries = 0;
    /** @var array<int, true> the player's beasts that have been out against the wild one, by their place in the party */
    private array $fought;
    /** @var list<array{0: int, 1: string}> the moves beasts have come to and have yet to learn: [place in the party, move] */
    private array $toLearn = [];
    /** Whether the line asking which move to forget for the first of $toLearn has been said. */
    private bool $asked = false;

    /** @var array<string, int> the player's bag, which using a thing takes from */
    private array $bag;

    /**
     * @param array<string, array> $moves data/moves.php
     * @param array<string, array> $things data/items.php
     * @param array<string, int> $bag the player's bag: item id => how many
     */
    public function __construct(public readonly Battle $battle, private readonly array $moves, private readonly Rng $rng, private readonly array $things, array &$bag)
    {
        $this->bag = &$bag;
        $this->cursor = new Cursor();
        $this->shown = [$this->truth(0), $this->truth(1)];
        $this->fought = [$battle->active[0] => true];
        $this->say('野生的' . $battle->fighter(1)->species['name'] . '出现了！');
        $this->say('去吧，' . $battle->fighter(0)->species['name'] . '！');
        $this->next();
    }

    public function step(Controls $c): void
    {
        if ($this->done) {
            throw new LogicException('the battle is over');
        }
        if ($this->phase === 'text') {
            if ($c->confirm) {
                $this->next();
            }
            return;
        }
        if ($this->cursor->move($c, count($this->items()))) {
            return;
        }
        if ($c->cancel && $this->phase === 'forget') {
            $this->forget(Fighter::MOVES); // the last entry: not to learn it
        } elseif ($c->cancel && $this->phase !== 'menu' && !$this->battle->awaits(0)) { // a fallen beast's place must be taken
            $this->open('menu');
        } elseif ($c->confirm) {
            match ($this->phase) {
                'menu' => $this->chosen(array_keys(self::MENU)[$this->cursor->at]),
                'moves' => $this->move($this->cursor->at),
                'party' => $this->send($this->cursor->at),
                'bag' => $this->use(array_keys($this->bag)[$this->cursor->at]),
                'forget' => $this->forget($this->cursor->at),
            };
        }
    }

    /** The list showing, a line an entry; none while a line is read. @return list<string> */
    public function items(): array
    {
        $me = $this->battle->fighter(0);
        return match ($this->phase) {
            'text' => [],
            'menu' => array_values(self::MENU),
            'moves' => array_map(fn(array $m) => sprintf('%s  %d/%d', $this->moves[$m['id']]['name'], $m['pp'], $this->moves[$m['id']]['pp']), $me->moves),
            'party' => array_map(PartyMenu::line(...), $this->battle->parties[0]),
            'bag' => Menu::bag($this->bag, $this->things),
            'forget' => [
                ...array_map(fn(array $m) => $this->moves[$m['id']]['name'], $this->battle->parties[0][$this->toLearn[0][0]]->moves),
                '不学' . $this->moves[$this->toLearn[0][1]]['name'],
            ],
        };
    }

    private function open(string $phase): void
    {
        [$this->phase, $this->cursor, $this->line] = [$phase, new Cursor(), null];
    }

    private function chosen(string $what): void
    {
        if ($what === 'party') {
            $this->open('party');
        } elseif ($what === 'bag') {
            if ($this->bag === []) {
                $this->say('背包里什么也没有。');
                $this->next();
            } else {
                $this->open('bag');
            }
        } elseif ($what === 'flee') {
            $this->flee();
        } elseif ($this->battle->usable(0) === []) {
            $this->turn(['move', 0]); // with none left the beast can only fail to move (the battle says so)
        } else {
            $this->open('moves');
        }
    }

    private function move(int $which): void
    {
        if ($this->battle->fighter(0)->moves[$which]['pp'] === 0) {
            $this->say('这个招式已经用不了了。');
            $this->next();
            return;
        }
        $this->turn(['move', $which]);
    }

    private function send(int $which): void
    {
        $f = $this->battle->parties[0][$which];
        if ($f->fainted()) {
            $this->say($f->species['name'] . '已经没有力气战斗了。');
            $this->next();
        } elseif ($which === $this->battle->active[0]) {
            $this->say($f->species['name'] . '已经在场上了。');
            $this->next();
        } elseif ($this->battle->awaits(0)) {
            [$hp, $status, $stages] = $this->before();
            $from = count($this->battle->log);
            $this->battle->send(0, $which);
            $this->tell(array_slice($this->battle->log, $from), $hp, $status, $stages);
            $this->next();
        } else {
            $this->turn(['switch', $which]);
        }
    }

    /**
     * The wild beast has fallen: each of the player's that fought it and still stands gains experience, the fallen's
     * base experience and base stats divided among them first (docs/numbers.md 7.1, 7.3).
     */
    private function reward(): void
    {
        $foe = $this->battle->fighter(1);
        $party = $this->battle->parties[0];
        $who = array_filter(array_keys($this->fought), fn(int $i) => !$party[$i]->fainted());
        sort($who);
        $share = array_map(fn(int $base) => intdiv($base, count($who)), $foe->species['stats']);
        $exp = Formulas::expGain($foe->species['exp'], $foe->level, count($who), false);
        foreach ($who as $i) {
            $f = $party[$i];
            [$from, $to] = $f->gain($exp, $share);
            $shown = $this->shownLast();
            $out = $shown[0]['which'] === $i;
            if ($out) {
                $shown[0]['exp'] = $f->exp;
            }
            $this->say($f->species['name'] . '获得了' . $exp . '点经验！', $shown);
            if ($to > $from) {
                if ($out) {
                    [$shown[0]['hp'], $shown[0]['level']] = [$f->hp, $f->level];
                }
                $this->say($f->species['name'] . '升到了' . $to . '级！', $shown);
                foreach ($f->learnable($from, $to) as $move) {
                    $this->toLearn[] = [$i, $move];
                }
            }
        }
    }

    /** The list's answer: the move at $slot is forgotten for the new one, or, with the entry after the four, the new one is not learned. */
    private function forget(int $slot): void
    {
        [$i, $move] = array_shift($this->toLearn);
        $this->asked = false;
        $f = $this->battle->parties[0][$i];
        $name = $this->moves[$move]['name'];
        if ($slot === Fighter::MOVES) {
            $this->say($f->species['name'] . '没有学' . $name . '。');
        } else {
            $old = $this->moves[$f->moves[$slot]['id']]['name'];
            $f->learn($move, $this->moves, $slot);
            $this->say($f->species['name'] . '忘掉了' . $old . '，学会了' . $name . '！');
        }
        $this->next();
    }

    /**
     * Every beast's hp and status, and the stages of the two out, as they are now: tell() applies to these what the battle then writes.
     * @return array{0: array, 1: array, 2: array}
     */
    private function before(): array
    {
        $hp = $status = $stages = [];
        foreach ([0, 1] as $side) {
            foreach ($this->battle->parties[$side] as $i => $f) {
                [$hp[$side][$i], $status[$side][$i]] = [$f->hp, $f->status];
            }
            $stages[$side] = $this->battle->fighter($side)->stages;
        }
        return [$hp, $status, $stages];
    }

    /** A thing of the bag is used: a seal is thrown; another is of no use here. */
    private function use(string $id): void
    {
        $thing = $this->things[$id];
        if (($thing['use'] ?? null) !== 'catch') {
            $this->say($thing['name'] . '在这里用不上。');
            $this->next();
            return;
        }
        if (count($this->battle->parties[0]) >= World::PARTY) {
            $this->say('队伍已经满了，没法再结契。');
            $this->next();
            return;
        }
        if (--$this->bag[$id] === 0) {
            unset($this->bag[$id]);
        }
        $foe = $this->battle->fighter(1);
        $name = $foe->species['name'];
        $this->say('使用了' . $thing['name'] . '！');
        // docs/numbers.md 8: a byte not over what the catch needs binds it.
        $needs = Formulas::catchValue($foe->species['catch'], $foe->maxHp, $foe->hp, $foe->status);
        if ($this->rng->byte() <= $needs) {
            [$this->outcome, $this->caught] = ['caught', $foe];
            $this->say('结契成功！' . $name . '成了你的同伴！');
            $this->next();
            return;
        }
        // It fails: the seal seems to hold up to three times, each a byte under the limit, before the beast is free and takes its turn.
        $held = 0;
        $limit = Formulas::wobbleLimit($needs);
        while ($held < 3 && $this->rng->byte() < $limit) {
            $held++;
        }
        $this->say(sprintf(self::BROKE[$held], $name));
        $this->turn(['pass', 0]);
    }

    /** docs/numbers.md 11.4. A try that fails is the player's turn: the wild beast still takes its own. */
    private function flee(): void
    {
        $limit = Formulas::fleeValue($this->battle->fighter(0)->stat('spe'), $this->battle->fighter(1)->stat('spe'), ++$this->fleeTries);
        if ($limit === null || $this->rng->byte() <= $limit) {
            $this->outcome = 'fled';
            $this->say('顺利逃走了！');
            $this->next();
            return;
        }
        $this->say('逃不掉！');
        $this->turn(['pass', 0]);
    }

    /** A turn: the player's action, and any move the wild beast has left, each as likely. */
    private function turn(array $action): void
    {
        $foe = ['move', 0];
        if (($usable = $this->battle->usable(1)) !== []) {
            do {
                $foe[1] = $this->rng->byte() & 3;
            } while (!in_array($foe[1], $usable, true));
        }
        [$hp, $status, $stages] = $this->before();
        $from = count($this->battle->log);
        $this->battle->turn($action, $foe);
        $this->tell(array_slice($this->battle->log, $from), $hp, $status, $stages);
        if ($this->battle->winner !== null) {
            $this->outcome = $this->battle->winner === 0 ? 'won' : 'lost';
            if ($this->outcome === 'won') {
                $this->reward();
            } else {
                $this->say('你的异兽全都倒下了……');
            }
        }
        if ($this->shownLast() !== [$this->truth(0), $this->truth(1)]) {
            throw new LogicException('what the lines show is not how the battle stands');
        }
        $this->next();
    }

    /**
     * Turns what the battle wrote of a turn into lines, each with what is shown once it is read.
     * @param array $hp every beast's hp before the turn, by side and place; $status likewise; $stages those of the two out
     */
    private function tell(array $events, array $hp, array $status, array $stages): void
    {
        $b = $this->battle;
        $shown = $this->shownLast();
        $name = fn(int $side) => ($side === 1 ? '野生的' : '') . $b->parties[$side][$shown[$side]['which']]->species['name'];
        foreach ($events as $e) {
            $side = $e[1];
            $line = null;
            switch ($e[0]) {
                case 'switch':
                    $next = $b->parties[$side][$e[2]];
                    $shown[$side] = ['which' => $e[2], 'hp' => $hp[$side][$e[2]], 'status' => $status[$side][$e[2]], 'level' => $next->level, 'exp' => $next->exp];
                    $stages[$side] = $b->parties[$side][$e[2]]->stages;
                    if ($side === 0) {
                        $this->fought[$e[2]] = true;
                    }
                    $line = ($side === 0 ? '去吧，' : '对手换上了') . $b->parties[$side][$e[2]]->species['name'] . '！';
                    break;
                case 'cant':
                    $line = $name($side) . match ($e[2]) {
                        'sleep' => '正在沉睡。', 'flinch' => '畏缩了，动弹不得！', 'paralyze' => '身体麻痹，动弹不得！', 'no moves' => '已经没有可用的招式了！',
                    };
                    break;
                case 'woke':
                    $shown[$side]['status'] = $status[$side][$shown[$side]['which']] = null;
                    $line = $name($side) . '醒了过来！';
                    break;
                case 'clear':
                    $line = $name($side) . '不再混乱了！';
                    break;
                case 'use':
                    $line = $name($side) . '使出了' . $this->moves[$e[2]]['name'] . '！';
                    break;
                case 'miss':
                    $line = '没有打中！';
                    break;
                case 'hit':
                    $shown[$side]['hp'] = $hp[$side][$shown[$side]['which']] -= $e[2];
                    $this->amend($shown);
                    if ($e[3]) {
                        $this->say('击中了要害！', $shown);
                    }
                    $factor = array_product($e[4]) / 10 ** count($e[4]);
                    $line = $factor > 1 ? '效果拔群！' : ($factor < 1 ? '效果不太好……' : null);
                    break;
                case 'hurt':
                    $shown[$side]['hp'] = $hp[$side][$shown[$side]['which']] -= $e[2];
                    $line = match ($e[3]) {
                        'confusion' => $name($side) . '在混乱中伤到了自己！', 'poison' => $name($side) . '受到毒的侵蚀！',
                        'burn' => $name($side) . '被灼伤折磨着！', 'sandstorm' => '风沙打在' . $name($side) . '身上！',
                    };
                    break;
                case 'heal':
                    $shown[$side]['hp'] = $hp[$side][$shown[$side]['which']] += $e[2];
                    $line = $name($side) . '恢复了体力！';
                    break;
                case 'faint':
                    $line = $name($side) . '倒下了！';
                    break;
                case 'status':
                    if ($e[2] !== 'confuse') {
                        $shown[$side]['status'] = $status[$side][$shown[$side]['which']] = $e[2];
                    }
                    $line = $name($side) . match ($e[2]) {
                        'poison' => '中毒了！', 'burn' => '被灼伤了！', 'paralyze' => '麻痹了！', 'sleep' => '睡着了！', 'confuse' => '混乱了！',
                    };
                    break;
                case 'weather':
                    $line = match ($e[2]) { 'rain' => '下起了雨。', 'sun' => '烈日当空。', 'sandstorm' => '风沙大作。', null => '天气恢复了平静。' };
                    break;
                case 'stage':
                    $by = $e[3] - $stages[$side][$e[2]];
                    $stages[$side][$e[2]] = $e[3];
                    $line = $name($side) . '的' . self::STATS[$e[2]] . (abs($by) > 1 ? '大幅' : '') . ($by > 0 ? '提高了！' : '降低了！');
                    break;
                case 'fail':
                    $line = '但是没有效果。';
                    break;
                default:
                    throw new LogicException("the battle wrote '{$e[0]}', which has no line");
            }
            if ($line !== null) {
                $this->say($line, $shown);
            }
        }
    }

    /** @return array{which: int, hp: int, status: ?string, level: int, exp: int} the side as the battle has it */
    private function truth(int $side): array
    {
        $f = $this->battle->fighter($side);
        return ['which' => $this->battle->active[$side], 'hp' => $f->hp, 'status' => $f->status, 'level' => $f->level, 'exp' => $f->exp];
    }

    /** What will be shown when every line queued has been read. */
    private function shownLast(): array
    {
        return $this->queue === [] ? $this->shown : end($this->queue)[1];
    }

    private function say(string $line, ?array $shown = null): void
    {
        $this->queue[] = [$line, $shown ?? $this->shownLast()];
    }

    /** What an event with no line of its own shows comes with the line before it. */
    private function amend(array $shown): void
    {
        $this->queue[array_key_last($this->queue)][1] = $shown;
    }

    /**
     * The next line. With none left: a move a beast has come to is learned, or, when it knows four, asked about and
     * then chosen for in the list; then the end, or the party's list when the player's beast has fallen, or the menu.
     */
    private function next(): void
    {
        if ($this->queue !== []) {
            [$this->line, $this->shown] = array_shift($this->queue);
            $this->phase = 'text';
        } elseif ($this->toLearn !== []) {
            [$i, $move] = $this->toLearn[0];
            $f = $this->battle->parties[0][$i];
            if (count($f->moves) < Fighter::MOVES) {
                $f->learn($move, $this->moves);
                array_shift($this->toLearn);
                $this->say($f->species['name'] . '学会了' . $this->moves[$move]['name'] . '！');
                $this->next();
            } elseif (!$this->asked) {
                $this->asked = true;
                $this->say($f->species['name'] . '想学' . $this->moves[$move]['name'] . '，可是已经会四个招式了。要忘掉哪一个？');
                $this->next();
            } else {
                $this->open('forget');
            }
        } elseif ($this->outcome !== null) {
            $this->done = true;
        } else {
            $this->open($this->battle->awaits(0) ? 'party' : 'menu');
        }
    }
}
