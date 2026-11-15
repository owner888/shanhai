<?php
// 皋涂之山, three hundred and eighty li southwest of 天帝之山. The classic: a water rises on it and runs west into the
// 诸资之水; the 涂水 rises on it and runs south into the 集获之水; on its south side much 丹粟, on its north much silver
// and gold; on it many 桂; the beast 𤣎如 lives there, and the bird 数斯. The mountain's name and its first water's are
// still to be checked against a second edition (docs/bestiary.md).
declare(strict_types=1);

return [
    'id' => 'gaotu',
    'name' => '皋涂之山',
    'range' => 4,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..TT.....TT.......^',
        '^..TT.,,,,TT.,,,,..^',
        '~~~...,,,,...,,,,..^',
        '^~~~..,,,,...,,,,..^',
        '^..~...............^',
        '^..................^',
        '====================',
        '^..................^',
        '^.....,,,,...~.....^',
        '^.....,,,,...~~....^',
        '^.....,,,,...~~....^',
        '^............~~....^',
        '^^^^^^^^^^^^^~~^^^^^',
    ],
    'exits' => ['left' => ['huangshan', 0], 'right' => ['tiandi', 0]],
    'signs' => [
        [11, 6, [
            ['say', '皋涂之山 —— 涂水出焉，南流注于集获之水；其上多桂木'],
        ]],
    ],
    // The tall grass: 𤣎如 and the bird 数斯.
    'wild' => ['rate' => 4, 'slots' => [[13, 'shusi'], [14, 'shusi'], [15, 'jueru'], [14, 'shusi'], [15, 'jueru'], [15, 'jueru'], [15, 'shusi']]],
];
