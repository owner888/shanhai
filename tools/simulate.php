<?php
// Weighs the tables: every beast against every other, one on one. php tools/simulate.php [level=50] [rounds=40]
declare(strict_types=1);

require __DIR__ . '/../src/load.php';

use Shanhai\Battle\Simulator;
use Shanhai\Data;

$level = (int)($argv[1] ?? 50);
$rounds = (int)($argv[2] ?? 40);
$beasts = Data::beasts();
$elements = Data::elements();
$table = (new Simulator($beasts, Data::learnsets(), Data::moves(), $elements))->table($level, $rounds);
$rate = array_map(fn(array $row) => array_sum($row) / count($row), $table);
$pct = fn(float $x) => str_pad((string)round($x * 100), 3, ' ', STR_PAD_LEFT) . '%';

printf("level %d, %d battles a pair\n\nby beast, the strongest first\n", $level, $rounds);
arsort($rate);
foreach ($rate as $id => $r) {
    $b = $beasts[$id];
    printf("  %s  tier %d  %-11s %s %d\n", $pct($r), $b['tier'], implode('/', $b['elements']), $b['name'], array_sum($b['stats']));
}
echo "\nby tier\n";
foreach ([1, 2, 3, 4, 5] as $t) {
    $in = array_filter($rate, fn(string $id) => $beasts[$id]['tier'] === $t, ARRAY_FILTER_USE_KEY);
    printf("  tier %d  %s  (%s to %s, %d beasts)\n", $t, $pct(array_sum($in) / count($in)), trim($pct(min($in))), trim($pct(max($in))), count($in));
}
echo "\nby main element, within tiers 2 and 3 only, so a tier does not speak for an element\n";
foreach (array_keys($elements) as $e) {
    $in = array_filter($rate, fn(string $id) => $beasts[$id]['elements'][0] === $e && in_array($beasts[$id]['tier'], [2, 3], true), ARRAY_FILTER_USE_KEY);
    printf("  %s  %s  (%d beasts)\n", $elements[$e]['name'], $in === [] ? '  —' : $pct(array_sum($in) / count($in)), count($in));
}
echo "\nthe three to choose from, row against column\n";
$starters = array_keys(array_filter($beasts, fn(array $b) => $b['starter'] ?? false));
foreach ($starters as $a) {
    echo '  ', $beasts[$a]['name'];
    foreach ($starters as $b) {
        echo '  ', $a === $b ? '   —' : $pct($table[$a][$b]);
    }
    echo "\n";
}
