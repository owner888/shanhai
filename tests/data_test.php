<?php
// The data files, checked as written: maps, their scripts, the elements, the beasts.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\{Data, Text};
use Shanhai\Render\Stage;
use Shanhai\World\Tile;
use Shanhai\Script\Vm;
use Shanhai\World\Dir;

$maps = Data::maps();
$elements = Data::elements();
$beasts = Data::beasts();

$scripts = [];
foreach ($maps as $map) {
    foreach ($map->warps as [$x, $y, $to, $tx, $ty, $dir]) {
        check($map->walkable($x, $y), "{$map->id}: the warp at $x,$y is on a tile that can be walked onto");
        check(isset($maps[$to]) && $maps[$to]->walkable($tx, $ty), "{$map->id}: the warp at $x,$y leads to a walkable tile of a map");
        check(Dir::tryFrom($dir) !== null, "{$map->id}: the warp at $x,$y faces a direction");
        check(isset($maps[$to]) && $maps[$to]->warpAt($tx, $ty) === null, "{$map->id}: the warp at $x,$y does not land on another warp");
    }
    $taken = [];
    foreach ($map->npcs as [$id, $x, $y, $dir, $look, $script]) {
        check($map->walkable($x, $y), "{$map->id}: $id stands on a walkable tile");
        check(Dir::tryFrom($dir) !== null, "{$map->id}: $id faces a direction");
        check(isset(Stage::LOOKS[$look]), "{$map->id}: $id has a look the stage draws: $look");
        check(!isset($taken["$x,$y"]), "{$map->id}: $id has the tile to itself");
        $taken["$x,$y"] = true;
        $scripts["{$map->id}: $id"] = $script;
    }
    foreach ($map->signs as [$x, $y, $script]) {
        check($map->walkable($x, $y) && !isset($taken["$x,$y"]), "{$map->id}: the sign at $x,$y stands on a free walkable tile");
        $taken["$x,$y"] = true;
        $scripts["{$map->id}: the sign at $x,$y"] = $script;
    }
    foreach ($map->triggers as [$x, $y, $flag, $script]) {
        check($map->walkable($x, $y) && !isset($taken["$x,$y"]), "{$map->id}: the trigger at $x,$y can be stepped on");
        check($flag !== '', "{$map->id}: the trigger at $x,$y has a flag");
        $scripts["{$map->id}: the trigger at $x,$y"] = $script;
    }
}
foreach ($scripts as $where => $script) {
    check(!throws(fn() => Vm::check($script)), "$where: its script is well formed");
    foreach ($script as $cmd) {
        if ($cmd[0] === 'say') {
            check(count(Text::wrap($cmd[1], Text::columns())) <= Text::LINES, "$where: the line fits the dialog box: {$cmd[1]}");
        }
        if ($cmd[0] === 'warp') {
            check(isset($maps[$cmd[1]]) && $maps[$cmd[1]]->walkable($cmd[2], $cmd[3]), "$where: its warp leads to a walkable tile of a map");
        }
        if ($cmd[0] === 'giveBeast') {
            check(isset($beasts[$cmd[1]]), "$where: it gives a beast there is");
        }
    }
}
$start = (require Data::DIR . '/game.php')['start'];
check(isset($maps[$start['map']]) && $maps[$start['map']]->walkable($start['x'], $start['y']), 'a new game starts on a walkable tile');

foreach (Tile::KINDS as [$kind]) {
    check(isset(Stage::GROUND[$kind]), "the stage has a ground color for $kind");
}
check(isset(Stage::LOOKS['player']), 'the stage draws the player');

same(9, count($elements), 'nine elements');
foreach ($elements as $id => $e) {
    same(2, count($e['strong']), "$id is strong against two");
    foreach ($e['strong'] as $other) {
        check(isset($elements[$other]) && $other !== $id, "$id is strong against an element there is, not itself: $other");
    }
}

same(10, count($beasts), 'the ten beasts of the first range');
same(3, count(array_filter($beasts, fn(array $b) => $b['starter'] ?? false)), 'three to choose from at the start');
$starters = array_keys(array_filter($beasts, fn(array $b) => $b['starter'] ?? false));
foreach ($starters as $a) {
    $beats = array_filter($starters, fn(string $b) => in_array($beasts[$b]['element'], $elements[$beasts[$a]['element']]['strong'], true));
    same(1, count($beats), "$a is strong against one of the other two");
}
foreach ($beasts as $id => $b) {
    check(isset($elements[$b['element']]), "$id has an element there is");
    check($b['name'] !== '' && $b['mountain'] !== '' && $b['looks'] !== '', "$id has a name, a mountain and its looks");
    check($b['lore'] !== '' || isset($b['unchecked']), "$id has its lore, or says what is still to be checked");
    same(6, count($b['stats']), "$id has six stats");
    check(min($b['stats']) >= 1 && max($b['stats']) <= 255, "$id's stats are within 1 to 255");
}

same(['你好，', '世界'], Text::wrap('你好，世界', 6), 'a CJK character takes two columns');
same(['abc', 'def'], Text::wrap("abc\ndef", 10), 'a newline breaks');
