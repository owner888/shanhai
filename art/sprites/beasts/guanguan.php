<?php
// 灌灌, of 青丘之山, drawn from its entry's looks: a bird as a turtledove (鸠). Plump and soft-gray, a speckled collar,
// a small head, standing. Seen from the side, facing left (Sprites). 36 by 32 pixels.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(36, 32);
Sprites::limb($rows, [24, 18], [34, 22], 3.0, 1.6, 'abcd');     // the tail
Sprites::limb($rows, [17, 24], [17, 30], 0.9, 0.8, 'hh');       // the feet
Sprites::limb($rows, [21, 24], [21, 30], 0.9, 0.8, 'hh');
Sprites::blob($rows, 18, 18, 10, 7, 'abcd');                    // the body
Sprites::blob($rows, 20, 17, 7, 4.5, 'uvw');                    // the folded wing
Sprites::pattern($rows, 'uvw', 'k', fn(int $x, int $y) => ($x + 2 * $y) % 5 === 0); // the wing's spots
Sprites::blob($rows, 9, 10, 5, 4.5, 'abcd');                    // the head
Sprites::pattern($rows, 'abcd', 'k', fn(int $x, int $y) => $y >= 13 && $y <= 15 && $x >= 9 && $x <= 13 && ($x + $y) % 2 === 0); // the collar
Sprites::dots($rows, [[3, 10], [4, 10], [4, 11]], 'h');         // the beak
Sprites::dots($rows, [[7, 9]], 'e');
Sprites::dots($rows, [[16, 31], [17, 31], [20, 31], [21, 31]], 'h');

return [
    'looks' => '形如鸠。',
    'size' => [36, 32],
    'colors' => [
        'a' => '#6a6268', 'b' => '#8e868c', 'c' => '#b2aaae', 'd' => '#d4ccce', // the plumage
        'u' => '#7a6a5e', 'v' => '#a08c7c', 'w' => '#c2ae9c',                     // the wing
        'k' => '#3a3034', 'h' => '#b06a5a', 'e' => '#1a1416',
    ],
    'shades' => [],
    'outline' => '#1a1416',
    'views' => ['left' => [$rows]],
];
