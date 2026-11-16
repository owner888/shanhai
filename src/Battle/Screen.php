<?php
declare(strict_types=1);

namespace Shanhai\Battle;

use LogicException;
use Shanhai\Cursor;
use Shanhai\World\{Controls, Menu, PartyMenu};


/**
 * A battle as the player goes through it, with no drawing: with a wild beast, or, with $trial, a trial, against the
 * beasts of the one who sets it, which cannot be fled nor bound. step() takes an update's Controls.
 * Lines are read one at a time, confirm going on; between turns the menu asks what to do. What is shown of each
 * side (which beast, its hp, its status) changes with the line that tells it, not when the turn is worked out.
 * The player is side 0, and chooses the next beast when one falls. A wild beast uses any move it has left; in a
 * trial the other side does what Ai makes of the turn (docs/numbers.md 26). When one of theirs falls, the player's beasts that fought it and still stand gain experience (docs/numbers.md 7.3), and one
 * that comes to a move knowing four already is asked which to forget. A seal from the bag tries to bind the wild
 * beast (docs/numbers.md 8): bound, it is $caught and the battle ends; else the turn is the wild beast's. With a full
 * party the beast bound goes to the pen's current box, and with that full too no seal is used (docs/numbers.md 16).
 */
final class Screen
{
    /** The menu between turns, in its order. */
    /** The move a beast close enough to the player comes to of itself (docs/numbers.md 25). */
    public const BOND_MOVE = 'tongxin';
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
    /**
     * What the line showing tells of, for what draws the battle and sounds it; null for a line that tells of nothing
     * to show. 'what' is: use (a beast, 'side', makes its move; with 'hit' the side struck, or null, and 'critical'),
     * hurt, heal, faint, switch ('side' in each), seal, broke, caught, level.
     */
    public ?array $cue = null;
    /** How many lines have shown: it rises with each, so that a cue is acted on once. */
    public int $lines = 0;
    /** won, lost, fled or caught, once it is known; $done when its last line has been read. */
    public ?string $outcome = null;
    /** The wild beast, once a seal has bound it. */
    public ?Fighter $caught = null;
    public bool $done = false;

    /** @var list<array{0: string, 1: array, 2: ?array}> the lines still to read, each with what is shown once it is, and its cue */
    private array $queue = [];
    private int $fleeTries = 0;
    /** @var array<int, true> the player's beasts that have been out against the wild one, by their place in the party */
    private array $fought;
    /** @var list<array{0: int, 1: string}> the moves beasts have come to and have yet to learn: [place in the party, move] */
    private array $toLearn = [];
    /** Whether the line asking which move to forget for the first of $toLearn has been said. */
    private bool $asked = false;

    /** What the one who sets the trial goes by (docs/numbers.md 26); null with a wild beast. */
    private readonly ?Ai $ai;
    /** @var array<string, int> the player's bag, which using a thing takes from */
    private array $bag;

