<?php
declare(strict_types=1);

namespace Shanhai\Battle;

use InvalidArgumentException;

/**
 * The numbers' baseline as code: each function is one rule of docs/numbers.md, named by its section, with its
 * divisions whole and in the order written there. Nothing here is tuned; a change starts in that document.
 */
final class Formulas
{
    public const MAX_STAT = 999, MAX_LEVEL = 100;
    /** 3.2: damage before its last +2 is capped at this. */
    public const DAMAGE_CAP = 997, DAMAGE_FLOOR = 2;
    /** 4: a random byte under these is a critical hit, by stage. */
    public const CRITICAL = [17, 32, 64, 85, 128, 128, 128];
    /** 5: what a stage from -6 to +6 multiplies a stat by, as [times, over]. */
    public const STAGES = [[25, 100], [28, 100], [33, 100], [40, 100], [50, 100], [66, 100], [1, 1], [15, 10], [2, 1], [25, 10], [3, 1], [35, 10], [4, 1]];
    /** 5: the same for accuracy and evasion. */
    public const ACCURACY_STAGES = [[33, 100], [36, 100], [43, 100], [50, 100], [60, 100], [75, 100], [1, 1], [133, 100], [166, 100], [2, 1], [233, 100], [133, 50], [3, 1]];
    /** 3.4: the random multiplier of damage is from this to 255, over 255. */
    public const VARY_FROM = 217;

    /** 2: a stat from its base value, individual value (0 to 15), stat experience (0 to 65535) and level. */
    public static function stat(int $base, int $dv, int $statExp, int $level, bool $hp): int
    {
        $root = min(255, (int)ceil(sqrt($statExp)));
        $n = intdiv((($base + $dv) * 2 + intdiv($root, 4)) * $level, 100);
        return min(self::MAX_STAT, $hp ? $n + $level + 10 : $n + 5);
    }

    /** 2: the hp's individual value, made of the low bits of the other four. */
    public static function hpDv(int $atk, int $def, int $spe, int $spc): int
    {
        return ($atk & 1) << 3 | ($def & 1) << 2 | ($spe & 1) << 1 | ($spc & 1);
    }

    /** 3.1: attack and defense as the damage formula takes them: both quartered while either is over 255. @return array{0: int, 1: int} */
    public static function truncate(int $attack, int $defense): array
    {
        while ($attack > 255 || $defense > 255) {
            $attack = max(1, intdiv($attack, 4));
            $defense = max(1, intdiv($defense, 4));
        }
        return [$attack, max(1, $defense)];
    }

    /** 3.2: the damage of a move of $power, before weather, the same element's bonus, the elements and the random spread. */
    public static function damage(int $level, int $power, int $attack, int $defense, bool $critical, int $itemBoost = 0): int
    {
        if ($power === 0) {
            return 0;
        }
        [$attack, $defense] = self::truncate($attack, $defense);
        $d = intdiv($level * 2, 5) + 2;
        $d = intdiv(intdiv($d * $power * $attack, $defense), 50);
        if ($itemBoost > 0) {
            $d = intdiv($d * (100 + $itemBoost), 100);
        }
        if ($critical) {
            $d *= 2;
        }
        return min($d, self::DAMAGE_CAP) + self::DAMAGE_FLOOR;
    }

    /** 3.3 and 11.3: damage times a factor in tenths (20, 15, 5); what was more than nothing stays at least 1. */
    public static function times(int $damage, int $tenths): int
    {
        $out = intdiv($damage * $tenths, 10);
        return $damage > 0 && $out === 0 ? 1 : $out;
    }

    /** 3.3: a move of one of its user's own elements does half as much again. */
    public static function sameElement(int $damage): int
    {
        return $damage + intdiv($damage, 2);
    }

    /** 3.4: the roll of the random spread, 217 to 255, from random bytes: each turned one bit to the right, taken again while under 217. */
    public static function varyRoll(Rng $rng): int
    {
        do {
            $b = $rng->byte();
            $r = ($b >> 1) | (($b & 1) << 7);
        } while ($r < self::VARY_FROM);
        return $r;
    }

    /** 3.4: damage with the random spread's roll; under 2 it is left alone. */
    public static function vary(int $damage, int $roll): int
    {
        return $damage < 2 ? $damage : intdiv($damage * $roll, 255);
    }

    /** 5: a stat at a stage from -6 to +6. */
    public static function staged(int $stat, int $stage): int
    {
        [$times, $over] = self::STAGES[$stage + 6];
        return min(self::MAX_STAT, max(1, intdiv($stat * $times, $over)));
    }

    /** 6: what a random byte must be under for a move of $percent accuracy to hit; 255 always hits. */
    public static function accuracy(int $percent, int $accuracyStage, int $evasionStage): int
    {
        $a = intdiv($percent * 255, 100);
        foreach ([$accuracyStage + 6, 6 - $evasionStage] as $i) {
            [$times, $over] = self::ACCURACY_STAGES[$i];
            $a = max(1, intdiv($a * $times, $over));
        }
        return min(255, $a);
    }

    /** What a random byte must be under for an effect of $percent chance to happen: read as accuracy is (docs/numbers.md 12). */
    public static function chance(int $percent): int
    {
        return intdiv($percent * 255, 100);
    }

    /** 7.2: the experience a beast of a growth rate has in all at level $n. */
    public static function expAtLevel(string $growth, int $n): int
    {
        $cube = $n ** 3;
        $exp = match ($growth) {
            'mediumFast' => $cube,
            'slightlyFast' => intdiv(3 * $cube, 4) + 10 * $n ** 2 - 30,
            'slightlySlow' => intdiv(3 * $cube, 4) + 20 * $n ** 2 - 70,
            'mediumSlow' => intdiv(6 * $cube, 5) - 15 * $n ** 2 + 100 * $n - 140,
            'fast' => intdiv(4 * $cube, 5),
            'slow' => intdiv(5 * $cube, 4),
            default => throw new InvalidArgumentException("no growth rate '$growth'"),
        };
        return max(0, $exp); // 9: as meant, nothing below zero at level 1
    }

    /** 7.1: the experience for defeating a beast of $baseExp at $level, shared among $participants; half as much again from a trainer's. */
    public static function expGain(int $baseExp, int $level, int $participants, bool $trainer): int
    {
        $exp = intdiv(intdiv($baseExp, $participants) * $level, 7);
        return $trainer ? $exp + intdiv($exp, 2) : $exp;
    }

    /** 8: what a random byte must not be over for a beast to be caught. $status: null, 'sleep', or another. */
    public static function catchValue(int $rate, int $maxHp, int $hp, ?string $status): int
    {
        [$a, $b] = [3 * $maxHp, 2 * $hp];
        while ($a > 255) { // 9: as meant, until it fits
            [$a, $b] = [intdiv($a, 4), max(1, intdiv($b, 4))];
        }
        $x = max(1, intdiv($rate * ($a - $b), $a));
        $x += match ($status) { null => 0, 'sleep' => 10, default => 5 }; // 9: as meant, the other statuses add 5
        return min(255, $x);
    }

    /** 11.4: what a random byte must not be over to flee on the $attempt-th try; null when fleeing is sure. */
    public static function fleeValue(int $speed, int $foeSpeed, int $attempt): ?int
    {
        if ($speed >= $foeSpeed) {
            return null;
        }
        $d = intdiv($foeSpeed, 4) & 0xff;
        if ($d === 0) {
            return null;
        }
        $f = intdiv($speed * 32, $d) + 30 * ($attempt - 1);
        return $f > 255 ? null : $f;
    }
}
