<?php
// A tree of a map: a trunk flaring at its roots, under clumps of leaves, the lower darker, the upper catching the light.
declare(strict_types=1);

use Shanhai\Render\Voxels;

return [
    'colors' => ['t' => '#6b4a2e', 'u' => '#523621', 'a' => '#2f6b48', 'b' => '#3f8758', 'c' => '#5aa46c', 'l' => '#7cbf86'],
    'parts' => ['body' => []],
    'boxes' => [
        ['body', 't', [-2, 0, -2], [1, 20, 1]],
        ['body', 'u', [-3, 0, -3], [2, 2, 2]],
        ['body', 'u', [-2, 6, 1], [-2, 14, 1]], ['body', 'u', [1, 3, -2], [1, 10, -2]],
        ...Voxels::ball('body', 'a', [0, 26, 0], 12, 8, 12),
        ...Voxels::ball('body', 'b', [-5, 32, -2], 8, 6, 8),
        ...Voxels::ball('body', 'b', [5, 31, 3], 8, 6, 8),
        ...Voxels::ball('body', 'c', [0, 38, 0], 7, 5, 7),
        ...Voxels::ball('body', 'l', [1, 41, 1], 4, 2, 4),
        ...Voxels::ball('body', 'l', [-6, 36, -1], 3, 2, 3),
    ],
];
