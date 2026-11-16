<?php
// 子桐之山, two hundred li southeast. The classic: the 子桐之水 rises on it and runs west into the marsh 馀如, and in it are many
// 䱻鱼, fish with a bird's wings, which shine as they come and go, and at whose sight the world has drought: met here on
// the ground by the water, being of fire.
declare(strict_types=1);

return [
    'id' => 'zitong',
    'name' => '子桐之山',
    'range' => 14,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^~~~~~~~...........^',
        '^~~~~~~~...........^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['qinshan', 0], 'right' => ['shanshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '子桐之山 —— 子桐之水出焉，而西流注于馀如之泽'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's fourteenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[3, 'huayudong'], [3, 'huayudong'], [5, 'huayudong'], [7, 'huayudong'], [7, 'huayudong'], [4, 'huayudong'], [4, 'huayudong']]],
];
