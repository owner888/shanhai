<?php
// The data files, checked as written: maps, their scripts, the beasts.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\{Data, Text};
use Shanhai\Render\Stage;
use Shanhai\World\Tile;
use Shanhai\Script\Vm;
use Shanhai\World\Dir;

$maps = Data::maps();
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

same(30, count($beasts), 'the beasts of the Southern Mountains: 12, 9 and 9 over its three ranges');
foreach ([1 => 12, 2 => 9, 3 => 9] as $range => $count) {
    same($count, count(array_filter($beasts, fn(array $b) => $b['range'] === $range)), "range $range");
}
same(['guanguan', 'lushu', 'xuangui'], (function () use ($beasts) {
    $ids = array_keys(array_filter($beasts, fn(array $b) => $b['starter'] ?? false));
    sort($ids);
    return $ids;
})(), 'the three to choose from at the start');
$names = [];
foreach ($beasts as $id => $b) {
    check(preg_match('/^[a-z]+$/', $id) === 1, "$id is a plain id");
    check(!isset($names[$b['name']]), "$id's name is its alone: {$b['name']}");
    $names[$b['name']] = true;
    check(in_array($b['kind'], ['strange', 'common'], true), "$id is strange or common");
    check($b['mountain'] !== '' && $b['looks'] !== '', "$id has a mountain and its looks");
    check(array_diff(array_keys($b), ['name', 'kind', 'range', 'mountain', 'looks', 'note', 'sound', 'omen', 'lore', 'unchecked', 'starter']) === [], "$id has only the fields there are");
    foreach (['looks', 'note', 'sound', 'omen', 'lore', 'unchecked'] as $field) {
        check(($b[$field] ?? 'x') !== '', "$id's $field, when given, says something");
    }
}

same(['你好，', '世界'], Text::wrap('你好，世界', 6), 'a CJK character takes two columns');
same(['abc', 'def'], Text::wrap("abc\ndef", 10), 'a newline breaks');
