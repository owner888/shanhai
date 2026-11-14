<?php
declare(strict_types=1);

namespace Shanhai\Battle;

/**
 * Battles played out with no player, to weigh the tables (roadmap 1.2): every beast against every other, one on one,
 * at one level, each side using the move that would do the most. It measures the data; when a number is off, the
 * data is changed, never a formula.
 */
final class Simulator
{
    /** Turns after which a battle with no winner is counted a draw. */
    public const TURNS = 200;

    /**
     * @param array<string, array> $beasts
     * @param array<string, list<array{0: int, 1: string}>> $learnsets
     * @param array<string, array> $moves
     * @param array<string, array> $elements
     */
    public function __construct(private readonly array $beasts, private readonly array $learnsets, private readonly array $moves, private readonly array $elements) {}

    /** The move a side that only weighs damage uses: the one that would do the most; the first it can use when none does any. */
    public static function pick(Battle $b, int $side): array
    {
        $best = null;
        $most = -1.0;
        foreach ($b->usable($side) as $i) {
            $d = $b->estimate($side, $i);
            if ($d > $most) {
                [$best, $most] = [$i, $d];
            }
        }
        return ['move', $best ?? 0];
    }

    /** One battle of $a against $b at $level: 0 when $a wins, 1 when $b does, -1 for neither. */
    public function battle(string $a, string $b, int $level, int $seed): int
    {
        $make = fn(string $id) => new Fighter($id, $this->beasts[$id], $level, $this->learnsets[$id], $this->moves);
        $battle = new Battle([[$make($a)], [$make($b)]], $this->elements, $this->moves, Rng::seeded($seed));
        while ($battle->winner === null && $battle->turns < self::TURNS) {
            $battle->turn(self::pick($battle, 0), self::pick($battle, 1));
        }
        return $battle->winner ?? -1;
    }

    /**
     * Every pair, $rounds battles each: how often the first of a pair beat the second, from 0 to 1, a draw counting half.
     * @return array<string, array<string, float>>
     */
    public function table(int $level, int $rounds): array
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
                    $w = $this->battle($a, $b, $level, ($i * 1000 + $j) * 1000 + $r);
                    $won += $w === 0 ? 1.0 : ($w === -1 ? 0.5 : 0.0);
                }
                $wins[$a][$b] = $won / $rounds;
                $wins[$b][$a] = 1.0 - $won / $rounds;
            }
        }
        return $wins;
    }
}
