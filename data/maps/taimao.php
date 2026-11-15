<?php
// 泰冒之山, two hundred li west of 钤山. The classic: on its south side much jade, on its north much iron; the 浴水 rises
// on it and runs east into the 河, with much 藻玉 in it and many white snakes.
declare(strict_types=1);

return [
    'id' => 'taimao',
    'name' => '泰冒之山',
    'range' => 5,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..^^.......^^.....^',
        '^..^^.......^^.....^',
        '^..................^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^....~~~~..........^',
        '^...~~~~~~~~~~~~~~~~',
        '^....~~~~~~~~~~~~~~~',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['shuli', 0], 'right' => ['qianshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '泰冒之山 —— 其阳多玉，其阴多铁；浴水出焉，东流注于河'],
        ]],
    ],
    // The 浴水: 白蛇.
    'water' => ['rate' => 2, 'slots' => [[20, 'baishe'], [15, 'baishe'], [20, 'baishe']]],
];
