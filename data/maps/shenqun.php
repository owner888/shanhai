<?php
// 神囷之山, three hundred li north. The classic: patterned stone on it; white snakes and flying insects below; the 黄水 and
// the 滏水 rise on it and run east.
declare(strict_types=1);

return [
    'id' => 'shenqun',
    'name' => '神囷之山',
    'range' => 10,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
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
    'exits' => ['left' => ['juru', 0], 'right' => ['fajiu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '神囷之山 —— 其上有文石，其下有白蛇，有飞虫'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the three areas before the reference's tenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[17, 'baishe'], [19, 'baishe'], [16, 'baishe'], [17, 'baishe'], [16, 'baishe'], [18, 'baishe'], [18, 'baishe']]],
];
