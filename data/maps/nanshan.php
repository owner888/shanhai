<?php
// 南山, a hundred and seventy li west of 时山. The classic: on it much 丹粟; the 丹水 rises on it and runs north into
// the 渭; its beasts are mostly 猛豹, its birds mostly 尸鸠.
declare(strict_types=1);

return [
    'id' => 'nanshan',
    'name' => '南山',
    'range' => 4,
    'rows' => [
        '^^^^^^^~~^^^^^^^^^^^',
        '^......~~..........^',
        '^.,,,,.~~...,,,,,..^',
        '^.,,,,.~~...,,,,,..^',
        '^.,,,,..~...,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^....TT....,,,,....^',
        '^....TT....,,,,....^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['dashi', 0], 'right' => ['shishan', 0]],
    'signs' => [
        [10, 6, [
            ['say', '南山 —— 上多丹粟；丹水出焉，北流注于渭'],
        ]],
    ],
    // The tall grass: 猛豹 and 尸鸠.
    'wild' => ['rate' => 4, 'slots' => [[13, 'shijiu'], [14, 'mengbao'], [15, 'shijiu'], [14, 'mengbao'], [15, 'shijiu'], [15, 'mengbao'], [15, 'mengbao']]],
];
