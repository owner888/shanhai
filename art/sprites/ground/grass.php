<?php
// The ground of grass: a green of two tones in patches, short dark blades and a few lit ones scattered on it.
// 32 by 32 pixels, a tile; it tiles (Sprites::noise of each pixel's place, which repeats with the tile). Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(32, 32);
for ($y = 0; $y < 32; $y++) {
    for ($x = 0; $x < 32; $x++) {
        // patches: the mean of the scatter around, so that neighbours lean the same way
        $patch = 0.0;
        foreach ([[0, 0], [3, 1], [-2, 3], [1, -3]] as [$dx, $dy]) {
            $patch += Sprites::noise(intdiv(($x + $dx + 32) % 32, 4), intdiv(($y + $dy + 32) % 32, 4), 'patch');
        }
        $rows[$y][$x] = $patch / 4 > 0.55 ? 'G' : 'g';
    }
}
for ($y = 0; $y < 32; $y++) {
    for ($x = 0; $x < 32; $x++) {
        $n = Sprites::noise($x, $y, 'blade');
        if ($n < 0.05) { // a blade: two pixels up
            $rows[$y][$x] = 'd';
            $rows[($y + 31) % 32][$x] = 'd';
        } elseif ($n > 0.975) {
            $rows[$y][$x] = 'l';
        }
    }
}

return [
    'size' => [32, 32],
    'colors' => ['g' => '#5e9a4c', 'G' => '#538e46', 'd' => '#3f7a3e', 'l' => '#8cc062'],
    'shades' => [],
    'outline' => null,
    'views' => ['down' => [$rows]],
];
