<?php
// 乐游之山, three hundred and seventy li west of 昆仑之丘. The classic: the 桃水 rises on it and runs west into 稷泽, with
// much white jade in it, and many 䱻鱼, a snake's shape with four feet.
declare(strict_types=1);

return [
    'id' => 'leyou',
    'name' => '乐游之山',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..................^',
        '^............^^^...^',
        '^............^^^...^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '~~~~~~~~~..........^',
        '~~~~~~~~~..........^',
        '^...~~~~~..........^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['liusha', 0], 'right' => ['kunlun', 0]],
    'signs' => [
        [10, 6, [
            ['say', '乐游之山 —— 桃水出焉，西流注于稷泽，是多白玉'],
        ]],
    ],
    // The 桃水: 䱻鱼.
    'water' => ['rate' => 2, 'slots' => [[20, 'huayu'], [15, 'huayu'], [20, 'huayu']]],
];
