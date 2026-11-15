<?php
// 鸟危之山, two hundred li southwest of 鹿台之山. The classic: on its south side much 磬石, on its north many 檀 and 楮;
// the 鸟危之水 rises on it and runs west into the 赤水, with much 丹粟 in it. It names no beast.
declare(strict_types=1);

return [
    'id' => 'niaowei',
    'name' => '鸟危之山',
    'range' => 5,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T.TT..TT..TT..TT...T',
        'T.TT..TT..TT..TT...T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        '^..................^',
        '~~~~~~~~~..........^',
        '~~~~~~~~~~...^^....^',
        '^............^^....^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['xiaoci', 0], 'right' => ['lutai', 0]],
    'signs' => [
        [9, 6, [
            ['say', '鸟危之山 —— 其阳多磬石，其阴多檀楮；鸟危之水出焉，西流注于赤水'],
        ]],
    ],
];
