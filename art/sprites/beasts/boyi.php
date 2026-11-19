<?php
// 猼訑, of 基山, drawn from its entry's looks: a sheep, nine tails, four ears, its eye on its back. A woolly body, its
// nine tails fanned behind, two pairs of ears, and an eye open in the wool of its back. Seen from the side, facing
// left (Sprites). 56 by 44 pixels.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(56, 44);
// the nine tails, fanned up and back
foreach (range(0, 8) as $i) {
    $a = M_PI * (0.95 + $i * 0.07);
    Sprites::limb($rows, [38, 20], [38 - 15 * cos($a), 20 - 14 * sin($a) - 4], 2.0, 1.2, 'tuvw');
}
Sprites::limb($rows, [34, 28], [35, 42], 2.2, 2.0, 'aabb');     // the far legs
Sprites::limb($rows, [16, 28], [15, 42], 2.2, 2.0, 'aabb');
Sprites::blob($rows, 25, 22, 15, 9, 'abcd');                    // the woolly body
Sprites::pattern($rows, 'cd', 'b', fn(int $x, int $y) => ($x * 5 + $y * 3) % 11 === 0);
Sprites::blob($rows, 9, 17, 6, 5, 'pqrs');                      // the head
Sprites::limb($rows, [10, 13], [16, 9], 1.4, 0.9, 'pqr');       // the four ears, two and two
Sprites::limb($rows, [9, 13], [14, 7], 1.4, 0.9, 'qrs');
Sprites::limb($rows, [8, 13], [3, 9], 1.4, 0.9, 'pqr');
Sprites::limb($rows, [9, 13], [5, 7], 1.4, 0.9, 'qrs');
Sprites::limb($rows, [30, 29], [31, 43], 2.4, 2.2, 'bccd');     // the near legs
Sprites::limb($rows, [20, 29], [19, 43], 2.4, 2.2, 'bccd');
Sprites::blob($rows, 26, 14, 5, 3.2, 'wwww');                   // the eye on its back, open in the wool
Sprites::dots($rows, [[25, 13], [26, 13], [25, 14], [26, 14], [27, 14], [26, 15]], 'e');
Sprites::dots($rows, [[21, 12], [22, 11], [23, 11], [29, 11], [30, 11], [31, 12]], 'a'); // its lids
Sprites::dots($rows, [[6, 16]], 'e');

return [
    'looks' => '形如羊，九尾四耳，目在背上。',
    'size' => [56, 44],
    'colors' => [
        'a' => '#8a7e6c', 'b' => '#b4a890', 'c' => '#d8ccb2', 'd' => '#f2eadc', // the wool
        't' => '#7e6a50', 'u' => '#a48c68', 'v' => '#c8ae84', 'w' => '#f4ecd8', // the tails; the eye's white
        'p' => '#5a4636', 'q' => '#7a6048', 'r' => '#9a7c5c', 's' => '#b8987a', // the face, the ears
        'e' => '#1a140e',
    ],
    'shades' => [],
    'outline' => '#1a140e',
    'views' => ['left' => [$rows]],
];
