<?php
// 女床之山, three hundred li southwest of 高山. The classic: on its south side much red copper, on its north much 石涅;
// its beasts are mostly 虎, 豹, 犀 and 兕; the bird 鸾鸟 lives there, like a pheasant with the five colours, and when it
// is seen the world is at peace. It is in the grass seldom, beside the beasts the mountain has many of.
declare(strict_types=1);

return [
    'id' => 'nuchuang',
    'name' => '女床之山',
    'range' => 5,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..,,,,,,..,,,,,,..^',
        '^..,,,,,,..,,,,,,..^',
        '^..,,,,,,..,,,,,,..^',
        '^..................^',
        '^....TT......TT....^',
        '^..................^',
        '====================',
        '^..................^',
        '^..,,,,,...........^',
        '^..,,,,,.....^^....^',
        '^..,,,,,.....^^....^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['longshou', 0], 'right' => ['gaoshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '女床之山 —— 其阳多赤铜，其阴多石涅；其兽多虎豹犀兕'],
        ]],
    ],
    // The tall grass: 虎, 豹, 犀, 兕, and seldom 鸾鸟.
    'wild' => ['rate' => 2, 'slots' => [[16, 'hu'], [16, 'bao'], [16, 'xi'], [16, 'si'], [15, 'luanniao'], [15, 'luanniao'], [15, 'luanniao']]],
];
