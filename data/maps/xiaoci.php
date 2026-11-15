<?php
// 小次之山, four hundred li west of 鸟危之山. The classic: on it much white jade, below it much red copper; the beast 朱厌
// lives there, an ape with a white head and red feet, at whose sight there is great war.
declare(strict_types=1);

return [
    'id' => 'xiaoci',
    'name' => '小次之山',
    'range' => 5,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^....^^^^....^^^^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^..^^..........^^..^',
        '^..^^...,,,,...^^..^',
        '^.......,,,,.......^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['daci', 0], 'right' => ['niaowei', 0]],
    'signs' => [
        [9, 6, [
            ['say', '小次之山 —— 其上多白玉，其下多赤铜'],
        ]],
    ],
    // The tall grass: 朱厌.
    'wild' => ['rate' => 2, 'slots' => [[16, 'zhuyan'], [16, 'zhuyan'], [16, 'zhuyan'], [16, 'zhuyan'], [15, 'zhuyan'], [15, 'zhuyan'], [15, 'zhuyan']]],
];
