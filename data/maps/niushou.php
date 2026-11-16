<?php
// 牛首之山, thirty li north. The classic: a herb called 鬼草, which takes sorrow away; the 劳水 rises on it and runs west into
// the 潏水, with many 飞鱼 in it, shaped like 鲋.
declare(strict_types=1);

return [
    'id' => 'niushou',
    'name' => '牛首之山',
    'range' => 15,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^.............^^...^',
        '^.............^^...^',
        '^..................^',
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
    'exits' => ['left' => ['wulin', 0], 'right' => ['huoshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '牛首之山 —— 有草焉，名曰鬼草，服之不忧；劳水出焉，而西流注于潏水'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'feiyu'], [15, 'feiyu'], [20, 'feiyu']]],
];
