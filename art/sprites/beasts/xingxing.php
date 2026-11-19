<?php
// 狌狌, of 招摇之山, drawn from its entry's looks: an ape of the long-tailed kind (禺), crouched to go on all fours,
// its ears white; brown, its face bare. Seen from the side, facing left (Sprites). 48 by 36 pixels.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(48, 36);
Sprites::limb($rows, [34, 18], [42, 6], 1.6, 1.2, 'abcd');      // the tail, curled up behind
Sprites::limb($rows, [41, 6], [37, 3], 1.2, 1.0, 'abcd');
Sprites::limb($rows, [30, 22], [32, 33], 2.0, 1.8, 'aabb');     // the far legs
Sprites::limb($rows, [14, 20], [12, 33], 2.0, 1.8, 'aabb');
Sprites::blob($rows, 24, 19, 12, 7, 'abcd');                    // the body, crouched
Sprites::blob($rows, 10, 13, 6.5, 6, 'abcd');                   // the head
Sprites::blob($rows, 7, 15, 3.5, 3, 'pqrs');                    // the bare face
Sprites::limb($rows, [27, 22], [27, 33], 2.2, 2.0, 'bccd');     // the near legs, the arm reaching to the ground
Sprites::limb($rows, [16, 20], [8, 33], 2.0, 1.8, 'bccd');
Sprites::blob($rows, 13, 7, 2.5, 3, 'wwWW');                    // the white ear
Sprites::dots($rows, [[6, 14], [5, 17], [6, 17]], 'e');         // the eye, the mouth
Sprites::dots($rows, [[6, 33], [7, 33], [8, 33], [9, 33], [26, 33], [27, 33], [28, 33], [29, 33]], 'a');

return [
    'looks' => '形如禺而白耳，伏地而行，也能像人一样奔走。',
    'size' => [48, 36],
    'colors' => [
        'a' => '#3e2a1c', 'b' => '#6a4628', 'c' => '#93683c', 'd' => '#b88c56', // the fur, shade to light
        'p' => '#a07058', 'q' => '#c08c70', 'r' => '#d8a888', 's' => '#ecc4a4', // the bare face
        'w' => '#f4f0e8', 'W' => '#c8c2b8',                                       // the white ear
        'e' => '#1a120c',
    ],
    'shades' => [],
    'outline' => '#1a120c',
    'views' => ['left' => [$rows]],
];
