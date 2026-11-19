<?php
// 旋龟, of 杻阳之山, drawn from its entry's looks: a turtle of the 怪水, a bird's head, a viper's tail. Its shell domed and
// plated, its head with a hooked beak, its tail long and thin as a snake's. Seen from the side, facing left (Sprites).
// 52 by 36 pixels.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(52, 36);
Sprites::limb($rows, [40, 26], [50, 20], 2.2, 0.9, 'xyz');      // the viper's tail
Sprites::limb($rows, [34, 27], [36, 34], 2.4, 2.2, 'xxyy');     // the far feet
Sprites::limb($rows, [18, 27], [16, 34], 2.4, 2.2, 'xxyy');
Sprites::limb($rows, [16, 22], [9, 15], 2.6, 2.4, 'xyz');       // the neck
Sprites::blob($rows, 7, 12, 5, 4.5, 'pqrs');                    // the bird's head
Sprites::dots($rows, [[1, 12], [2, 12], [2, 13], [1, 13], [2, 14], [3, 14]], 'h'); // the hooked beak
Sprites::blob($rows, 28, 20, 16, 10, 'abcd');                   // the shell
Sprites::pattern($rows, 'abcd', 'k', fn(int $x, int $y) => $y < 28 && ($x % 8 === 0 || ($y - 13) % 6 === 0)); // its plates
Sprites::limb($rows, [30, 28], [31, 35], 2.6, 2.4, 'xyzz');     // the near feet
Sprites::limb($rows, [20, 28], [19, 35], 2.6, 2.4, 'xyzz');
Sprites::blob($rows, 28, 29, 15, 2, 'xy');                      // the rim under the shell
Sprites::dots($rows, [[6, 11]], 'e');

return [
    'looks' => '怪水中的龟，鸟首，虺尾。',
    'size' => [52, 36],
    'colors' => [
        'a' => '#2a3e36', 'b' => '#3e5a4a', 'c' => '#587a5e', 'd' => '#7c9c74', // the shell
        'k' => '#1a2620',                                                       // its seams
        'x' => '#5e5236', 'y' => '#86784e', 'z' => '#aa9c6c',                     // the skin, the tail
        'p' => '#6a5034', 'q' => '#8e6c44', 'r' => '#b08c58', 's' => '#ccaa74', // the bird's head
        'h' => '#d8b040', 'e' => '#16120c',
    ],
    'shades' => [],
    'outline' => '#12160e',
    'views' => ['left' => [$rows]],
];
