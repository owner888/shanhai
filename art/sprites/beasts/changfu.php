<?php
// 尚付 (鵸䳜), of 基山, drawn from its entry's looks: a bird of three heads, six eyes, six feet, three wings. A plump
// body, three necks rising, a pair of eyes to each head, three wings raised, three pairs of feet. Seen from the side,
// facing left (Sprites). 56 by 48 pixels.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(56, 48);
// the three wings, raised back to front
foreach ([[34, 22, 50, 4], [32, 23, 54, 12], [30, 24, 52, 20]] as [$x0, $y0, $x1, $y1]) {
    Sprites::limb($rows, [$x0, $y0], [$x1, $y1], 4.0, 1.6, 'uvwx');
}
Sprites::limb($rows, [46, 30], [54, 34], 3.0, 1.4, 'abcd');     // the tail
// the six feet, three pairs
foreach ([24, 30, 36] as $x) {
    Sprites::limb($rows, [$x + 2, 34], [$x + 2, 46], 1.0, 0.9, 'hh');
    Sprites::limb($rows, [$x, 34], [$x - 1, 46], 1.2, 1.0, 'ij');
}
Sprites::blob($rows, 30, 28, 15, 9, 'abcd');                    // the body
// the three necks and heads, back to front
foreach ([[22, 22, 18, 6], [20, 24, 11, 10], [19, 27, 6, 17]] as [$x0, $y0, $hx, $hy]) {
    Sprites::limb($rows, [$x0, $y0], [$hx + 2, $hy + 2], 2.8, 2.2, 'abcd');
    Sprites::blob($rows, $hx, $hy, 4.5, 4, 'bcd');
    Sprites::dots($rows, [[$hx - 4, $hy + 1], [$hx - 5, $hy + 1], [$hx - 5, $hy + 2]], 'h'); // the beak
    Sprites::dots($rows, [[$hx - 1, $hy - 1], [$hx + 1, $hy - 1]], 'e');                   // two eyes each
}
Sprites::dots($rows, [[22, 47], [23, 47], [28, 47], [29, 47], [34, 47], [35, 47]], 'h');

return [
    'looks' => '三首、六目、六足、三翼的鸟。',
    'size' => [56, 48],
    'colors' => [
        'a' => '#2a2a40', 'b' => '#40426a', 'c' => '#5c6094', 'd' => '#8288c0', // the plumage
        'u' => '#3a2a3e', 'v' => '#5e4064', 'w' => '#865c8e', 'x' => '#b088b4', // the wings
        'h' => '#c89a30', 'i' => '#a87c28', 'j' => '#d8aa48',                     // the beaks and feet
        'e' => '#f0e0a0',
    ],
    'shades' => [],
    'outline' => '#14121e',
    'views' => ['left' => [$rows]],
];
