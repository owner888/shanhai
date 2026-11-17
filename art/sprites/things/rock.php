<?php
// A rock: two lumps of stone, shaded as balls lit from the top left (Sprites::blob), on a flat bottom. 32 by 26 pixels:
// a tile wide. Never turns.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(32, 26);
Sprites::blob($rows, 12, 16, 10, 9, 'abcd');
Sprites::blob($rows, 20, 13, 10, 12, 'abcd');
// a crack, and the flat bottom it sits on
foreach ([[18, 9], [19, 10], [19, 11], [20, 12], [20, 13]] as [$x, $y]) {
    $rows[$y][$x] = 'a';
}
$rows[25] = str_repeat('.', 32);

return [
    'size' => [32, 26],
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
