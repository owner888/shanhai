<?php
// 毕山, thirty li southeast (`bishan` is 碧山, of the east). The classic: the 帝苑之水 rises on it and runs northeast into the
// 瀙, with crystal and many 蛟 in it; 㻬琈 jade.
declare(strict_types=1);

return [
    'id' => 'bishansy',
    'name' => '毕山',
    'range' => 25,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^...^^.............^',
        '^...^^.............^',
        '^..................^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^...........~~~~~~~^',
        '^...........~~~~~~~^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['yingzhen', 0], 'right' => ['lema', 0]],
    'signs' => [
        [9, 6, [
            ['say', '毕山 —— 帝苑之水出焉，东北流注于瀙，其中多水玉，多蛟；其上多㻬琈之玉'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'jiaolong'], [15, 'jiaolong'], [20, 'jiaolong']]],
];
