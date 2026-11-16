<?php
// 馀峨之山, three hundred and eighty li south. The classic: 梓 and 楠 on it, 荆 and 芑 below; the 杂余之水 rises on it and runs east
// into the 黄水; the beast 犰狳 lives there, a hare with a bird's beak, an owl's eyes and a snake's tail, which plays dead
// when it sees someone.
declare(strict_types=1);

return [
    'id' => 'yue',
    'name' => '馀峨之山',
    'range' => 12,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
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
    'exits' => ['left' => ['geshanshou', 0], 'right' => ['dufu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '馀峨之山 —— 其上多梓楠，其下多荆芑；杂余之水出焉，东流注于黄水'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's twelfth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[26, 'qiuyu'], [26, 'qiuyu'], [28, 'qiuyu'], [28, 'qiuyu'], [30, 'qiuyu'], [30, 'qiuyu'], [28, 'qiuyu']]],
];
