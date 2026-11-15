<?php
// 西皇之山, three hundred and fifty li west of 中皇之山. The classic: on its south side much gold, on its north much iron;
// its beasts are mostly 麋, 鹿 and 㸲牛.
declare(strict_types=1);

return [
    'id' => 'xihuang',
    'name' => '西皇之山',
    'range' => 5,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..,,,,,,,..,,,,,..^',
        '^..,,,,,,,..,,,,,..^',
        '^..,,,,,,,..,,,,,..^',
        '^..................^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^..................^',
        '^..,,,,,......^^^..^',
        '^..,,,,,......^^^..^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['laishan', 0], 'right' => ['zhonghuang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '西皇之山 —— 其阳多金，其阴多铁；其兽多麋、鹿、㸲牛'],
        ]],
    ],
    // The tall grass: 麋, 鹿 and 㸲牛.
    'wild' => ['rate' => 2, 'slots' => [[16, 'mi'], [16, 'yelu'], [16, 'zuoniu'], [16, 'mi'], [15, 'yelu'], [15, 'yelu'], [15, 'mi']]],
];
