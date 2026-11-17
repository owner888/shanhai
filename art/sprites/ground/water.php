<?php
// Water: a blue in two depths, and short crests of light, wavering, in rows. 32 by 32 pixels, a tile; it tiles.
// Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(32, 32);
for ($y = 0; $y < 32; $y++) {
    for ($x = 0; $x < 32; $x++) {
        $deep = Sprites::noise(intdiv($x, 8), intdiv($y, 8), 'deep') > 0.6;
        $rows[$y][$x] = $deep ? 'B' : 'b';
    }
}
for ($k = 0; $k < 10; $k++) {
    $x = (int)(Sprites::noise($k, 0, 'crest') * 32);
    $y = (int)(Sprites::noise($k, 1, 'crest') * 32);
    $long = 3 + (int)(Sprites::noise($k, 2, 'crest') * 4);
    for ($i = 0; $i < $long; $i++) {
        $rows[$y][($x + $i) % 32] = 'w';
        $rows[($y + 1) % 32][($x + $i + 1) % 32] = 'B';
    }
}

return [
    'size' => [32, 32],
    'colors' => ['b' => '#3a7aa6', 'B' => '#2e6894', 'w' => '#a8d4e8'],
    'shades' => [],
    'outline' => null,
    'views' => ['down' => [$rows]],
];
