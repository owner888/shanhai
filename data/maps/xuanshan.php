<?php
// 宣山, fifty-five li east. The classic: the 沦水 rises on it and runs southeast into the 瀙, with many 蛟 in it; a mulberry
// on it fifty feet round, called 帝女之桑.
declare(strict_types=1);

return [
    'id' => 'xuanshan',
    'name' => '宣山',
    'range' => 25,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T...^^.............T',
        'T...^^.............T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T...........~~~~~~~T',
        'T...TT......~~~~~~~T',
        'T...TT.............T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['yashan', 0], 'right' => ['hengshansy', 0]],
    'signs' => [
        [9, 6, [
            ['say', '宣山 —— 沦水出焉，东南流注于瀙水，其中多蛟；其上有桑焉，名曰帝女之桑'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'jiaolong'], [15, 'jiaolong'], [20, 'jiaolong']]],
];
