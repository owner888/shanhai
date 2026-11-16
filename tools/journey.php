<?php
// A game played through by a player with no hands (src/Journey.php): php tools/journey.php [seeds=5]
// For each of the three beasts to choose from and each seed: at what levels each trial was passed (the party's mean,
// its lowest and its highest), with how many beasts, after how many tries, wild battles and battles lost, and how long
// it took at 60 updates a second, and how much money it then had; and how often it changed a beast of its party for a better one at the pen. The player keeps a full party, as a person does.
declare(strict_types=1);

require __DIR__ . '/../src/load.php';

use Shanhai\Data;
use Shanhai\Journey;

$seeds = (int)($argv[1] ?? 5);
$beasts = Data::beasts();
$starters = array_keys(array_filter($beasts, fn(array $b) => $b['starter'] ?? false));
foreach ([0, 1, 2] as $starter) {
    $sum = [];
    $swaps = $rites = 0;
    for ($seed = 1; $seed <= $seeds; $seed++) {
        $j = new Journey(Data::newWorld($seed), Data::maps(), Data::moves(), Data::gods(), $starter);
        $j->play();
        $swaps += $j->swapped;
        $rites += $j->rites;
        $name = $beasts[$starters[$starter]]['name'];
        foreach ($j->passed as $p) {
            foreach (['tries', 'level', 'low', 'high', 'size', 'wild', 'lost', 'updates', 'money'] as $k) {
                $sum[$p['range']][$k] = ($sum[$p['range']][$k] ?? 0) + $p[$k];
            }
            $sum[$p['range']]['who'] = $p['who'];
        }
    }
    printf("%s first, the mean of %d games (wild battles, battles lost and time are counted from the start); %.1f times a beast was left in the pen for a better one, %.1f rites received; the last game's party at the end: %s\n",
        $name, $seeds, $swaps / $seeds, $rites / $seeds, implode('、', array_map(fn($f) => $f->species['name'] . ' ' . $f->level, $j->w->party)));
    foreach ($sum as $range => $s) {
        printf("  range %d %s: passed with %.1f beasts at level %.1f (%.0f to %.0f) on try %.1f, after %.0f wild battles, %.1f lost, %.0f minutes, with %.0f in money\n",
            $range, $s['who'], $s['size'] / $seeds, $s['level'] / $seeds, $s['low'] / $seeds, $s['high'] / $seeds, $s['tries'] / $seeds, $s['wild'] / $seeds, $s['lost'] / $seeds, $s['updates'] / $seeds / 3600, $s['money'] / $seeds);
    }
}
