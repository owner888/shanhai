<?php
// 鸡山, five hundred li east of 灌湘之山 (`jishan` is 基山, of the first range). The classic: on it much gold, below it
// much 丹雘; the 黑水 rises on it and runs south into the sea, and in it is 鱄鱼, at whose sight there is great drought.
declare(strict_types=1);

return [
    'id' => 'jishan3',
    'name' => '鸡山',
    'range' => 3,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^....^^^^....^^^^^',
        '^..................^',
        '^...T..........T...^',
        '^..................^',
        '^..................^',
        '====================',
        '^.......~~~........^',
        '^.......~~~~.......^',
        '^..^^...~~~~...^^..^',
        '^..^^..~~~~~~..^^..^',
        '^......~~~~~~......^',
        '^^^^^^^~~~~~~^^^^^^^',
    ],
    'exits' => ['left' => ['guanxiang', 0], 'right' => ['lingqiu', 0]],
    'signs' => [
        [12, 6, [
            ['say', '鸡山 —— 其上多金，其下多丹雘；黑水出焉，南流注于海'],
        ]],
    ],
    // The 黑水: 鱄鱼.
    'water' => ['rate' => 2, 'slots' => [[20, 'tuanyu'], [15, 'tuanyu'], [20, 'tuanyu']]],
];
