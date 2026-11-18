<?php
// A thatched roof: straw laid in courses down the slope, each course's lower edge in shade, the straws' ends showing
// as short strokes. 32 by 32 pixels, a tile; it tiles along the roof and down it. Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(32, 32);
for ($y = 0; $y < 32; $y++) {
    for ($x = 0; $x < 32; $x++) {
        $course = $y % 8;
        $n = Sprites::noise($x, $y, 'straw');
        $c = $n < 0.5 ? 'a' : 'b';
        if ($course === 7) {
            $c = 'd'; // the course's edge, in shade
        } elseif ($course === 6 && Sprites::noise($x, intdiv($y, 8), 'end') < 0.5) {
            $c = 'd';
        } elseif (Sprites::noise($x, intdiv($y, 3), 'stroke') < 0.18) {
            $c = 'c'; // a straw in shade, down the slope
        } elseif ($n > 0.93) {
            $c = 'l';
        }
        $rows[$y][$x] = $c;
    }
}

return [
    'size' => [32, 32],
    'colors' => ['a' => '#a88a52', 'b' => '#b49660', 'c' => '#8a6e40', 'd' => '#5e4a2c', 'l' => '#d0b67a'],
    'shades' => [],
    'outline' => null,
    'views' => ['down' => [$rows]],
];
