<?php
// The ground of a path: trodden earth, in patches of two tones, with pebbles lit on their top left. 32 by 32 pixels,
// a tile; it tiles. Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(32, 32);
for ($y = 0; $y < 32; $y++) {
    for ($x = 0; $x < 32; $x++) {
        $n = Sprites::noise(intdiv($x, 3), intdiv($y, 2), 'earth');
        $rows[$y][$x] = $n > 0.72 ? 'E' : ($n < 0.12 ? 'D' : 'e');
    }
}
for ($k = 0; $k < 9; $k++) {
    $x = (int)(Sprites::noise($k, 0, 'pebble') * 32);
    $y = (int)(Sprites::noise($k, 1, 'pebble') * 32);
    $rows[$y][$x] = 'l';
    $rows[$y][($x + 1) % 32] = 'p';
    $rows[($y + 1) % 32][$x] = 'p';
    $rows[($y + 1) % 32][($x + 1) % 32] = 'D';
}

return [
    'size' => [32, 32],
    'colors' => ['e' => '#b89262', 'E' => '#c6a172', 'D' => '#9a7650', 'p' => '#a8a090', 'l' => '#d8d0bc'],
    'shades' => [],
    'outline' => null,
    'views' => ['down' => [$rows]],
];
