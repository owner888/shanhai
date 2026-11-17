<?php
// A rock: a stone and a smaller one beside it, shaded as balls lit from the top left (Sprites::blob), on a flat bottom.
// 32 by 18 pixels: a tile wide and knee high to a person. Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(32, 18);
Sprites::blob($rows, 14, 10, 11, 9, 'abcd');
Sprites::blob($rows, 25, 12, 6, 6, 'abcd');
// a crack
foreach ([[12, 5], [13, 6], [13, 7], [14, 8]] as [$x, $y]) {
    $rows[$y][$x] = 'a';
}
$rows[17] = str_repeat('.', 32);

return [
    'size' => [32, 18],
    'colors' => [
        'a' => '#4a4850', // in shade
        'b' => '#6e6c70',
        'c' => '#9a968e',
        'd' => '#c8c2b2', // lit
    ],
    'shades' => [],
    'outline' => '#1e1c22',
    'views' => ['down' => [$rows]],
];
