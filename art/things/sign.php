<?php
// A sign of a map: a framed board on a post, facing south, with lines of writing cut in it.
declare(strict_types=1);

return [
    'colors' => ['t' => '#6b4a2e', 'b' => '#b08250', 'g' => '#a0744a', 'd' => '#7a5634', 'k' => '#4a3020'],
    'parts' => ['body' => []],
    'boxes' => [
        ['body', 't', [-2, 0, 0], [1, 17, 3]],
        ['body', 'b', [-10, 14, 4], [9, 25, 5]],
        ['body', 'g', [-9, 17, 5], [8, 17, 5]], ['body', 'g', [-9, 22, 5], [8, 22, 5]],
        ['body', 'd', [-10, 25, 4], [9, 25, 5]], ['body', 'd', [-10, 14, 4], [9, 14, 5]],
        ['body', 'd', [-10, 14, 4], [-10, 25, 5]], ['body', 'd', [9, 14, 4], [9, 25, 5]],
        ['body', 'k', [-6, 21, 5], [5, 21, 5]], ['body', 'k', [-6, 18, 5], [3, 18, 5]],
    ],
];
