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
    /** A stage goes from this many under to this many over. */
    public const MAX_STAGE = 6;
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

    /** 8: by what a catch needs (catchValue), the first row it is not over: what a random byte must be under for the binding to hold once more before it breaks. */
    public const WOBBLES = [
        [1, 63], [2, 75], [3, 84], [4, 90], [5, 95], [7, 103], [10, 113], [15, 126], [20, 134], [30, 149], [40, 160], [50, 169],
        [60, 177], [80, 191], [100, 201], [120, 211], [140, 220], [160, 227], [180, 234], [200, 240], [220, 246], [240, 251], [254, 253], [255, 255],
    ];

    /** 8: of a hundred, how often a catch that needs $catchValue binds: a byte of the 256 not over it, rounded down. */
    public static function catchPercent(int $catchValue): int
    {
        return intdiv(($catchValue + 1) * 100, 256);
    }

    /** 8: for a catch that failed, what a random byte must be under for each of the three times the binding seems to hold. */
    public static function wobbleLimit(int $catchValue): int
    {
        foreach (self::WOBBLES as [$upTo, $limit]) {
            if ($catchValue <= $upTo) {
                return $limit;
            }
        }
        throw new InvalidArgumentException("no catch value $catchValue");
    }

    /** 13: of a hundred, the last number that falls to each of the seven wild beasts a map's grass holds: 30, 30, 20, 10, 5, 4 and 1 in a hundred. */
    public const WILD_SLOTS = [30, 60, 80, 90, 95, 99, 100];
    /** 13: of a hundred, the last number that falls to each of the three wild beasts a map's water holds: 60, 30 and 10 in a hundred. */
    public const WATER_SLOTS = [60, 90, 100];
    /**
     * 8, 22: a kind's catch rate as a seal makes it, 255 at most. By a number, that many hundredths of it. By 'level', twice
     * with the wild beast's level under that of the player's beast, four times under half of it, eight under a quarter.
     * By 'water', three times in a battle begun on the water. By 'bond', as it is: that seal changes what is bound, not the binding.
     */
    public static function sealRate(int $rate, int|string $by, int $mine = 0, int $theirs = 0, bool $water = false): int
    {
        $times = match ($by) {
            'level' => $theirs >= $mine ? 1 : ($theirs >= $mine >> 1 ? 2 : ($theirs >= $mine >> 2 ? 4 : 8)),
            'water' => $water ? 3 : 1,
            'bond' => 1,
            default => null,
        };
        return min(255, $times === null ? intdiv($rate * $by, 100) : $rate * $times);
    }

    /** 17: the money a new game begins with, the most there can be, and the most of one thing a bag holds. */
    public const START_MONEY = 3000, MAX_MONEY = 999999, MAX_STACK = 99;
    /** 17: what the one beaten pays for each level of their last beast, before it is counted four times: a god, and one met on the road. */
    public const REWARD_GOD = 25, REWARD_ROAD = 8;

    /** 17: what winning pays: the base of the one beaten, times the level of their last beast, four times over. */
    public static function reward(int $base, int $level): int
    {
        return 4 * $base * $level;
    }

    /**
     * 23: a beast's four individual values, 0 to 15 each, from two bytes: attack and defense the first's halves, speed and
     * special the second's. @return array{atk: int, def: int, spe: int, spc: int}
     */
    public static function dvs(int $first, int $second): array
    {
        return ['atk' => $first >> 4, 'def' => $first & 15, 'spe' => $second >> 4, 'spc' => $second & 15];
    }

    /** 23: what is said of a beast's individual values, by what the four add up to (0 to 60): these words are the game's own. */
    public const TALENT = [48 => '万里挑一', 38 => '出众', 23 => '寻常', 0 => '平平'];

    /** 23: the word for individual values. @param array{atk: int, def: int, spe: int, spc: int} $dvs */
    public static function talent(array $dvs): string
    {
        foreach (self::TALENT as $from => $word) {
            if (array_sum($dvs) >= $from) {
                return $word;
            }
        }
    }

    /** 25: the bond a beast begins with, the most there is, what one bound with the seal of kinship begins with, and what it takes to come to the move of the bond. */
    public const BOND_BASE = 70, BOND_MAX = 255, BOND_SEAL = 200, BOND_MOVE = 220;
    /** 25: how many steps the player walks for each of the party to grow one closer. */
    public const BOND_STEPS = 512;
    /** 25: what a thing that happens changes a bond by: with the bond under 100, under 200, and otherwise. */
    public const BOND_CHANGES = [
        'level' => [5, 3, 2],       // it gained a level
        'move' => [1, 1, 0],        // it learned a move
        'trial' => [3, 2, 1],       // it went into a god's trial, able to fight
        'fainted' => [-1, -1, -1],  // it fell in a battle
        'outmatched' => [-5, -5, -10], // it fell to one thirty levels or more over it
        'step' => [1, 1, 1],        // the player walked BOND_STEPS with it
    ];
    /** 25: what is said of a bond, from what it is and over: the grades are the reference's, the words the game's own. */
    public const BOND_WORDS = [250 => '形影不离', 200 => '十分亲近', 150 => '亲近', 100 => '渐渐熟了', 50 => '还生分', 0 => '疏远'];

    /** 25: a bond after $why has happened, from 0 to BOND_MAX. */
    public static function bond(int $now, string $why): int
    {
        return max(0, min(self::BOND_MAX, $now + self::BOND_CHANGES[$why][$now < 100 ? 0 : ($now < 200 ? 1 : 2)]));
    }

    /** 25: the word for a bond. */
    public static function bondWord(int $bond): string
    {
        foreach (self::BOND_WORDS as $from => $word) {
            if ($bond >= $from) {
                return $word;
            }
        }
    }

    /** 25: the power of the move of the bond: the bond times ten over twenty-five, and 1 at least. */
    public static function bondPower(int $bond): int
    {
        return max(1, intdiv($bond * 10, 25));
    }

    /** 20: a wild beast holds nothing on a byte under the first; else its kind's second thing on a byte under the second, its first on any other. */
    public const WILD_HOLDS_NONE = 192, WILD_HOLDS_SECOND = 20;

    /** 13: the steps after entering a map, or a battle, of which only the last can meet a wild beast. */
    public const WILD_WAIT = 5;

    /** 13: what a random byte must be under for a step in grass to meet a wild beast, on a map whose rate is $percent. */
    public static function wildLimit(int $percent): int
    {
        return intdiv($percent * 255, 100);
    }

    /**
     * 13: which of its beasts the grass gives (WILD_SLOTS), or the water (WATER_SLOTS): bytes are asked for until one is
     * under a hundred, and it, plus one, is looked up in $slots.
     * @param list<int> $slots
     */
    public static function wildSlot(Rng $rng, array $slots = self::WILD_SLOTS): int
    {
        do {
            $n = $rng->byte();
        } while ($n >= 100);
        foreach ($slots as $slot => $last) {
            if ($n + 1 <= $last) {
                return $slot;
            }
        }
        throw new \LogicException('unreachable: the last slot ends at a hundred');
    }

    /**
     * 13: on water a wild beast's level is what the map says and up to four more, by one byte: none under 35 in a
     * hundred of 255, one under 65, two under 85, three under 95, four from there.
     */
    public static function waterLevelBonus(int $byte): int
    {
        $bonus = 0;
        foreach ([35, 65, 85, 95] as $percent) {
            $bonus += $byte >= self::wildLimit($percent) ? 1 : 0;
        }
        return $bonus;
    }

    /** 7.3: the level a beast of a growth rate is at with $exp in all: the last whose experience it has. */
    public static function levelAt(string $growth, int $exp): int
    {
        $level = 1;
        while ($level < self::MAX_LEVEL && self::expAtLevel($growth, $level + 1) <= $exp) {
            $level++;
        }
        return $level;
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
