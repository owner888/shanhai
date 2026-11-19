<?php
// 类, of 亶爰之山, drawn from its entry's looks: a wildcat (狸) with long hair. A cat's body under a long, shaggy coat
// falling in locks, a bushy tail. Seen from the side, facing left (Sprites). 44 by 34 pixels.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(44, 34);
Sprites::limb($rows, [32, 16], [42, 8], 3.6, 2.4, 'abcd');      // the bushy tail
Sprites::limb($rows, [28, 22], [29, 32], 1.8, 1.6, 'aabb');     // the far legs
Sprites::limb($rows, [14, 22], [13, 32], 1.8, 1.6, 'aabb');
Sprites::blob($rows, 22, 19, 12, 7, 'abcd');                    // the body
Sprites::blob($rows, 9, 14, 6.5, 5.5, 'abcd');                  // the head
Sprites::dots($rows, [[6, 7], [6, 8], [7, 8], [11, 7], [11, 8], [12, 8]], 'b'); // the ears
Sprites::limb($rows, [25, 23], [25, 32], 2.0, 1.8, 'bccd');     // the near legs
Sprites::limb($rows, [11, 22], [10, 32], 2.0, 1.8, 'bccd');
// the long hair, falling in locks from its back and belly
Sprites::pattern($rows, 'bcd', 'a', fn(int $x, int $y) => $y > 12 && ($x * 3 + $y) % 7 === 0);
foreach (range(14, 32, 3) as $x) {
    Sprites::limb($rows, [$x, 23], [$x - 1, 27], 1.0, 0.6, 'bc');
}
Sprites::dots($rows, [[6, 13], [4, 16]], 'e');

return [
    'looks' => '形如狸而有长毛。',
    'size' => [44, 34],
    'colors' => [
        'a' => '#4e3820', 'b' => '#7e5c34', 'c' => '#a88048', 'd' => '#d0a86a',
        'e' => '#1a120a',
    ],
    'shades' => [],
    'outline' => '#1a120a',
    'views' => ['left' => [$rows]],
];
