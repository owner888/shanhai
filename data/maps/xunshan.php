<?php
// 洵山, four hundred li east of 咸阴之山. The classic: on its south side much gold, on its north much jade; 䍺 lives
// there, which must not be killed; the 洵水 rises on it and runs south into the marsh of 阏, and in it are many 芘蠃.
declare(strict_types=1);

return [
    'id' => 'xunshan',
    'name' => '洵山',
    'range' => 2,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^.,,,,,,....,,,,,,.^',
        '^.,,,,,,....,,,,,,.^',
        '^.,,,,,,....,,,,,,.^',
        '^.,,,,,,...........^',
        '^..................^',
        '====================',
        '^..................^',
        '^.......~~~........^',
        '^.^^....~~~.....^^.^',
        '^.^^..~~~~~~~~..^^.^',
        '^.....~~~~~~~~.....^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['xianyin', 0], 'right' => ['huzhuo', 0]],
    'signs' => [
        [10, 6, [
            ['say', '洵山 —— 洵水南流，注于阏之泽'],
        ]],
    ],
    // The 洵水: 芘蠃.
    'water' => ['rate' => 6, 'slots' => [[5, 'biluo'], [6, 'biluo'], [4, 'biluo']]],
    // The tall grass: 䍺.
    'wild' => ['rate' => 10, 'slots' => [[5, 'huan'], [6, 'huan'], [7, 'huan'], [6, 'huan'], [8, 'huan'], [8, 'huan'], [8, 'huan']]],
];
