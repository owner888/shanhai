<?php
// The models of art/ and the faces they are drawn with (Render\Voxels): only those that can be seen, each facing out. Needs no window.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\Data;
use Shanhai\Render\Voxels;

$model = fn(array $boxes, array $parts = ['body' => []], array $colors = ['a' => '#ff0000']) => ['colors' => $colors, 'parts' => $parts, 'boxes' => $boxes];
$count = fn(array $faces) => array_map('count', $faces);
/** How many of the faces are not wound counterclockwise seen from the side their normal points to. */
$inward = function (array $faces): int {
    $bad = 0;
    foreach ($faces as [$c, $n]) {
        [$u, $v] = [[$c[1][0] - $c[0][0], $c[1][1] - $c[0][1], $c[1][2] - $c[0][2]], [$c[2][0] - $c[0][0], $c[2][1] - $c[0][1], $c[2][2] - $c[0][2]]];
        $cross = [$u[1] * $v[2] - $u[2] * $v[1], $u[2] * $v[0] - $u[0] * $v[2], $u[0] * $v[1] - $u[1] * $v[0]];
        $bad += $cross[0] * $n[0] + $cross[1] * $n[1] + $cross[2] * $n[2] > 0 ? 0 : 1;
    }
    return $bad;
};

// A voxel alone: six faces, PER_TILE to a tile, each facing out.
$v = 1.0 / Voxels::PER_TILE;
$one = Voxels::faces($model([['body', 'a', [0, 0, 0], [0, 0, 0]]]));
same(['body' => 6], $count($one), 'a voxel alone: six faces');
same(0, $inward($one['body']), 'each facing out');
same([[0.0, $v, $v], [$v, $v, $v], [$v, $v, 0.0], [0.0, $v, 0.0]], $one['body'][0][0], 'its top, a voxel up');

// Two side by side: no face between them in one part; both faces when they are of two parts, which move apart.
same(['body' => 10], $count(Voxels::faces($model([['body', 'a', [0, 0, 0], [1, 0, 0]]]))), 'two of one part: ten faces');
$two = Voxels::faces($model([['body', 'a', [0, 0, 0], [0, 0, 0]], ['head', 'a', [1, 0, 0], [1, 0, 0]]], ['body' => [], 'head' => ['at' => [1, 0, 0]]]));
same(['body' => 6, 'head' => 6], $count($two), 'two of two parts: six each');
same(0.0, min(array_merge(...array_map(fn(array $f) => array_column($f[0], 0), $two['head']))), 'a part\'s faces are from its pivot');

// A box over another takes its place; '.' takes voxels away; a part left with none is an error.
same(['body' => ['0,0,0' => 'b', '1,0,0' => 'a']], Voxels::voxels($model([['body', 'a', [0, 0, 0], [1, 0, 0]], ['body', 'b', [0, 0, 0], [0, 0, 0]]], colors: ['a' => '#ff0000', 'b' => '#00ff00'])), 'a later box over an earlier');
same(['body' => ['1,0,0' => 'a']], Voxels::voxels($model([['body', 'a', [0, 0, 0], [1, 0, 0]], ['body', '.', [0, 0, 0], [0, 0, 0]]])), '\'.\' takes a voxel away');
check(throws(fn() => Voxels::voxels($model([['body', 'a', [0, 0, 0], [0, 0, 0]], ['body', '.', [0, 0, 0], [0, 0, 0]]]))), 'a part with no voxels throws');

// A ball: as wide as its radii, about its center, round (no corner of its box filled), nothing below the ground.
$ball = Voxels::voxels($model(Voxels::ball('body', 'a', [0, 6, 0], 6, 6, 6)))['body'];
$at = array_map(fn(string $k) => array_map('intval', explode(',', $k)), array_keys($ball));
same([-6, 5, 0, 11, -6, 5], [min(array_column($at, 0)), max(array_column($at, 0)), min(array_column($at, 1)), max(array_column($at, 1)), min(array_column($at, 2)), max(array_column($at, 2))], 'a ball of radius six: twelve across each way');
check(!isset($ball['-6,0,-6']) && !isset($ball['5,11,5']), 'its box\'s corners are empty');
same([], array_filter($at, fn(array $p) => $p[1] < 0), 'a ball partly below the ground is cut at it');
same(0, min(array_column(array_map(fn(string $k) => array_map('intval', explode(',', $k)), array_keys(Voxels::voxels($model(Voxels::ball('body', 'a', [0, 2, 0], 4, 4, 4)))['body'])), 1)), 'and starts at it');

// A color given when drawn: needed, and used.
$robe = $model([['body', 'r', [0, 0, 0], [0, 0, 0]]], colors: ['r' => null]);
check(throws(fn() => Voxels::faces($robe)), 'a color given when drawn and not given throws');
same([0.5, 0.5, 0.5], array_map(fn(float $c) => round($c, 1), array_slice(Voxels::faces($robe, ['r' => [0.5, 0.5, 0.5, 1.0]])['body'][0][2], 0, 3)), 'and is drawn when given');

// The grain: the same each time for a place, never further from the color than GRAIN.
same(Voxels::shade([0.5, 0.5, 0.5, 1.0], 3, 4), Voxels::shade([0.5, 0.5, 0.5, 1.0], 3, 4), 'a place\'s shade is the same each time');
$shades = array_map(fn(int $i) => Voxels::shade([0.5, 0.5, 0.5, 1.0], $i, 0)[0], range(0, 99));
check(min($shades) >= 0.5 * (1 - Voxels::GRAIN) && max($shades) <= 0.5 * (1 + Voxels::GRAIN) && max($shades) > min($shades), 'the grain stays within GRAIN, and varies');

// Every model of art/: read and checked, each part's faces facing out; a thing is one body, for one draw of many;
// a beast's is one of data/beasts.php, drawn from its passage.
$beasts = Data::beasts();
$names = Voxels::names();
check(in_array('people/person', $names, true) && in_array('things/tree', $names, true), 'art/ has the person and the things a map draws');
foreach ($names as $name) {
    $m = Voxels::load($name);
    $faces = Voxels::faces($m, ['r' => [1.0, 1.0, 1.0, 1.0]]);
    same(0, array_sum(array_map($inward, $faces)), "$name: every face facing out");
    [$folder, $id] = explode('/', $name);
    check(in_array($folder, ['beasts', 'people', 'things'], true), "$name: in a folder of art/'s");
    if ($folder === 'things') {
        same(['body'], array_keys($faces), "$name: a thing has no part that moves");
    }
    if ($folder === 'beasts') {
        check(isset($beasts[$id]), "$name: a beast of data/beasts.php");
        same($beasts[$id]['looks'] ?? null, $m['looks'] ?? null, "$name: drawn from the passage the beast's entry has");
    }
}
