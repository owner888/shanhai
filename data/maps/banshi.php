<?php
// 半石之山, seventy li east. The classic: a herb called 嘉荣, which keeps off fear of thunder; the 来需之水 rises on its south
// side and runs west into the 伊水, with many 鯩鱼 in it; the 合水 rises on its north side and runs north into the 洛, with
// many 䲢鱼 in it.
declare(strict_types=1);

return [
    'id' => 'banshi',
    'name' => '半石之山',
    'range' => 21,
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
    'exits' => ['left' => ['dakushan', 0], 'right' => ['shaoshi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '半石之山 —— 其上有草焉，名曰嘉荣；来需之水出于其阳，而西流注于伊水'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'lunyu'], [15, 'tengyu'], [20, 'lunyu']]],
];
