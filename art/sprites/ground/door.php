<?php
// The ground at a door: a threshold of dark planks, upright to the way in, with iron studs. 32 by 32 pixels. Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(32, 32);
for ($y = 0; $y < 32; $y++) {
    for ($x = 0; $x < 32; $x++) {
        $c = Sprites::noise($x, intdiv($y, 4), 'door') < 0.15 ? 'g' : 'w';
        if ($x % 8 === 7) {
            $c = 'k';
        } elseif ($x % 8 === 0) {
            $c = 'l';
        } elseif (($y === 5 || $y === 26) && $x % 8 === 3) {
            $c = 'i';
        }
        $rows[$y][$x] = $c;
    }
}

return [
    'size' => [32, 32],
    'colors' => ['w' => '#5a3c28', 'g' => '#4c3222', 'l' => '#6e4c34', 'k' => '#2e1e14', 'i' => '#9a9aa2'],
    'shades' => [],
    'outline' => null,
    'views' => ['down' => [$rows]],
];
