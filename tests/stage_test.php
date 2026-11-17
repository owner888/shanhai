<?php
// The faces a map's Stage draws its ground and walls with (Stage::faces): only those that can be seen. Needs no window.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\Data;
use Shanhai\Render\Stage;
use Shanhai\World\{Map, Tile};

$map = fn(array $rows) => Map::fromArray(['id' => 'm', 'name' => 'M', 'rows' => $rows]);
$tops = fn(array $faces) => array_values(array_filter($faces, fn(array $f) => $f[1] === [0.0, 1.0, 0.0]));
$sides = fn(array $faces) => array_values(array_filter($faces, fn(array $f) => $f[1] !== [0.0, 1.0, 0.0]));
/** A side's [lowest y, highest y]. */
$span = fn(array $f) => [min(array_column($f[0], 1)), max(array_column($f[0], 1))];

// Flat ground: a top a tile, and sides only at the map's edge, down to the ground's bottom.
$faces = Stage::faces($map(['...', '...', '...']), Stage::ground(...));
same([9, 12], [count($tops($faces)), count($sides($faces))], 'three by three of ground: nine tops, the twelve sides of its edge, none between two tiles');
same([[Stage::GROUND_BOTTOM, Stage::GROUND_TOP]], array_values(array_unique(array_map($span, $sides($faces)), SORT_REGULAR)), 'the edge\'s sides go from the ground\'s bottom to its top');

// Water lies lower: the ground around it shows a bank, from the water's top up; the water shows no side but at the edge.
$faces = Stage::faces($map(['...', '.~.', '...']), Stage::ground(...));
$banks = array_values(array_filter($sides($faces), fn(array $f) => $span($f) === [Stage::WATER_TOP, Stage::GROUND_TOP]));
same([9, 16, 4], [count($tops($faces)), count($sides($faces)), count($banks)], 'a tile of water in the middle: four banks more');
$normals = array_column($banks, 1);
sort($normals);
same([[-1.0, 0.0, 0.0], [0.0, 0.0, -1.0], [0.0, 0.0, 1.0], [1.0, 0.0, 0.0]], $normals, 'each facing the water');
$faces = Stage::faces($map(['~.']), Stage::ground(...));
check(in_array([Stage::WATER_BOTTOM, Stage::WATER_TOP], array_map($span, $sides($faces)), true), 'at the edge the water shows its own depth');

// Walls: a block on the ground, with no face between two of them; nothing for another tile.
same([1, 4], [count($tops($w = Stage::faces($map(['...', '.H.', '...']), Stage::wall(...)))), count($sides($w))], 'a wall alone: a top and four sides');
same([[Stage::GROUND_TOP, Stage::WALL_TOP]], array_values(array_unique(array_map($span, $sides($w)), SORT_REGULAR)), 'from the ground up');
same([2, 6], [count($tops($w = Stage::faces($map(['HH']), Stage::wall(...)))), count($sides($w))], 'two walls side by side: two tops, six sides');
same([], Stage::faces($map(['...']), Stage::wall(...)), 'no wall, no face');

// Every map of the game: each face is wound counterclockwise seen from outside, and no side lies against the block beside it.
foreach (Data::maps() as $m) {
    foreach (['ground' => Stage::ground(...), 'wall' => Stage::wall(...)] as $what => $block) {
        $bad = $hidden = 0;
        foreach (Stage::faces($m, $block) as [$c, $n]) {
            [$u, $v] = [[$c[1][0] - $c[0][0], $c[1][1] - $c[0][1], $c[1][2] - $c[0][2]], [$c[2][0] - $c[0][0], $c[2][1] - $c[0][1], $c[2][2] - $c[0][2]]];
            $cross = [$u[1] * $v[2] - $u[2] * $v[1], $u[2] * $v[0] - $u[0] * $v[2], $u[0] * $v[1] - $u[1] * $v[0]];
            $bad += $cross[0] * $n[0] + $cross[1] * $n[1] + $cross[2] * $n[2] > 0 ? 0 : 1;
            if ($n[1] === 0.0) {
                // the tile the side belongs to is half a tile behind it; the one beside it half a tile before
                $center = [array_sum(array_column($c, 0)) / 4, array_sum(array_column($c, 2)) / 4];
                [$bx, $by] = [(int)round($center[0] + $n[0] / 2), (int)round($center[1] + $n[2] / 2)];
                $inside = $bx >= 0 && $by >= 0 && $bx < $m->width && $by < $m->height;
                $beside = $inside ? $block(Tile::name($m->tile($bx, $by)), $bx, $by) : null;
                $hidden += $beside !== null && min(array_column($c, 1)) < $beside[0] ? 1 : 0;
            }
        }
        same([0, 0], [$bad, $hidden], "{$m->id}: its $what's faces all face out, and none is hidden against the block beside it");
    }
}
