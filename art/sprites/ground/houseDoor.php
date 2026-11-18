<?php
// A house's wall with its door: the wall as house.php, and at its foot a door of two leaves under a lintel, a step
// before it. 32 by 64 pixels, drawn once over the whole face of the door's tile (Stage::byTexture), the wall's height,
// so that the door stands a head taller than who goes through it. Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$wall = require __DIR__ . '/house.php';
$rows = [...$wall['views']['down'][0], ...$wall['views']['down'][0]];
// the door: from row 11 to the foot, between the posts, the lintel over it
for ($y = 8; $y < 64; $y++) {
    for ($x = 6; $x < 28; $x++) {
        if ($y < 11) {
            $rows[$y][$x] = $y === 10 ? 'T' : 't'; // the lintel
        } elseif ($x === 6 || $x === 27) {
            $rows[$y][$x] = 'T'; // the frame
        } elseif ($y >= 61) {
            $rows[$y][$x] = $y === 61 ? 's' : 'S'; // the step
        } elseif ($x === 16 || $x === 17) {
            $rows[$y][$x] = 'k'; // between the leaves
        } elseif (($x - 7) % 5 === 4) {
            $rows[$y][$x] = 'w'; // the planks' seams
        } elseif (($y === 38 || $y === 39) && ($x === 14 || $x === 19)) {
            $rows[$y][$x] = 'i'; // the rings
        } else {
            $rows[$y][$x] = 'd';
        }
    }
}

return [
    'size' => [32, 64],
    'colors' => $wall['colors'] + ['d' => '#5a3c28', 'w' => '#3e2a1c', 'k' => '#1e140e', 'i' => '#a89a70', 's' => '#9a968c', 'S' => '#6e6a64'],
    'shades' => [],
    'outline' => null,
    'views' => ['down' => [$rows]],
];