    /**
     * @param array<string, array> $moves data/moves.php
     * @param array<string, array> $things data/items.php
     * @param array<string, int> $bag the player's bag: item id => how many
     * @param ?string $trial the name of the one who sets the trial; null for a battle with a wild beast
     * @param bool $duel the trial is one met on the road, not a god's: it goes by a trial's rules and is told in other words
     * @param ?string $pen the name of the pen's box a beast bound is sent to, the party being full; null when it joins the party
     * @param int $penRoom how many more that box holds
     * @param int $reward what the one who sets the trial pays when it is won (docs/numbers.md 17)
     * @param bool $water the battle began on the water (docs/numbers.md 22)
     * @param list<string> $uses the things the one who sets the trial has to use on their beasts (docs/numbers.md 26)
     */
    public function __construct(public readonly Battle $battle, private readonly array $moves, private readonly Rng $rng, private readonly array $things, array &$bag, public readonly ?string $trial = null, public readonly bool $duel = false, private readonly ?string $pen = null, private readonly int $penRoom = 0, public readonly int $reward = 0, private readonly bool $water = false, array $uses = [])
    {
        $this->bag = &$bag;
        $battle->wild = $trial === null;
        $this->ai = $trial === null ? null : new Ai($duel ? Ai::ROAD : Ai::GOD, $moves, $things, $uses);
        $this->cursor = new Cursor();
        $this->shown = [$this->truth(0), $this->truth(1)];
        $this->fought = [$battle->active[0] => true];
        $first = $battle->fighter(1)->species['name'];
        if ($trial === null) {
            $this->say('野生的' . $first . '出现了！');
        } else {
            $this->say($trial . ($duel ? '向你挑战！' : '要考校你！'));
            $this->say($trial . '派出了' . $first . '！');
        }
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
                'dose' => $this->dose($this->cursor->at),
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
            'bag' => $this->bagLines(),
            'dose' => array_map(PartyMenu::line(...), $this->battle->parties[0]),
            'forget' => [
                ...array_map(fn(array $m) => $this->moves[$m['id']]['name'], $this->battle->parties[0][$this->toLearn[0][0]]->moves),
                '不学' . $this->moves[$this->toLearn[0][1]]['name'],
            ],
        };
    }

    /**
     * The bag's lines; beside what binds, how likely it is to as the wild beast stands (docs/numbers.md 8), when it
     * can be tried at all: not in a trial, nor with the party and the pen's box both full.
     * @return list<string>
     */
    private function bagLines(): array
    {
        $lines = Menu::bag($this->bag, $this->things);
        if ($this->trial !== null || !$this->room()) {
            return $lines;
        }
        foreach (array_keys($this->bag) as $i => $id) {
            if (($this->things[$id]['use'] ?? null) === 'catch') {
                $needs = $this->needs($this->things[$id]);
                $lines[$i] .= '  成功率 ' . ($needs === null ? 100 : Formulas::catchPercent($needs)) . '%';
            }
        }
        return $lines;
    }

    private function open(string $phase): void
    {
        [$this->phase, $this->cursor, $this->line, $this->cue] = [$phase, new Cursor(), null, null];
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
            $this->turn(['move', 0]); // with none left the beast struggles (docs/numbers.md 11.6), whatever is named
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
     * One of the other side's has fallen: each of the player's that fought it and still stands gains experience, the
     * fallen's base experience and base stats divided among them first, half as much again in a trial
     * (docs/numbers.md 7.1, 7.3); and those that hold what shares (18). Then those that fight the next are counted anew.
     * @param array $hp every beast's hp at this point of the turn, which a level gained raises
     * @param array $shown what is shown at this point of the turn
     */
    private function reward(Fighter $foe, array &$hp, array &$shown): void
    {
        $party = $this->battle->parties[0];
        $who = array_filter(array_keys($this->fought), fn(int $i) => !$party[$i]->fainted());
        sort($who);
        $this->fought = [$shown[0]['which'] => true];
        // docs/numbers.md 18: with a beast that can fight holding what shares, the fallen's base experience and base stats are
        // halved; those that fought divide the half, and those that hold it divide as much again, fought or not
        $holders = array_keys(array_filter($party, fn(Fighter $f) => !$f->fainted() && $f->item !== null && ($this->things[$f->item]['hold'] ?? null) === 'share'));
        $half = fn(int $base) => $holders === [] ? $base : intdiv($base, 2);
        foreach ([$who, $holders] as $those) {
            if ($those !== []) {
                $this->give($foe, $those, $half, $hp, $shown);
            }
        }
    }

    /**
     * Those of the party at $who take what $foe, fallen, gives, divided among them.
     * @param list<int> $who @param callable(int): int $of what of a base value is theirs to divide
     */
    private function give(Fighter $foe, array $who, callable $of, array &$hp, array &$shown): void
    {
        $party = $this->battle->parties[0];
        $share = array_map(fn(int $base) => intdiv($of($base), count($who)), $foe->species['stats']);
        $exp = Formulas::expGain($of($foe->species['exp']), $foe->level, count($who), $this->trial !== null);
        foreach ($who as $i) {
            $f = $party[$i];
            $most = $f->maxHp;
            [$from, $to] = $f->gain($exp, $share);
            $hp[0][$i] += $f->maxHp - $most;
            $out = $shown[0]['which'] === $i;
            if ($out) {
                $shown[0]['exp'] = $f->exp;
            }
            $this->say($f->species['name'] . '获得了' . $exp . '点经验！', $shown);
            if ($to > $from) {
                if ($out) {
                    [$shown[0]['hp'], $shown[0]['level']] = [$hp[0][$i], $f->level];
                }
                $this->say($f->species['name'] . '升到了' . $to . '级！', $shown, ['what' => 'level']);
                foreach ($f->learnable($from, $to) as $move) {
                    $this->toLearn[] = [$i, $move];
                }
                // docs/numbers.md 25: each level brings it closer; close enough, it comes to the move of the bond, which is put to it once
                for ($l = $from; $l < $to; $l++) {
                    $f->bond = Formulas::bond($f->bond, 'level');
                }
                if ($f->bond >= Formulas::BOND_MOVE && !$f->offered && !in_array(self::BOND_MOVE, array_column($f->moves, 'id'), true)) {
                    $f->offered = true;
                    $this->toLearn[] = [$i, self::BOND_MOVE];
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
            $f->bond = Formulas::bond($f->bond, 'move');
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

    /** Whether one of the player's beasts that has been out in this battle held what doubles what winning pays (docs/numbers.md 19). */
    private bool $doubled = false;

    /** What winning this battle pays. */
    public function payout(): int
    {
        return $this->reward * ($this->doubled ? 2 : 1);
    }

    /** Notes whether the player's beast now out holds what doubles the pay. */
    private function coin(): void
    {
        $this->doubled = $this->doubled || ($this->things[$this->battle->fighter(0)->item ?? '']['hold'] ?? null) === 'coin';
    }

    /** Whether a beast bound has somewhere to go: the party, or the pen's box it would be sent to. */
    private function room(): bool
    {
        return $this->pen === null || $this->penRoom > 0;
    }

    /** What a byte must not be over for the seal $thing to bind the wild beast as it stands (docs/numbers.md 8, 22); null for the seal that cannot fail. */
    private function needs(array $thing): ?int
    {
        $by = $thing['by'] ?? 100;
        if ($by === 'sure') {
            return null;
        }
        $foe = $this->battle->fighter(1);
        $rate = Formulas::sealRate($foe->species['catch'], $by, $this->battle->fighter(0)->level, $foe->level, $this->water, isset($foe->species['flees']), $foe->species['weight'] ?? 0);
        return Formulas::catchValue($rate, $foe->maxHp, $foe->hp, $foe->status);
    }

    /** Whether the beast bound was bound with the seal of kinship (docs/numbers.md 25). */
    public bool $kin = false;

    /** The medicine of the bag chosen to be used on a beast, an id. */
    private string $dosing = '';

    /**
     * The medicine chosen is used on the party's beast at $which (docs/numbers.md 21): one that would do nothing is not
     * used, and the turn is not spent; used, it is gone from the bag and the turn is the other side's.
     */
    private function dose(int $which): void
    {
        $thing = $this->things[$this->dosing];
        $f = $this->battle->parties[0][$which];
        if (!Medicine::apply($thing, $f)) {
            $this->say('用了也没有效果。');
            $this->next();
            return;
        }
        if (--$this->bag[$this->dosing] === 0) {
            unset($this->bag[$this->dosing]);
        }
        $this->say('给' . $f->species['name'] . '用了' . $thing['name'] . '。', [$this->truth(0), $this->truth(1)]);
        $this->turn(['pass', 0]);
    }

    /** A thing of the bag is used: a medicine on a beast, a seal thrown; another is of no use here. */
    private function use(string $id): void
    {
        $thing = $this->things[$id];
        if (Medicine::is($thing)) { // docs/numbers.md 21: used on a beast of the party, which is chosen next
            $this->dosing = $id;
            $this->open('dose');
            return;
        }
        if (($thing['use'] ?? null) !== 'catch') {
            $this->say($thing['name'] . '在这里用不上。');
            $this->next();
            return;
        }
        if ($this->trial !== null) {
            $this->say($this->duel ? '对手的异兽不能结契。' : '试炼中的异兽不能结契。');
            $this->next();
            return;
        }
        if (!$this->room()) {
            $this->say('队伍和兽栏的' . $this->pen . '都满了，没法再结契。');
            $this->next();
            return;
        }
        if (--$this->bag[$id] === 0) {
            unset($this->bag[$id]);
        }
        $foe = $this->battle->fighter(1);
        $name = $foe->species['name'];
        $this->say('使用了' . $thing['name'] . '！', null, ['what' => 'seal']);
        // docs/numbers.md 8: a byte not over what the catch needs binds it.
        $needs = $this->needs($thing);
        if ($needs === null || $this->rng->byte() <= $needs) { // 22: the seal that cannot fail asks for no roll
            [$this->outcome, $this->caught, $this->kin] = ['caught', $foe, ($thing['by'] ?? null) === 'bond'];
            $this->say('结契成功！' . $name . '成了你的同伴！', null, ['what' => 'caught']);
            if ($this->pen !== null) {
                $this->say('队伍满了，' . $name . '被送到了兽栏的' . $this->pen . '。');
                if ($this->penRoom === 1) {
                    $this->say($this->pen . '满了。到兽栏换一栏之前，没法再结契。');
                }
            }
            $this->next();
            return;
        }
        // It fails: the seal seems to hold up to three times, each a byte under the limit, before the beast is free and takes its turn.
        $held = 0;
        $limit = Formulas::wobbleLimit($needs);
        while ($held < 3 && $this->rng->byte() < $limit) {
            $held++;
        }
        $this->say(sprintf(self::BROKE[$held], $name), null, ['what' => 'broke']);
        $this->turn(['pass', 0]);
    }

    /** docs/numbers.md 11.4. A try that fails is the player's turn: the wild beast still takes its own. */
    private function flee(): void
    {
        if ($this->trial !== null) {
            $this->say($this->duel ? '这场对战不能逃！' : '试炼不能逃！');
            $this->next();
            return;
        }
        $limit = Formulas::fleeValue($this->battle->fighter(0)->stat('spe'), $this->battle->fighter(1)->stat('spe'), ++$this->fleeTries);
        // docs/numbers.md 19: one holding what is never lost gets away with no roll
        $sure = $limit === null || ($this->things[$this->battle->fighter(0)->item ?? '']['hold'] ?? null) === 'flee';
        if ($sure || $this->rng->byte() <= $limit) {
            $this->outcome = 'fled';
            $this->say('顺利逃走了！');
            $this->next();
            return;
        }
        $this->say('逃不掉！');
        $this->turn(['pass', 0]);
    }

    /** A turn: the player's action, and the other side's: any move a wild beast has left, each as likely; in a trial, what Ai makes of it (docs/numbers.md 26). */
    private function turn(array $action): void
    {
        $this->coin();
        $foe = ['move', 0];
        $usable = $this->battle->usable(1);
        if ($this->ai !== null) {
            $foe = $this->ai->act($this->battle, $this->rng);
            if ($foe[0] === 'item') { // a thing used takes the turn
                $theirs = $this->battle->fighter(1);
                if (!Medicine::apply($this->things[$foe[1]], $theirs)) {
                    throw new LogicException("the one who sets the trial used '{$foe[1]}' for nothing");
                }
                $this->say($this->trial . '给' . $theirs->species['name'] . '用了' . $this->things[$foe[1]]['name'] . '。', [$this->truth(0), $this->truth(1)]);
                $foe = ['pass', 0];
            }
        } elseif ($usable !== []) {
            do {
                $foe[1] = $this->rng->byte() & 3;
            } while (!in_array($foe[1], $usable, true));
        }
        [$hp, $status, $stages] = $this->before();
        $from = count($this->battle->log);
        $this->battle->turn($action, $foe);
        $this->ai?->seen($this->battle, $action, $foe);
        if ($this->ai !== null && $this->battle->awaits(1)) { // docs/numbers.md 26: the one who sets the trial chooses which of theirs comes next
            $this->battle->send(1, $this->ai->next($this->battle, $this->rng));
        }
        $this->coin();
        // docs/numbers.md 25: one of the player's that fell this turn is less close for it, and much less if what felled it was thirty levels or more over it
        foreach ($this->battle->parties[0] as $i => $f) {
            if ($hp[0][$i] > 0 && $f->fainted()) {
                $f->bond = Formulas::bond($f->bond, $this->battle->fighter(1)->level >= $f->level + 30 ? 'outmatched' : 'fainted');
            }
        }
        $this->tell(array_slice($this->battle->log, $from), $hp, $status, $stages);
        if ($this->battle->gone) {
            $this->outcome = 'gone';
        } elseif ($this->battle->winner !== null) {
            $this->outcome = $this->battle->winner === 0 ? 'won' : 'lost';
            if ($this->outcome === 'lost') {
                $this->say('你的异兽全都倒下了……');
            } elseif ($this->trial !== null) {
                $this->say($this->duel ? '打赢了' . $this->trial . '！' : '通过了' . $this->trial . '的试炼！');
                if ($this->reward > 0) {
                    $this->say('得到了' . $this->payout() . '贝。');
                }
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
        $name = fn(int $side) => ($side === 1 && $this->trial === null ? '野生的' : '') . $b->parties[$side][$shown[$side]['which']]->species['name'];
        foreach ($events as $e) {
            $side = $e[1];
            $line = $cue = null;
            switch ($e[0]) {
                case 'switch':
                    $next = $b->parties[$side][$e[2]];
                    $shown[$side] = ['which' => $e[2], 'hp' => $hp[$side][$e[2]], 'status' => $status[$side][$e[2]], 'level' => $next->level, 'exp' => $next->exp];
                    $stages[$side] = $b->parties[$side][$e[2]]->stages;
                    if ($side === 0) {
                        $this->fought[$e[2]] = true;
                    }
                    $line = ($side === 0 ? '去吧，' : ($this->trial ?? '对手') . '派出了') . $b->parties[$side][$e[2]]->species['name'] . '！';
                    $cue = ['what' => 'switch', 'side' => $side];
                    break;
                case 'cant':
                    $line = $name($side) . match ($e[2]) {
                        'sleep' => '正在沉睡。', 'flinch' => '畏缩了，动弹不得！', 'paralyze' => '身体麻痹，动弹不得！',
                    };
                    break;
                case 'struggle':
                    $line = $name($side) . '已经没有可用的招式了，只能挣扎！';
                    $cue = ['what' => 'use', 'side' => $side, 'hit' => null, 'critical' => false];
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
                    $cue = ['what' => 'use', 'side' => $side, 'hit' => null, 'critical' => false];
                    break;
                case 'miss':
                    $line = '没有打中！';
                    break;
                case 'hit':
                    $shown[$side]['hp'] = $hp[$side][$shown[$side]['which']] -= $e[2];
                    $this->amend($shown, $side, $e[3]);
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
                        'recoil' => $name($side) . '也被反震伤到了！',
                    };
                    $cue = ['what' => 'hurt', 'side' => $side];
                    break;
                case 'heal':
                    $shown[$side]['hp'] = $hp[$side][$shown[$side]['which']] += $e[2];
                    $line = $name($side) . '恢复了体力！';
                    $cue = ['what' => 'heal', 'side' => $side];
                    break;
                case 'gone': // docs/numbers.md 30
                    $this->say($name($side) . '逃走了！', $shown);
                    break;
                case 'faint':
                    $this->say($name($side) . '倒下了！', $shown, ['what' => 'faint', 'side' => $side]);
                    if ($side === 1) {
                        $this->reward($b->parties[1][$shown[1]['which']], $hp, $shown);
                    }
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
                case 'held':
                    $line = $name($side) . '的' . $this->things[$e[2]]['name'] . '起了作用！';
                    break;
                case 'cured':
                    if ($e[2] !== 'confuse') {
                        $shown[$side]['status'] = $status[$side][$shown[$side]['which']] = null;
                    }
                    $line = $name($side) . ($e[2] === 'confuse' ? '不再混乱了！' : '恢复了正常！');
                    break;
                default:
                    throw new LogicException("the battle wrote '{$e[0]}', which has no line");
            }
            if ($line !== null) {
                $this->say($line, $shown, $cue);
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

    private function say(string $line, ?array $shown = null, ?array $cue = null): void
    {
        $this->queue[] = [$line, $shown ?? $this->shownLast(), $cue];
    }

    /** What an event with no line of its own shows comes with the line before it: a hit, with the line of the move that made it. */
    private function amend(array $shown, int $struck, bool $critical): void
    {
        $last = array_key_last($this->queue);
        $this->queue[$last][1] = $shown;
        if (($this->queue[$last][2]['what'] ?? null) === 'use') {
            [$this->queue[$last][2]['hit'], $this->queue[$last][2]['critical']] = [$struck, $critical];
        }
    }

    /**
     * The next line. With none left: a move a beast has come to is learned, or, when it knows four, asked about and
     * then chosen for in the list; then the end, or the party's list when the player's beast has fallen, or the menu.
     */
    private function next(): void
    {
        if ($this->queue !== []) {
            [$this->line, $this->shown, $this->cue] = array_shift($this->queue);
            $this->phase = 'text';
            $this->lines++;
        } elseif ($this->toLearn !== []) {
            [$i, $move] = $this->toLearn[0];
            $f = $this->battle->parties[0][$i];
            if (count($f->moves) < Fighter::MOVES) {
                $f->learn($move, $this->moves);
                $f->bond = Formulas::bond($f->bond, 'move');
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
