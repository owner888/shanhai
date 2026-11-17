<?php
// A person of the classic's world: a robe crossed at the breast, wide sleeves, a sash; hair bound in a knot with a pin.
// The robe is the color of who they are (Stage::LOOKS). Tall for its head: the camera looks down at 51 degrees and
// shows an upright figure at about six tenths of its height, so this one stands about a tile high on the screen.
declare(strict_types=1);

// The robe's white edge, crossing from the left shoulder down to the right above the sash.
$lapel = array_map(fn(int $k) => ['body', 'c', [-4 + $k, 31 - $k, 4], [-3 + $k, 31 - $k, 4]], range(0, 9));

return [
    'colors' => [
        'r' => null,      // the robe: given by the actor's look
        's' => '#f0c8a0', // skin
        'k' => '#eaa88e', // cheeks
        'm' => '#b8705c', // mouth
        'h' => '#1e1a1a', // hair, brows
        'g' => '#c9a227', // the hairpin
        'p' => '#3b3a46', // trousers
        'f' => '#4a3424', // shoes
        'b' => '#8a5a2b', // the sash
        'c' => '#f2eee2', // the robe's edge
        'e' => '#1a1a1a', // eyes
    ],
    'parts' => ['body' => []],
    'boxes' => [
        ['body', 'f', [-6, 0, -2], [-2, 1, 4]], ['body', 'f', [1, 0, -2], [5, 1, 4]],
        ['body', 'p', [-5, 2, -2], [-2, 12, 2]], ['body', 'p', [1, 2, -2], [4, 12, 2]],
        // the robe, its hem wider
        ['body', 'r', [-8, 10, -4], [7, 31, 4]],
        ['body', 'r', [-9, 10, -5], [8, 13, 5]],
        ...$lapel,
        ['body', 'b', [-8, 20, -4], [7, 21, 4]], ['body', 'b', [2, 15, 5], [3, 19, 5]],
        // wide sleeves, and the hands out of them
        ['body', 'r', [-12, 17, -3], [-9, 31, 3]], ['body', 'r', [8, 17, -3], [11, 31, 3]],
        ['body', 'r', [-12, 16, -3], [-9, 19, 4]], ['body', 'r', [8, 16, -3], [11, 19, 4]],
        ['body', 's', [-11, 14, -1], [-10, 15, 1]], ['body', 's', [9, 14, -1], [10, 15, 1]],
        // the neck and the head
        ['body', 's', [-2, 32, -2], [1, 33, 1]],
        ['body', 's', [-6, 34, -5], [5, 45, 4]],
        // the hair: over the top, the fringe, down the back and the sides; the knot and its pin
        ['body', 'h', [-6, 44, -6], [5, 46, 4]],
        ['body', 'h', [-6, 43, 4], [5, 43, 4]], ['body', 'h', [-6, 41, 4], [-5, 42, 4]], ['body', 'h', [4, 41, 4], [5, 42, 4]],
        ['body', 'h', [-6, 35, -6], [5, 45, -6]],
        ['body', 'h', [-6, 37, -5], [-6, 45, 1]], ['body', 'h', [5, 37, -5], [5, 45, 1]],
        ['body', 'h', [-2, 47, -3], [1, 50, 0]],
        ['body', 'g', [-4, 48, -2], [3, 48, -1]],
        // the face
        ['body', 'h', [-5, 41, 4], [-3, 41, 4]], ['body', 'h', [2, 41, 4], [4, 41, 4]],
        ['body', 'e', [-4, 38, 4], [-3, 39, 4]], ['body', 'e', [2, 38, 4], [3, 39, 4]],
        ['body', 'k', [-5, 36, 4], [-4, 36, 4]], ['body', 'k', [3, 36, 4], [4, 36, 4]],
        ['body', 'm', [-1, 35, 4], [0, 35, 4]],
    ],
];
