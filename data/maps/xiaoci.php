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
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 白豪、豹、麋、白豪 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 2, 'slots' => [[16, 'baihao'], [16, 'bao'], [16, 'mi'], [16, 'baihao'], [15, 'zhuyan'], [15, 'zhuyan'], [15, 'zhuyan']]],
];
