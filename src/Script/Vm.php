<?php
declare(strict_types=1);

namespace Shanhai\Script;

use InvalidArgumentException;
use RuntimeException;
use Shanhai\Battle\{Ai, Formulas};
use Shanhai\World\{Dir, World};

/**
 * Runs a map's event script: a list of commands, each [name, arguments...].
 *
 *     ['say', text]                       shows a line; waits for confirm
 *     ['setFlag', flag]  ['clearFlag', flag]
 *     ['ifFlag', flag, label]             jumps when the flag is set
 *     ['ifNotFlag', flag, label]
 *     ['goto', label]    ['label', name]
 *     ['face', actor, dir]                actor: 'player' or an NPC's id
 *     ['move', actor, dir, tiles]         waits until the actor has walked them
 *     ['warp', map, x, y, dir]
 *     ['give', item, count]
 *     ['giveBeast', beast, level]
 *     ['ask', line, [[choice, label], ...]]   shows the line with the choices; goes to the label of the one taken
 *     ['battle', beast, level]   a wild one; the script goes on when it is over, unless it was lost
 *     ['trial', who, [[beast or god, level], ...]]   a trial against their beasts, one holding a thing written [beast, level, thing]; the script goes on when it is passed.
 *                                                    A fourth, [thing, ...], is what they use on their beasts in it (docs/numbers.md 26)
 *     ['duel', who, [[beast, level], ...]]   a battle with one met on the road, by a trial's rules; the script goes on when it is won
 *     ['rest']                   the party's beasts are whole again, and here is where a battle lost wakes the player
 *     ['pen']                    opens the pen, where beasts are left and taken; the script goes on when it is closed
 *     ['shop', [item, ...]]      opens buying those things; the script goes on when the player leaves
 *     ['league', who, [[beast, level], ...]]         as 'trial', for one of the five at the Lower Capital (docs/numbers.md 32): several stand in one
 *                                                    script, one after another with no rest between, and to lose to any is to begin again
 *     ['rite']                   asks which of the party's beasts receives a rite it could now (docs/numbers.md 27), and gives it; says so when none could
 *     ['float']                  the player goes onto the water faced, carried by a beast of the party
 *     ['load']                   the saved game takes the world's place
 *     ['end']
 */
final class Vm
{
    /** command => how many arguments */
    public const ARGS = [
        'say' => 1, 'setFlag' => 1, 'clearFlag' => 1, 'ifFlag' => 2, 'ifNotFlag' => 2, 'goto' => 1, 'label' => 1,
        'face' => 2, 'move' => 3, 'warp' => 4, 'give' => 2, 'giveBeast' => 2, 'ask' => 2, 'battle' => 2, 'trial' => 2, 'league' => 2, 'duel' => 2, 'rest' => 0, 'pen' => 0, 'shop' => 1, 'rite' => 0, 'float' => 0, 'load' => 0, 'end' => 0,
    ];

    public bool $done = false;
    private int $pc = 0;
    /** @var array<string, int> */
    private array $labels;
    private ?string $moving = null;
    private ?Dir $moveDir = null;
    private int $moveLeft = 0;
    /** @var ?list<string> the labels of the choices asked, while the answer is awaited */
    private ?array $asked = null;
    /** @var ?list<array{0: int, 1: int}> the rites asked among, while the answer is awaited; the choice after the last is none */
    private ?array $rites = null;
    public const NO_RITE = '「眼下没有哪一只能受祭。常兽二十五级，带璋玉来；受过璋礼的、或本就不弱的常兽三十六级，带璧玉来；受过璧礼又与你十分亲近的，带圭玉来。」';

    /** @param list<array> $script */
    public function __construct(private readonly array $script)
    {
        $this->labels = self::check($script);
    }

