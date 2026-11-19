<?php
// 鯥, of 柢山, drawn from its entry's looks: an ox, of the hills; a snake's tail; wings under its ribs. A heavy body on
// short legs, horns, a wing folded up from its side, a long scaled tail. Seen from the side, facing left (Sprites).
// 60 by 46 pixels.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(60, 46);
Sprites::limb($rows, [46, 26], [58, 32], 3.0, 1.0, 'xyz');      // the snake's tail
Sprites::limb($rows, [40, 30], [41, 44], 2.8, 2.6, 'aabb');     // the far legs
Sprites::limb($rows, [18, 30], [17, 44], 2.8, 2.6, 'aabb');
Sprites::blob($rows, 30, 24, 18, 10, 'abcd');                   // the body
Sprites::blob($rows, 11, 22, 8, 7, 'abcd');                     // the head, low
Sprites::blob($rows, 6, 26, 4, 3, 'bcd');                       // the muzzle
Sprites::limb($rows, [10, 15], [5, 8], 1.6, 0.8, 'hhij');       // the horns
Sprites::limb($rows, [14, 15], [16, 7], 1.6, 0.8, 'hhij');
Sprites::limb($rows, [36, 31], [37, 45], 3.0, 2.8, 'bccd');     // the near legs
Sprites::limb($rows, [22, 31], [21, 45], 3.0, 2.8, 'bccd');
// the wing, folded up from under its ribs: feathers fanning back
foreach ([[26, 22, 44, 6], [28, 23, 48, 10], [30, 24, 50, 15]] as [$x0, $y0, $x1, $y1]) {
    Sprites::limb($rows, [$x0, $y0], [$x1, $y1], 3.0, 1.4, 'uvw');
}
Sprites::dots($rows, [[8, 20]], 'e');
Sprites::dots($rows, [[16, 45], [17, 45], [20, 45], [21, 45], [22, 45], [36, 45], [37, 45], [38, 45]], 'k');

return [
    'looks' => '形如牛，居于山陵，蛇尾，胁下生翼。',
    'size' => [60, 46],
    'colors' => [
        'a' => '#3a2a1c', 'b' => '#5e4430', 'c' => '#866244', 'd' => '#a8845c', // the hide
        'x' => '#3e4a2e', 'y' => '#5e6e40', 'z' => '#80925a',                     // the snake's tail
        'u' => '#8c8070', 'v' => '#b4aa96', 'w' => '#dcd4c0',                     // the wing
        'h' => '#6a6052', 'i' => '#a8a090', 'j' => '#d8d0c0',                     // the horns
        'e' => '#16100a', 'k' => '#241a12',
    ],
    'shades' => [],
    'outline' => '#16100a',
    'views' => ['left' => [$rows]],
];
