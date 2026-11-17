<?php
// 鹿蜀, of 杻阳之山: a horse with a white head, a tiger's stripes and a red tail. The text gives no coat but the stripes,
// so the coat is a tiger's, tawny under them, lighter at the belly.
declare(strict_types=1);

use Shanhai\Render\Voxels;

// A tiger's stripe across the back at z, running down each flank and forward as it goes, thinner as it goes.
$stripe = fn(int $z) => [
    ['body', 'k', [-6, 28, $z], [5, 31, $z + 1]],
    ['body', 'k', [-6, 24, $z + 1], [5, 27, $z + 2]],
    ['body', 'k', [-6, 21, $z + 2], [5, 23, $z + 2]],
];

return [
    'looks' => '形如马，白首，身有虎纹，赤尾。',
    'colors' => [
        't' => '#d9a441', // the coat
        'b' => '#ecc878', // the belly
        'k' => '#3a2a22', // the stripes
        'w' => '#f2eee2', // the white head
        'm' => '#d8d0c0', // the mane, the ears' insides
        'r' => '#c23b2b', // the red tail
        'q' => '#962a20', // its shadowed strands
        'h' => '#4a3a30', // hooves, nostrils
        'e' => '#1a1a1a', // eyes
    ],
    'parts' => [
        'body' => [],
        'head' => ['at' => [0, 38, 19], 'swing' => ['x', 0.10, 2.5]],
        'tail' => ['at' => [0, 29, -18], 'swing' => ['z', 0.25, 3.0]],
    ],
    'boxes' => [
        // the legs: hooves, lower legs, and the upper legs thicker, the hind thighs most
        ['body', 'h', [-6, 0, 8], [-4, 2, 11]], ['body', 'h', [3, 0, 8], [5, 2, 11]],
        ['body', 'h', [-6, 0, -15], [-4, 2, -12]], ['body', 'h', [3, 0, -15], [5, 2, -12]],
        ['body', 't', [-6, 3, 9], [-4, 17, 11]], ['body', 't', [3, 3, 9], [5, 17, 11]],
        ['body', 't', [-6, 3, -14], [-4, 17, -12]], ['body', 't', [3, 3, -14], [5, 17, -12]],
        ['body', 't', [-6, 11, 8], [-4, 19, 11]], ['body', 't', [3, 11, 8], [5, 19, 11]],
        ['body', 't', [-7, 12, -16], [-4, 21, -11]], ['body', 't', [3, 12, -16], [6, 21, -11]],
        // the trunk, its chest and rump, its edges rounded
        ['body', 't', [-6, 18, -16], [5, 31, 14]],
        ['body', 't', [-5, 20, 15], [4, 30, 16]], ['body', 't', [-5, 21, -18], [4, 31, -17]],
        ['body', '.', [-6, 30, -16], [-6, 31, 14]], ['body', '.', [5, 30, -16], [5, 31, 14]],
        ['body', '.', [-5, 31, -16], [-5, 31, 14]], ['body', '.', [4, 31, -16], [4, 31, 14]],
        ['body', '.', [-6, 18, -16], [-6, 19, 14]], ['body', '.', [5, 18, -16], [5, 19, 14]],
        ['body', 'b', [-5, 18, -14], [4, 19, 12]],
        // the neck, rising forward
        ['body', 't', [-4, 26, 12], [3, 33, 17]], ['body', 't', [-4, 31, 15], [3, 38, 20]], ['body', 't', [-3, 36, 18], [2, 41, 22]],
        ...$stripe(-13), ...$stripe(-7), ...$stripe(-1), ...$stripe(5), ...$stripe(10),
        // the mane, down the back of the neck
        ['body', 'm', [-1, 30, 11], [0, 34, 12]], ['body', 'm', [-1, 34, 14], [0, 38, 15]], ['body', 'm', [-1, 38, 17], [0, 42, 18]],
        // the white head: skull, long muzzle narrowing at its end, ears, eyes, nostrils, the forelock
        ['head', 'w', [-4, 37, 18], [3, 44, 24]],
        ['head', 'w', [-3, 34, 24], [2, 40, 30]],
        ['head', '.', [-3, 40, 29], [2, 40, 30]], ['head', '.', [-3, 34, 30], [-3, 40, 30]], ['head', '.', [2, 34, 30], [2, 40, 30]],
        ['head', 'w', [-4, 45, 20], [-3, 47, 21]], ['head', 'w', [-4, 48, 20], [-4, 49, 20]], ['head', 'm', [-3, 45, 21], [-3, 46, 21]],
        ['head', 'w', [2, 45, 20], [3, 47, 21]], ['head', 'w', [3, 48, 20], [3, 49, 20]], ['head', 'm', [2, 45, 21], [2, 46, 21]],
        ['head', 'e', [-4, 41, 21], [-4, 42, 22]], ['head', 'e', [3, 41, 21], [3, 42, 22]],
        ['head', 'h', [-2, 37, 30], [-2, 38, 30]], ['head', 'h', [1, 37, 30], [1, 38, 30]],
        ['head', 'm', [-1, 45, 22], [0, 45, 24]],
        // the red tail: falling back from the rump and down, full at its end, darker strands in it
        ['tail', 'r', [-1, 25, -20], [0, 30, -19]],
        ['tail', 'r', [-2, 16, -22], [1, 26, -20]],
        ['tail', 'r', [-3, 8, -24], [2, 18, -21]],
        ['tail', 'q', [-1, 9, -24], [-1, 16, -24]], ['tail', 'q', [1, 10, -23], [1, 17, -23]], ['tail', 'q', [-3, 11, -22], [-3, 17, -22]],
    ],
];
