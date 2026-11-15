<?php
declare(strict_types=1);

namespace Shanhai\Battle;

/**
 * Battles played out with no player, to weigh the tables (roadmap 1.2): one on one, every beast against every other,
 * and party against party, with switching. Both sides only weigh damage. It measures the data; when a number is off,
 * the data is changed, never a formula.
 */
final class Simulator
{
    /** Turns after which a battle with no winner is counted a draw. */
    public const TURNS = 300;
    /** How much better, on the scale of standing(), a beast waiting must be than the one out for a side to switch. */
    public const SWITCH_MARGIN = 0.5;
    /**
     * The effects a careful player keeps a move for before any other, the most worth first: those that tools/effects.php
     * measures as worth the turn they take (docs/balance.md). A beast with none of these keeps the last such move it learned.
     */
    public const KEEPS_FIRST = ['sleep', 'confuse', 'paralyze'];
    /** think(): how many times each thing a side could do is played on, and for how many turns at most. */
    public const ROLLOUTS = 4, AHEAD = 40;

    /**
     * @param array<string, array> $beasts
     * @param array<string, list<array{0: int, 1: string}>> $learnsets
     * @param array<string, array> $moves
     * @param array<string, array> $elements
     */
    public function __construct(private readonly array $beasts, private readonly array $learnsets, private readonly array $moves, private readonly array $elements) {}

    /** The most any of $u's moves would do to $t, and which move. @return array{0: float, 1: int} */
    private static function best(Battle $b, Fighter $u, Fighter $t): array
    {
        $most = -1.0;
        $which = 0;
        foreach ($u->moves as $i => $m) {
            if ($m['pp'] > 0 && ($d = $b->estimate($u, $t, $i)) > $most) {
                [$most, $which] = [$d, $i];
            }
        }
        return [max(0.0, $most), $which];
    }

    /** How $u stands against $t, from -1 to 1: the share of $t's hp its best move takes, less the share of its own that $t's takes. */
    public static function standing(Battle $b, Fighter $u, Fighter $t): float
    {
        return min(1.0, self::best($b, $u, $t)[0] / max(1, $t->hp)) - min(1.0, self::best($b, $t, $u)[0] / max(1, $u->hp));
    }

    /** The move a side that only weighs damage uses: the one that would do the most. */
    public static function pick(Battle $b, int $side): array
    {
        return ['move', self::best($b, $b->fighter($side), $b->fighter(1 - $side))[1]];
    }

    /**
     * As pick(), but a side whose beast stands much worse against the other's than one waiting does sends that one in,
     * unless it switched the turn before ($switched, which this keeps).
     * @param array{0: bool, 1: bool} $switched
     */
    public static function pickOrSwitch(Battle $b, int $side, array &$switched): array
    {
        $foe = $b->fighter(1 - $side);
        $now = self::standing($b, $b->fighter($side), $foe);
        $better = null;
        $most = $now + self::SWITCH_MARGIN;
        foreach ($b->parties[$side] as $i => $f) {
            if ($i !== $b->active[$side] && !$f->fainted() && ($s = self::standing($b, $f, $foe)) > $most) {
                [$better, $most] = [$i, $s];
            }
        }
        if ($better !== null && !$switched[$side]) {
            $switched[$side] = true;
            return ['switch', $better];
        }
        $switched[$side] = false;
        return self::pick($b, $side);
    }

    /**
     * What a side that thinks ahead does: each thing it could do — any move it can use, any beast it could send in — is
     * played on ROLLOUTS times against a side that only weighs damage, and it does what came out best. So a move that
     * does no damage, the weather, a switch are used when they win battles, by no weight of ours.
     */
    public static function think(Battle $b, int $side, int $seed): array
    {
        $options = array_map(fn(int $i) => ['move', $i], $b->usable($side));
        foreach ($b->parties[$side] as $i => $f) {
            if ($i !== $b->active[$side] && !$f->fainted()) {
                $options[] = ['switch', $i];
            }
        }
        if (count($options) <= 1) {
            return $options[0] ?? ['move', 0];
        }
        $best = $options[0];
        $most = -INF;
        foreach ($options as $option) {
            $worth = 0.0;
            for ($k = 0; $k < self::ROLLOUTS; $k++) {
                $c = $b->copy(Rng::seeded($seed * 31 + $k)); // the same rolls for each option
                $other = self::pick($c, 1 - $side);
                $c->turn(...($side === 0 ? [$option, $other] : [$other, $option]));
                for ($t = 0; $c->winner === null && $t < self::AHEAD; $t++) {
                    $c->turn(self::pick($c, 0), self::pick($c, 1));
                }
                $worth += self::worth($c, $side);
            }
            if ($worth > $most) {
                [$best, $most] = [$option, $worth];
            }
        }
        return $best;
    }

    /** How a battle played on came out for a side: won 1, lost 0, and in between by the hp each party has left. */
    private static function worth(Battle $b, int $side): float
    {
        $left = [];
        foreach ([0, 1] as $s) {
            $left[$s] = array_sum(array_map(fn(Fighter $f) => $f->hp / $f->maxHp, $b->parties[$s])) / count($b->parties[$s]);
        }
        return match ($b->winner) {
            $side => 1.0 + 0.1 * $left[$side],
            1 - $side => -0.1 * $left[1 - $side],
            default => 0.5 + 0.4 * ($left[$side] - $left[1 - $side]),
        };
    }

