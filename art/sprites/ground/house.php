<?php
// A house's wall: rammed earth plastered pale, framed in dark timber, a post at each tile's edge, a beam across, a
// little grime at the foot. 32 by 32 pixels, a tile; it tiles along the wall and up it. Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(32, 32);
for ($y = 0; $y < 32; $y++) {
    for ($x = 0; $x < 32; $x++) {
        $n = Sprites::noise($x, $y, 'plaster');
        $c = $n < 0.1 ? 'P' : ($n > 0.95 ? 'l' : 'p');
        if ($x < 3) {
            $c = $x === 0 ? 'T' : 't'; // the post
        } elseif ($y === 14 || $y === 15) {
            $c = $y === 14 ? 't' : 'T'; // the beam
        } elseif ($x === 3 || $y === 16) {
            $c = 'P'; // the plaster in the timber's shade
        }
        $rows[$y][$x] = $c;
    }
}

return [
    'size' => [32, 32],
    'colors' => ['p' => '#d8c8a8', 'P' => '#bcaa88', 'l' => '#e8dcc0', 't' => '#6a4a32', 'T' => '#4a3222'],
    'shades' => [],
    'outline' => null,
    'views' => ['down' => [$rows]],
];
