<?php
// The ground of sand: pale, with ripples the wind left and a grain here and there. 32 by 32 pixels, a tile; it tiles.
// Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(32, 32);
for ($y = 0; $y < 32; $y++) {
    for ($x = 0; $x < 32; $x++) {
        // a ripple every eight rows, wavering
        $ripple = ($y + (int)round(2 * sin($x / 32 * 2 * M_PI))) % 8 === 0;
        $n = Sprites::noise($x, $y, 'sand');
        $rows[$y][$x] = $ripple ? 'r' : ($n < 0.04 ? 'r' : ($n > 0.96 ? 'l' : 's'));
    }
}

return [
    'size' => [32, 32],
    'colors' => ['s' => '#dccb9c', 'r' => '#c4b082', 'l' => '#f0e4c0'],
    'shades' => [],
    'outline' => null,
    'views' => ['down' => [$rows]],
];