    /**
     * Checks a script as it is written: known commands, their argument counts, labels that exist, directions.
     * @param list<array> $script
     * @return array<string, int> where each label is
     */
    public static function check(array $script): array
    {
        $labels = [];
        foreach ($script as $i => $cmd) {
            $name = $cmd[0] ?? '';
            if (!isset(self::ARGS[$name])) {
                throw new InvalidArgumentException("script line $i: unknown command '$name'");
            }
            if (count($cmd) - 1 !== self::ARGS[$name] && !(in_array($name, ['trial', 'league'], true) && count($cmd) === 4)) {
                throw new InvalidArgumentException("script line $i: '$name' takes " . self::ARGS[$name] . ' arguments');
            }
            if ($name === 'label') {
                $labels[$cmd[1]] = $i;
            }
        }
        foreach ($script as $i => $cmd) {
            $targets = match ($cmd[0]) { 'goto' => [$cmd[1]], 'ifFlag', 'ifNotFlag' => [$cmd[2]], default => [] };
            if ($cmd[0] === 'ask') {
                if (!is_array($cmd[2]) || count($cmd[2]) < 2) {
                    throw new InvalidArgumentException("script line $i: 'ask' takes two choices or more");
                }
                foreach ($cmd[2] as $choice) {
                    if (!is_array($choice) || count($choice) !== 2 || !is_string($choice[0]) || $choice[0] === '') {
                        throw new InvalidArgumentException("script line $i: a choice is [what it says, the label it goes to]");
                    }
                    $targets[] = $choice[1];
                }
            }
            if ($cmd[0] === 'shop' && (!is_array($cmd[1]) || $cmd[1] === [] || !array_is_list($cmd[1]) || array_filter($cmd[1], fn($id) => !is_string($id)) !== [])) {
                throw new InvalidArgumentException("script line $i: 'shop' takes the things sold");
            }
            if (in_array($cmd[0], ['trial', 'league', 'duel'], true)) {
                if (!is_string($cmd[1]) || $cmd[1] === '' || !is_array($cmd[2]) || $cmd[2] === []) {
                    throw new InvalidArgumentException("script line $i: 'trial' takes who sets it and their beasts");
                }
                if (isset($cmd[3]) && ($cmd[0] === 'duel' || !is_array($cmd[3]) || $cmd[3] === [] || !array_is_list($cmd[3]) || array_diff($cmd[3], Ai::THINGS) !== [])) {
                    throw new InvalidArgumentException("script line $i: what the one who sets a trial uses is a list of " . implode(', ', Ai::THINGS));
                }
                foreach ($cmd[2] as $theirs) {
                    if (!is_array($theirs) || !in_array(count($theirs), [2, 3], true) || !is_string($theirs[0]) || !is_int($theirs[1]) || !is_string($theirs[2] ?? '')) {
                        throw new InvalidArgumentException("script line $i: a trial's beast is [beast or god, level], or with what it holds [beast, level, thing]");
                    }
                }
            }
            foreach ($targets as $target) {
                if (!isset($labels[$target])) {
                    throw new InvalidArgumentException("script line $i: no label '$target'");
                }
            }
            $dir = match ($cmd[0]) { 'face', 'move' => $cmd[2], 'warp' => $cmd[4], default => null };
            if ($dir !== null && Dir::tryFrom($dir) === null) {
                throw new InvalidArgumentException("script line $i: no direction '$dir'");
            }
        }
        return $labels;
    }

    /** Runs until the script waits (a line showing, an actor walking) or ends. */
    public function run(World $w): void
    {
        while (!$this->done) {
            if ($w->dialog !== null || $w->battle !== null || $w->pen !== null || $w->shop !== null) {
                return;
            }
            if ($this->rites !== null) {
                [$rites, $this->rites] = [$this->rites, null];
                if (isset($rites[$w->chosen])) {
                    $w->dialog = $w->rite(...$rites[$w->chosen]);
                    continue;
                }
            }
            if ($this->asked !== null) {
                $this->pc = $this->labels[$this->asked[$w->chosen]];
                $this->asked = null;
            }
            if ($this->moving !== null) {
                $a = $w->actor($this->moving);
                if ($a->moving) {
                    return;
                }
                if ($this->moveLeft > 0) {
                    if (!$w->walk($a, $this->moveDir)) {
                        throw new RuntimeException("script: '{$this->moving}' cannot walk {$this->moveDir->value} from {$a->x},{$a->y}");
                    }
                    $this->moveLeft--;
                    return;
                }
                $this->moving = null;
            }
            if ($this->pc >= count($this->script)) {
                $this->done = true;
                return;
            }
            $cmd = $this->script[$this->pc++];
            switch ($cmd[0]) {
                case 'say': $w->dialog = $cmd[1]; break;
                case 'setFlag': $w->flags[$cmd[1]] = true; break;
                case 'clearFlag': unset($w->flags[$cmd[1]]); break;
                case 'ifFlag': if (isset($w->flags[$cmd[1]])) { $this->pc = $this->labels[$cmd[2]]; } break;
                case 'ifNotFlag': if (!isset($w->flags[$cmd[1]])) { $this->pc = $this->labels[$cmd[2]]; } break;
                case 'goto': $this->pc = $this->labels[$cmd[1]]; break;
                case 'label': break;
                case 'face': $w->actor($cmd[1])->dir = Dir::from($cmd[2]); break;
                case 'move': [$this->moving, $this->moveDir, $this->moveLeft] = [$cmd[1], Dir::from($cmd[2]), $cmd[3]]; break;
                case 'warp': $w->enter($cmd[1], $cmd[2], $cmd[3], Dir::from($cmd[4])); break;
                case 'give': $w->items[$cmd[1]] = ($w->items[$cmd[1]] ?? 0) + $cmd[2]; break;
                case 'giveBeast': $w->join($cmd[1], $cmd[2]); break;
                case 'rest': $w->rest(); break;
                case 'pen': $w->pen(); break;
                case 'shop': $w->shop($cmd[1]); break;
                case 'rite':
                    $rites = $w->rites();
                    if ($rites === []) {
                        $w->dialog = self::NO_RITE;
                    } else {
                        $w->ask('「让哪一只受祭？」', [...array_map(fn(array $r) => $w->party[$r[0]]->species['name'] . ' 受' . Formulas::RITES[$r[1]]['name'] . '礼', $rites), '不用']);
                        $this->rites = $rites;
                    }
                    break;
                case 'float': $w->float(); break;
                case 'load': $w->load(); break;
                case 'ask': $w->ask($cmd[1], array_column($cmd[2], 0)); $this->asked = array_column($cmd[2], 1); break;
                case 'battle': $w->fight($cmd[1], $cmd[2]); break;
                case 'trial':
                case 'league': $w->trial($cmd[1], $cmd[2], $cmd[3] ?? []); break;
                case 'duel': $w->duel($cmd[1], $cmd[2]); break;
                case 'end': $this->done = true; break;
            }
        }
    }
}
