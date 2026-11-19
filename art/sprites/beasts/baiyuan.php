<?php
// 白猿, of 堂庭之山, drawn from its entry's looks: an ape, white; standing half up, its long arms to the ground, its face
// dark. Seen from the side, facing left (Sprites). 44 by 48 pixels.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(44, 48);
Sprites::limb($rows, [26, 30], [30, 46], 3.0, 2.4, 'aabb');     // the far leg
Sprites::limb($rows, [18, 16], [10, 44], 2.4, 2.0, 'aabb');     // the far arm
Sprites::blob($rows, 24, 24, 10, 12, 'abcd');                   // the body, stooping
Sprites::limb($rows, [22, 32], [22, 46], 3.2, 2.6, 'bccd');     // the near leg
Sprites::blob($rows, 15, 10, 7, 7, 'abcd');                     // the head
Sprites::blob($rows, 11, 12, 4, 3.5, 'pqrs');                   // the dark face
Sprites::limb($rows, [22, 16], [14, 45], 2.6, 2.2, 'bccd');     // the near arm, long
Sprites::dots($rows, [[10, 11], [9, 14], [10, 14]], 'e');
Sprites::dots($rows, [[12, 46], [13, 46], [14, 46], [15, 46]], 'a');

return [
    'looks' => '白色的猿。',
    'size' => [44, 48],
    'colors' => [
        'a' => '#8e8c94', 'b' => '#bab8c0', 'c' => '#dddce2', 'd' => '#faf8f6', // the white fur
        'p' => '#2e2826', 'q' => '#463c38', 'r' => '#5e504a', 's' => '#76665e', // the face
        'e' => '#e8d8b0',
    ],
    'shades' => [],
    'outline' => '#1c1a20',
    'views' => ['left' => [$rows]],
];
