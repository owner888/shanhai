<?php
// 讙山, seventy li southeast. The classic: 檀 trees, 封 stone, white tin; the 郁水 comes out on it and goes under below it,
// with whetstone in it. It names no beast.
declare(strict_types=1);

return [
    'id' => 'huanshanba',
    'name' => '讙山',
    'range' => 22,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T.......~~~........T',
        'T.......~~~........T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['yushanba', 0], 'right' => ['renju', 0]],
    'signs' => [
        [9, 6, [
            ['say', '讙山 —— 其木多檀，多封石，多白锡；郁水出于其上，潜于其下'],
        ]],
    ],
];
