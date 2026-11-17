<?php
// The game played through from its start by a player with no hands (src/Journey.php), with each of the three beasts
// to choose from: every trial there is can be passed, and every map come to. What a map, a script or a table breaks
// on the way throws here. The player keeps a full party, as a person does: it binds wild beasts with the seals it is
// given and buys, changes its weakest for better ones at the pen, and by the last trial has six.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\Data;
use Shanhai\Journey;

$ranges = array_keys(Data::ranges());
$maps = Data::maps();
foreach ([0, 1, 2] as $starter) {
    $j = new Journey(Data::newWorld(1), $maps, Data::moves(), Data::gods(), $starter);
    $j->play();
    $who = array_values(array_filter(Data::beasts(), fn(array $b) => $b['starter'] ?? false))[$starter]['name'];
    $order = $ranges;
    array_splice($order, 8, 0, [8]); // docs/numbers.md 32: the Lower Capital's five come after the eighth range's trial
    same($order, array_column($j->passed, 'range'), "with $who the trial of each range is passed, in the ranges' order, and the Lower Capital's five after the eighth");
    same('帝之下都', $j->passed[8]['who'], "and the ninth thing passed is the Lower Capital");
    same(\Shanhai\World\World::PARTY, count($j->w->party), "and it has bound a full party on the way");
    same(count($j->w->party), count(array_unique(array_map(fn($f) => $f->id, $j->w->party))), "each of a kind of its own");
    check(end($j->passed)['low'] > 1 && end($j->passed)['high'] > end($j->passed)['low'], "and all of them have grown");
    check($j->swapped > 0 && array_filter([...$j->w->party, ...$j->w->boxes[0]], fn($f) => $f->item === 'tongxiu') !== [], "it has changed beasts for better ones at the pen, and one holds what shares");
    $unreached = array_values(array_filter(array_keys($maps), fn(string $id) => !$j->reach($id)));
    same([], $unreached, "and then every map can be come to");
}
