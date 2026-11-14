<?php
// Party against party, with switching: php tools/parties.php [level=50] [battles=4000] [size=3]
// Parties are drawn at random from the beasts of tiers 2 and 3. A beast's figure is how often a party with it won.
declare(strict_types=1);

require __DIR__ . '/../src/load.php';

use Shanhai\Battle\Simulator;
use Shanhai\Data;

$level = (int)($argv[1] ?? 50);
$battles = (int)($argv[2] ?? 4000);
$size = (int)($argv[3] ?? 3);
$think = in_array('think', $argv, true); // both sides think ahead: slower, and they use every kind of move
$beasts = Data::beasts();
$elements = Data::elements();
$field = array_values(array_filter(array_keys($beasts), fn(string $id) => in_array($beasts[$id]['tier'], [2, 3], true)));
$rate = (new Simulator($beasts, Data::learnsets(), Data::moves(), $elements))->parties($field, $size, $level, $battles, 1, $think);
$pct = fn(float $x) => str_pad(round($x * 100) . '%', 4, ' ', STR_PAD_LEFT);

printf("%slevel %d, %d battles of parties of %d, tiers 2 and 3\n\nhow often a party with the beast in it won\n", $think ? 'thinking ahead; ' : '', $level, $battles, $size);
arsort($rate);
foreach ($rate as $id => $r) {
    printf("  %s  tier %d  %-11s %s%s\n", $pct($r), $beasts[$id]['tier'], implode('/', $beasts[$id]['elements']), $beasts[$id]['name'], ($beasts[$id]['starter'] ?? false) ? '  (to choose from)' : '');
}
echo "\nby main element, each tier's beasts weighed alike\n";
foreach (array_keys($elements) as $e) {
    $by = [];
    foreach ([2, 3] as $t) {
        $in = array_filter($rate, fn(string $id) => $beasts[$id]['elements'][0] === $e && $beasts[$id]['tier'] === $t, ARRAY_FILTER_USE_KEY);
        if ($in !== []) {
            $by[] = array_sum($in) / count($in);
        }
    }
    printf("  %s  %s\n", $elements[$e]['name'], $pct(array_sum($by) / count($by)));
}
printf("\nspread: the best %s, the worst %s\n", trim($pct(max($rate))), trim($pct(min($rate))));
