<?php
// A rock of a map: a low boulder and a smaller one on it, lighter above, moss on its top.
declare(strict_types=1);

use Shanhai\Render\Voxels;

return [
    'colors' => ['d' => '#76746c', 'g' => '#8e8c84', 'l' => '#a8a69c', 'm' => '#6f9a5a'],
    'parts' => ['body' => []],
    'boxes' => [
        ...Voxels::ball('body', 'd', [0, 3, 0], 12, 6, 10),
        ...Voxels::ball('body', 'g', [0, 6, 0], 11, 6, 9),
        ...Voxels::ball('body', 'l', [-2, 11, -1], 7, 5, 6),
        ...Voxels::ball('body', 'm', [-1, 15, 0], 3, 2, 3),
    ],
];
