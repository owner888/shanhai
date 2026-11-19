<?php
// 九尾狐, of 青丘之山, drawn from its entry's looks: a fox with nine tails. A slender fox, red-gold, its nine tails
// fanned up behind it, white at their tips. Seen from the side, facing left (Sprites). 60 by 44 pixels.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(60, 44);
// the nine tails, fanned, each white at its tip
foreach (range(0, 8) as $i) {
    $a = M_PI * (0.9 + $i * 0.085);
    [$tx, $ty] = [36 - 22 * cos($a), 24 - 20 * sin($a) - 2];
    Sprites::limb($rows, [36, 24], [$tx, $ty], 2.6, 2.4, 'abcd');
    Sprites::blob($rows, $tx, $ty, 2.6, 2.6, 'wwvv');
}
Sprites::limb($rows, [30, 30], [31, 42], 1.6, 1.4, 'aabb');     // the far legs
Sprites::limb($rows, [16, 30], [14, 42], 1.6, 1.4, 'aabb');
Sprites::blob($rows, 24, 26, 13, 6.5, 'abcd');                  // the body
Sprites::blob($rows, 23, 30, 9, 2.5, 'vvww');                   // the white of its belly
Sprites::limb($rows, [14, 24], [9, 17], 4.0, 3.4, 'abcd');      // the neck
Sprites::blob($rows, 7, 15, 5.5, 4.5, 'abcd');                  // the head
Sprites::limb($rows, [4, 16], [0, 18], 2.2, 1.0, 'bcd');        // the snout
Sprites::limb($rows, [8, 11], [7, 5], 1.8, 0.6, 'abc');         // the ears
Sprites::limb($rows, [10, 11], [12, 5], 1.8, 0.6, 'abc');
Sprites::limb($rows, [27, 31], [28, 43], 1.8, 1.6, 'bccd');     // the near legs
Sprites::limb($rows, [19, 31], [18, 43], 1.8, 1.6, 'bccd');
Sprites::dots($rows, [[5, 14]], 'e');
Sprites::dots($rows, [[0, 18]], 'k');

return [
    'looks' => '形如狐而九尾。',
    'size' => [60, 44],
    'colors' => [
        'a' => '#6e2e14', 'b' => '#a64c1e', 'c' => '#d0742c', 'd' => '#eea050', // the fur
        'v' => '#d8cfc0', 'w' => '#faf6ee',                                       // white
        'e' => '#1a0e08', 'k' => '#1a0e08',
    ],
    'shades' => [],
    'outline' => '#1a0e08',
    'views' => ['left' => [$rows]],
];
