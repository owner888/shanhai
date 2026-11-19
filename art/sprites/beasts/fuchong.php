<?php
// 蝮虫, of 猨翼之山, drawn from its entry's looks: a viper, coiled, its head raised to strike; dark bands down its
// back. Seen from the side, facing left (Sprites). 48 by 32 pixels.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(48, 32);
// the coils, back to front, and the neck rising from them
Sprites::blob($rows, 30, 25, 14, 5, 'abcd');
Sprites::blob($rows, 26, 22, 11, 4.5, 'abcd');
Sprites::limb($rows, [20, 21], [12, 9], 3.4, 2.8, 'abcd');
Sprites::blob($rows, 9, 8, 5.5, 3.5, 'abcd');                   // the head, flat and broad
Sprites::pattern($rows, 'bcd', 'k', fn(int $x, int $y) => ($x + $y) % 6 < 2 && $x > 13);
Sprites::dots($rows, [[7, 7]], 'e');
Sprites::dots($rows, [[3, 10], [2, 11], [3, 12]], 't');          // the tongue

return [
    'looks' => '毒蛇一类。',
    'size' => [48, 32],
    'colors' => [
        'a' => '#3a3a1e', 'b' => '#62602e', 'c' => '#8c8640', 'd' => '#b8b060',
        'k' => '#241e12', // the bands
        'e' => '#e0c040', 't' => '#c03028',
    ],
    'shades' => [],
    'outline' => '#16140c',
    'views' => ['left' => [$rows]],
];
