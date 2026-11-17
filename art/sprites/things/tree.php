<?php
// A tree, as the maps stand them: a trunk and a tall crown of leafy lumps, each lump shaded as a ball lit from the top
// left (Sprites::blob), the back ones painted first. 64 by 96 pixels: two tiles wide and three high, its crown over the
// tiles around, a tree standing well over who walks under it. Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(64, 96);
// the trunk, lighter on its left, and its roots
foreach (range(60, 95) as $y) {
    $half = $y > 89 ? 4 + ($y - 89) : 4;
    for ($x = 32 - $half; $x < 32 + $half; $x++) {
        $rows[$y][$x] = $x < 30 ? 'u' : ($x < 33 ? 't' : 'T');
    }
}
// branches, seen between the lumps
foreach ([[24, 60], [25, 59], [26, 58], [39, 57], [40, 56], [41, 55]] as [$x, $y]) {
    $rows[$y][$x] = 'T';
}
// the crown, back to front
foreach ([
    [32, 14, 13, 13], [18, 26, 11, 11], [46, 26, 11, 11],
    [32, 32, 16, 14], [12, 42, 10, 10], [52, 42, 10, 10],
    [24, 48, 14, 11], [41, 48, 14, 11], [32, 58, 12, 8],
] as [$cx, $cy, $rx, $ry]) {
    Sprites::blob($rows, $cx, $cy, $rx, $ry, 'abcd');
}

return [
    'size' => [64, 96],
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
