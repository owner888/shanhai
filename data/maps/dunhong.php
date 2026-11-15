<?php
// 敦薨之山, three hundred and twenty li north of 大咸之山. The classic: on it many 棕 and 枏, below it much 茈草; the
// 敦薨之水 rises on it and runs west into 泑泽, with many 赤鲑 in it; its beasts are mostly 兕 and 旄牛, its birds
// 鸤鸠 (尸鸠 in data/beasts.php).
declare(strict_types=1);

return [
    'id' => 'dunhong',
    'name' => '敦薨之山',
    'range' => 8,
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
        'T~~~~~~~~..........T',
        'T~~~~~~~~..........T',
        'T..................T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['daxian', 0], 'right' => ['shaoxian', 0]],
    'signs' => [
        [9, 6, [
            ['say', '敦薨之山 —— 其上多棕枏，其下多茈草；敦薨之水出焉，而西流注于泑泽'],
        ]],
    ],
    // The 敦薨之水: 赤鲑.
    'water' => ['rate' => 2, 'slots' => [[20, 'chigui'], [15, 'chigui'], [20, 'chigui']]],
    // The tall grass: 兕, 旄牛 and 尸鸠.
    'wild' => ['rate' => 2, 'slots' => [[22, 'si'], [23, 'maoniu'], [23, 'shijiu'], [24, 'si'], [25, 'maoniu'], [23, 'shijiu'], [22, 'si']]],
];
