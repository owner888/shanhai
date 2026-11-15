<?php
// A game played through by a player with no hands (src/Journey.php): php tools/journey.php [seeds=5]
// For each of the three beasts to choose from and each seed: at what level each trial was passed, after how many
// tries, wild battles and battles lost, and how long it took at 60 updates a second.
declare(strict_types=1);

require __DIR__ . '/../src/load.php';

use Shanhai\Data;
use Shanhai\Journey;

$seeds = (int)($argv[1] ?? 5);
$beasts = Data::beasts();
$starters = array_keys(array_filter($beasts, fn(array $b) => $b['starter'] ?? false));
foreach ([0, 1, 2] as $starter) {
    $sum = [];
    for ($seed = 1; $seed <= $seeds; $seed++) {
        $j = new Journey(Data::newWorld($seed), Data::maps(), Data::moves(), Data::gods(), $starter);
        $j->play();
        $name = $beasts[$j->w->party[0]->id]['name'];
        foreach ($j->passed as $p) {
            foreach (['tries', 'level', 'wild', 'lost', 'updates'] as $k) {
                $sum[$p['range']][$k] = ($sum[$p['range']][$k] ?? 0) + $p[$k];
            }
            $sum[$p['range']]['who'] = $p['who'];
        }
    }
    printf("%s alone, the mean of %d games (wild battles, battles lost and time are counted from the start)\n", $name, $seeds);
    foreach ($sum as $range => $s) {
        printf("  range %d %s: passed at level %.1f on try %.1f, after %.0f wild battles, %.1f lost, %.0f minutes\n",
            $range, $s['who'], $s['level'] / $seeds, $s['tries'] / $seeds, $s['wild'] / $seeds, $s['lost'] / $seeds, $s['updates'] / $seeds / 3600);
    }
}
