<?php
// The game played through from its start by a player with no hands (src/Journey.php), with each of the three beasts
// to choose from: every trial there is can be passed, and every map come to. What a map, a script or a table breaks
// on the way throws here.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\Data;
use Shanhai\Journey;

$ranges = array_keys(Data::ranges());
$maps = Data::maps();
foreach ([0, 1, 2] as $starter) {
    $j = new Journey(Data::newWorld(1), $maps, Data::moves(), Data::gods(), $starter);
    $j->play();
    $who = $j->w->party[0]->species['name'];
    same($ranges, array_column($j->passed, 'range'), "with $who the trial of each range is passed, in the ranges' order");
    $unreached = array_values(array_filter(array_keys($maps), fn(string $id) => !$j->reach($id)));
    same([], $unreached, "and then every map can be come to");
}
