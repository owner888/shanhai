<?php
// A wall of stone blocks: rows eight pixels high, blocks sixteen wide, each row's joints half a block along from the
// last's, mortar between, each block lit along its top and shaded along its bottom. 32 by 32 pixels; it tiles, up the
// wall's sides as on its top. Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(32, 32);
for ($y = 0; $y < 32; $y++) {
    $course = intdiv($y, 8);
    for ($x = 0; $x < 32; $x++) {
        $along = ($x + ($course % 2) * 8) % 32;
        $block = intdiv($along, 16);
        $c = Sprites::noise($course, $block, 'block') > 0.5 ? 's' : 'S';
        if ($y % 8 === 7 || $along % 16 === 15) {
            $c = 'm';
        } elseif ($y % 8 === 0) {
            $c = 'l';
        } elseif ($y % 8 === 6) {
            $c = 'd';
        } elseif (Sprites::noise($x, $y, 'pit') < 0.04) {
            $c = 'd';
        }
        $rows[$y][$x] = $c;
    }
}

return [
    'size' => [32, 32],
    'colors' => ['s' => '#d8d2c2', 'S' => '#ccc4b2', 'l' => '#ece6d8', 'd' => '#aea694', 'm' => '#8a8274'],
    'shades' => [],
    'outline' => null,
    'views' => ['down' => [$rows]],
];
