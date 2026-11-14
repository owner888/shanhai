<?php
// What a move that does no damage is worth: php tools/effects.php [level=50] [rounds=10]
// Every pair of beasts of tiers 2 and 3, one on one, both sides only weighing damage, three ways:
//   as it is     neither does anything else
//   a turn spent the first beast spends its first turn on the effect (a move of its own, that never misses), then fights on
//   for free     the effect is already there when the battle starts
// 'a turn spent' against 'as it is' says whether the effect is worth a turn; 'for free' says how strong it is in itself.
// 'close' counts only the pairs where the first beast wins 25% to 75% as it is: where a move could turn a battle.
declare(strict_types=1);

require __DIR__ . '/../src/load.php';

use Shanhai\Battle\{Battle, Fighter, Rng, Simulator};
use Shanhai\Data;

$level = (int)($argv[1] ?? 50);
$rounds = (int)($argv[2] ?? 10);
$beasts = Data::beasts();
$elements = Data::elements();
$learnsets = Data::learnsets();
$moves = Data::moves();
$field = array_values(array_filter(array_keys($beasts), fn(string $id) => in_array($beasts[$id]['tier'], [2, 3], true)));

/** What having the effect already means, on the first beast $u or the second $t. */
$free = [
    'sleep' => function (Fighter $u, Fighter $t) { [$t->status, $t->sleep] = ['sleep', 4]; },
    'paralyze' => function (Fighter $u, Fighter $t) { $t->status = 'paralyze'; },
    'poison' => function (Fighter $u, Fighter $t) { $t->status = 'poison'; },
    'confuse' => function (Fighter $u, Fighter $t) { $t->confused = 4; },
];
$effects = ['sleep', 'paralyze', 'poison', 'confuse', 'down:atk:1', 'down:atk:2', 'down:def:1', 'down:spd:1', 'down:spe:1',
    'up:atk:2', 'up:spa:2', 'up:def:2', 'up:spd:2', 'up:spe:2', 'up:eva:1', 'rain', 'sun', 'sandstorm'];
foreach ($effects as $e) {
    $moves["x:$e"] = ['name' => $e, 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 1, 'effect' => [$e, 100]];
}

/** How often $a beats $b: 'plain', 'spent' or 'free'. */
$rate = function (string $a, string $b, string $how, string $effect) use ($beasts, $learnsets, $moves, $elements, $level, $rounds, $free): float {
    $won = 0.0;
    for ($r = 0; $r < $rounds; $r++) {
        $u = new Fighter($a, $beasts[$a], $level, $learnsets[$a], $moves);
        $t = new Fighter($b, $beasts[$b], $level, $learnsets[$b], $moves);
        $battle = new Battle([[$u], [$t]], $elements, $moves, Rng::seeded(crc32("$a $b") % 100000 * 100 + $r));
        if ($how === 'spent') {
            $u->moves[] = ['id' => "x:$effect", 'pp' => 1];
            $battle->turn(['move', count($u->moves) - 1], Simulator::pick($battle, 1));
        } elseif ($how === 'free') {
            if (isset($free[$effect])) {
                $free[$effect]($u, $t);
            } elseif (in_array($effect, ['rain', 'sun', 'sandstorm'], true)) {
                [$battle->weather, $battle->weatherLeft] = [$effect, Battle::WEATHER_TURNS];
            } else {
                [$way, $stat, $by] = explode(':', $effect);
                $who = $way === 'up' ? $u : $t;
                $who->stages[$stat] = $way === 'up' ? (int)$by : -(int)$by;
            }
        }
        while ($battle->winner === null && $battle->turns < Simulator::TURNS) {
            $battle->turn(Simulator::pick($battle, 0), Simulator::pick($battle, 1));
        }
        $won += $battle->winner === 0 ? 1.0 : ($battle->winner === 1 ? 0.0 : 0.5);
    }
    return $won / $rounds;
};

$plain = [];
$turns = 0;
foreach ($field as $a) {
    foreach ($field as $b) {
        if ($a !== $b) {
            $plain["$a $b"] = $rate($a, $b, 'plain', '');
        }
    }
}
$close = array_filter($plain, fn(float $x) => $x >= 0.25 && $x <= 0.75);
printf("level %d, %d battles a pair, %d pairs of which %d are close\n\n", $level, $rounds, count($plain), count($close));
printf("%-12s %21s   %21s\n%-12s %10s %10s   %10s %10s\n", '', 'all pairs', 'close pairs', 'effect', 'turn spent', 'for free', 'turn spent', 'for free');
$show = fn(float $d) => str_pad(($d >= 0 ? '+' : '') . round($d * 100) . ' pts', 10, ' ', STR_PAD_LEFT);
foreach ($effects as $e) {
    $sum = ['spent' => [0.0, 0.0], 'free' => [0.0, 0.0]];
    foreach ($plain as $pair => $base) {
        [$a, $b] = explode(' ', $pair);
        foreach (['spent', 'free'] as $how) {
            $d = $rate($a, $b, $how, $e) - $base;
            $sum[$how][0] += $d;
            $sum[$how][1] += isset($close[$pair]) ? $d : 0.0;
        }
    }
    printf("%-12s %s %s   %s %s\n", $e, $show($sum['spent'][0] / count($plain)), $show($sum['free'][0] / count($plain)),
        $show($sum['spent'][1] / max(1, count($close))), $show($sum['free'][1] / max(1, count($close))));
}
