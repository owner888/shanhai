<?php
// A tree, as the maps stand them: a trunk and a crown of leafy lumps, each lump shaded as a ball lit from the top
// left (Sprites::blob), the back ones painted first. 48 by 72 pixels: a tile and a half wide, its crown over the tiles
// around. Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(48, 72);
// the trunk, lighter on its left, and its roots
foreach (range(46, 71) as $y) {
    $half = $y > 66 ? 4 + ($y - 66) : 3;
    for ($x = 24 - $half; $x < 24 + $half; $x++) {
        $rows[$y][$x] = $x < 22 ? 'u' : ($x < 25 ? 't' : 'T');
    }
}
// a branch or two, seen between the lumps
foreach ([[18, 44], [19, 43], [29, 42], [30, 41]] as [$x, $y]) {
    $rows[$y][$x] = 'T';
}
// the crown, back to front
foreach ([
    [24, 13, 11, 10], [13, 22, 9, 9], [35, 22, 9, 9],
    [24, 24, 13, 11], [10, 34, 8, 8], [38, 34, 8, 8],
    [18, 38, 11, 9], [31, 38, 11, 9], [24, 45, 9, 6],
] as [$cx, $cy, $rx, $ry]) {
    Sprites::blob($rows, $cx, $cy, $rx, $ry, 'abcd');
}

return [
    'size' => [48, 72],
    'colors' => [
        'a' => '#1f4a36', // the leaves in the deepest shade
        'b' => '#2f6e3e',
        'c' => '#4f9a48',
        'd' => '#8cc45a', // where the light falls on them
        'u' => '#8a6444', // the trunk, lit
        't' => '#6b4a32',
        'T' => '#4a3222', // the trunk in shade
    ],
    'shades' => [],
    'outline' => '#16261c',
    'views' => ['down' => [$rows]],
];
