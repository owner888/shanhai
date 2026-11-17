<?php
// The ground of scree: loose stones packed together, each a cell of the stones' centres (the nearest one, across the
// tile's edges, so that it tiles), dark between them and lit on their top left. 32 by 32 pixels. Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$stones = array_map(fn(int $k) => [Sprites::noise($k, 0, 'stone') * 32, Sprites::noise($k, 1, 'stone') * 32, Sprites::noise($k, 2, 'stone') > 0.5 ? 's' : 'S'], range(0, 13));
/** The nearest stone to a place and how much farther the next is, across the tile's edges. */
$near = function (float $x, float $y) use ($stones): array {
    $d = [];
    foreach ($stones as $i => [$sx, $sy]) {
        $dx = abs($x - $sx); $dy = abs($y - $sy);
        $d[$i] = hypot(min($dx, 32 - $dx), min($dy, 32 - $dy));
    }
    asort($d);
    [$a, $b] = array_slice($d, 0, 2, true) + [null, null];
    $keys = array_keys(array_slice($d, 0, 2, true));
    return [$keys[0], $d[$keys[1]] - $d[$keys[0]]];
};
$rows = Sprites::blank(32, 32);
for ($y = 0; $y < 32; $y++) {
    for ($x = 0; $x < 32; $x++) {
        [$i, $gap] = $near($x + 0.5, $y + 0.5);
        if ($gap < 1.2) {
            $rows[$y][$x] = 'k';
            continue;
        }
        // lit where the stone ends up and to the left, shaded where it ends down and to the right
        [$up] = $near($x - 0.5, $y - 0.5);
        [$down] = $near($x + 1.5, $y + 1.5);
        $rows[$y][$x] = $up !== $i ? 'l' : ($down !== $i ? 'd' : $stones[$i][2]);
    }
}

return [
    'size' => [32, 32],
    'colors' => ['s' => '#9a968c', 'S' => '#8a867e', 'l' => '#c4beb0', 'd' => '#6a6660', 'k' => '#4a4644'],
    'shades' => [],
    'outline' => null,
    'views' => ['down' => [$rows]],
];
