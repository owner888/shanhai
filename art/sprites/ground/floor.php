<?php
// A wooden floor: planks across, eight pixels wide, a dark seam between them, their ends staggered, a grain along each.
// 32 by 32 pixels, a tile; it tiles. Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(32, 32);
for ($y = 0; $y < 32; $y++) {
    $plank = intdiv($y, 8);
    $end = [5, 21, 13, 29][$plank];
    for ($x = 0; $x < 32; $x++) {
        $tone = Sprites::noise($plank, intdiv(($x - $end + 32) % 32, 16), 'plank') > 0.5 ? 'w' : 'W';
        $c = $tone;
        if ($y % 8 === 7 || $x === $end) {
            $c = 'k';
        } elseif ($y % 8 === 0) {
            $c = 'l';
        } elseif (Sprites::noise(intdiv($x, 5), $y, 'grain') < 0.12) {
            $c = 'g';
        }
        $rows[$y][$x] = $c;
    }
}

return [
    'size' => [32, 32],
    'colors' => ['w' => '#a0744a', 'W' => '#946a42', 'g' => '#82603c', 'l' => '#b88a5a', 'k' => '#5a3e26'],
    'shades' => [],
    'outline' => null,
    'views' => ['down' => [$rows]],
];