    /**
     * Which four moves a beast keeps, of those it has learned by $level, when a player who chose with care had it: its
     * three strongest that do damage, no two of one element, and one that does none: the one whose effect is first in
     * KEEPS_FIRST, else the last it learned. With no such move, or too few of the others, the strongest left fill the four.
     * @return list<string>
     */
    public function kept(string $id, int $level): array
    {
        $damage = $none = [];
        foreach ($this->learnsets[$id] as [$at, $move]) {
            if ($at <= $level) {
                if ($this->moves[$move]['power'] > 0) {
                    $damage[] = $move;
                } else {
                    $none[] = $move;
                }
            }
        }
        usort($damage, fn(string $a, string $b) => $this->moves[$b]['power'] * $this->moves[$b]['accuracy'] <=> $this->moves[$a]['power'] * $this->moves[$a]['accuracy']);
        $keep = $none === [] ? [] : [end($none)];
        foreach (array_reverse(self::KEEPS_FIRST) as $effect) {
            foreach ($none as $move) {
                if ($this->moves[$move]['effect'][0] === $effect) {
                    $keep = [$move];
                }
            }
        }
        $elements = [];
        foreach ($damage as $move) { // the strongest of each element first
            $e = $this->moves[$move]['element'] ?? '';
            if (count($keep) < 4 && !isset($elements[$e])) {
                $keep[] = $move;
                $elements[$e] = true;
            }
        }
        foreach ($damage as $move) {
            if (count($keep) < 4 && !in_array($move, $keep, true)) {
                $keep[] = $move;
            }
        }
        return $keep;
    }

    private function fighter(string $id, int $level): Fighter
    {
        return new Fighter($id, $this->beasts[$id], $level, $this->learnsets[$id], $this->moves, keeps: $this->kept($id, $level));
    }

    /**
     * One battle of party $a against party $b at $level: 0 when $a wins, 1 when $b does, -1 for neither.
     * $think: both sides think ahead (think()); else both only weigh damage, and switch on standing.
     * @param list<string> $a
     * @param list<string> $b
     */
    public function battle(array $a, array $b, int $level, int $seed, bool $think = false): int
    {
        $battle = new Battle([array_map(fn(string $id) => $this->fighter($id, $level), $a), array_map(fn(string $id) => $this->fighter($id, $level), $b)],
            $this->elements, $this->moves, Rng::seeded($seed));
        $switched = [false, false];
        while ($battle->winner === null && $battle->turns < self::TURNS) {
            $at = $seed * 1000 + $battle->turns;
            $battle->turn(
                $think ? self::think($battle, 0, $at * 2) : self::pickOrSwitch($battle, 0, $switched),
                $think ? self::think($battle, 1, $at * 2 + 1) : self::pickOrSwitch($battle, 1, $switched),
            );
        }
        return $battle->winner ?? -1;
    }

    /**
     * Every pair one on one, $rounds battles each: how often the first of a pair beat the second, from 0 to 1, a draw counting half.
     * @return array<string, array<string, float>>
     */
    public function table(int $level, int $rounds, bool $think = false): array
    {
        $ids = array_keys($this->beasts);
        $wins = [];
        foreach ($ids as $i => $a) {
            foreach ($ids as $j => $b) {
                if ($j <= $i) {
                    continue;
                }
                $won = 0.0;
                for ($r = 0; $r < $rounds; $r++) {
                    $w = $this->battle([$a], [$b], $level, ($i * 1000 + $j) * 1000 + $r, $think);
                    $won += $w === 0 ? 1.0 : ($w === -1 ? 0.5 : 0.0);
                }
                $wins[$a][$b] = $won / $rounds;
                $wins[$b][$a] = 1.0 - $won / $rounds;
            }
        }
        return $wins;
    }

    /**
     * $battles battles between parties of $size drawn at random from $field, no beast twice in a party: how often a party
     * with each beast in it won, a draw counting half.
     * @param list<string> $field
     * @return array<string, float>
     */
    public function parties(array $field, int $size, int $level, int $battles, int $seed, bool $think = false): array
    {
        $rng = Rng::seeded($seed);
        $draw = function () use ($rng, $field, $size): array {
            $left = $field;
            $party = [];
            while (count($party) < $size) {
                $i = ($rng->byte() << 8 | $rng->byte()) % count($left);
                $party[] = $left[$i];
                array_splice($left, $i, 1);
            }
            return $party;
        };
        $won = $played = array_fill_keys($field, 0.0);
        for ($n = 0; $n < $battles; $n++) {
            [$a, $b] = [$draw(), $draw()];
            $w = $this->battle($a, $b, $level, $seed * 100000 + $n, $think);
            foreach ([0 => $a, 1 => $b] as $side => $party) {
                foreach ($party as $id) {
                    $played[$id]++;
                    $won[$id] += $w === $side ? 1.0 : ($w === -1 ? 0.5 : 0.0);
                }
            }
        }
        $rate = [];
        foreach ($field as $id) {
            $rate[$id] = $played[$id] > 0 ? $won[$id] / $played[$id] : 0.5;
        }
        return $rate;
    }
}
