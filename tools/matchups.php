<?php
// The elements against one another, and the three to choose from against each: php tools/matchups.php [level=50] [rounds=30]
// Counts the beasts of tiers 2 and 3 only, and weighs the five elements alike, so that neither a tier nor how many
// beasts an element has in the data speaks for the element.
declare(strict_types=1);

require __DIR__ . '/../src/load.php';

use Shanhai\Battle\Simulator;
use Shanhai\Data;

$level = (int)($argv[1] ?? 50);
$rounds = (int)($argv[2] ?? 30);
$think = in_array('think', $argv, true); // both sides think ahead: slower, and they use every kind of move
$beasts = Data::beasts();
$elements = Data::elements();
$table = (new Simulator($beasts, Data::learnsets(), Data::moves(), $elements))->table($level, $rounds, $think);
$field = array_filter(array_keys($beasts), fn(string $id) => in_array($beasts[$id]['tier'], [2, 3], true));
$pct = fn(float $x) => str_pad(round($x * 100) . '%', 6, ' ', STR_PAD_LEFT);
$head = fn() => implode('', array_map(fn(array $e) => str_pad($e['name'], 7, ' ', STR_PAD_LEFT), $elements)) . "    even\n";

printf("%slevel %d, %d battles a pair, tiers 2 and 3\n\nmain element against main element (row beats column)\n    %s", $think ? 'thinking ahead; ' : '', $level, $rounds, $head());
foreach (array_keys($elements) as $a) {
    $row = [];
    foreach (array_keys($elements) as $d) {
        $sum = $n = 0;
        foreach ($field as $x) {
            foreach ($field as $y) {
                if ($x !== $y && $beasts[$x]['elements'][0] === $a && $beasts[$y]['elements'][0] === $d) {
                    $sum += $table[$x][$y];
                    $n++;
                }
            }
        }
        $row[] = $n > 0 ? $sum / $n : 0.5;
    }
    echo $elements[$a]['name'], '  ', implode('', array_map($pct, $row)), '  ', $pct(array_sum($row) / count($row)), "\n";
}
echo "\nthe three to choose from, against each main element\n         ", $head();
foreach (array_keys(array_filter($beasts, fn(array $b) => $b['starter'] ?? false)) as $id) {
    $row = [];
    foreach (array_keys($elements) as $e) {
        $in = array_filter($field, fn(string $o) => $o !== $id && $beasts[$o]['elements'][0] === $e);
        $row[] = array_sum(array_map(fn(string $o) => $table[$id][$o], $in)) / count($in);
    }
    echo str_pad($beasts[$id]['name'], 9), implode('', array_map($pct, $row)), '  ', $pct(array_sum($row) / count($row)), "\n";
}
