<?php
// Tall grass, where wild beasts hide: blades leaning this way and that, dark at the root and light at the tip, across
// the tile. 32 by 16 pixels: a tile wide, and stood a little before the tile's middle, so that who walks in it is
// hidden to the knees. The blades are placed by Sprites::noise, the same each time. Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(32, 16);
for ($k = 0; $k < 22; $k++) {
    $root = 1 + (int)(Sprites::noise($k, 0, 'grass') * 30);
    $tall = 8 + (int)(Sprites::noise($k, 1, 'grass') * 8);
    $lean = (Sprites::noise($k, 2, 'grass') - 0.5) * 0.9;
    for ($i = 0; $i < $tall; $i++) {
        $x = (int)round($root + $lean * $i * $i / $tall);
        if ($x < 0 || $x > 31) {
            break;
        }
        $up = $i / $tall;
        $rows[15 - $i][$x] = $up > 0.75 ? 'd' : ($up > 0.4 ? 'c' : ($up > 0.15 ? 'b' : 'a'));
    }
}

return [
    'size' => [32, 16],
    'colors' => [
        'a' => '#1e4430', // at the root
        'b' => '#2f6a3a',
        'c' => '#4c9446',
        'd' => '#8cc85a', // at the tip
    ],
    'shades' => [],
    'outline' => '#16301e',
    'views' => ['down' => [$rows]],
];
