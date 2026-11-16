<?php
// 刚山, a hundred and twenty li west of 泾谷之山. The classic: many 柒木, much 㻬琈 jade; the 刚水 rises on it and runs
// north into the 渭; there are many 神魑 there, a human face on a beast's body, one foot and one hand.
declare(strict_types=1);

return [
    'id' => 'gangshan',
    'name' => '刚山',
    'range' => 7,
    'rows' => [
        'TTTTTTTTT~~TTTTTTTTT',
        'T........~~........T',
        'T..,,,,,.~~.,,,,,..T',
        'T..,,,,,.~~.,,,,,..T',
        'T..,,,,,.~~.,,,,,..T',
        'T........~~........T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['gangwei', 0], 'right' => ['jinggu', 0]],
    'signs' => [
        [12, 6, [
            ['say', '刚山 —— 多柒木，多㻬琈之玉；是多神魑'],
        ]],
    ],
    // The tall grass: 神魑.
    'wild' => ['rate' => 10, 'slots' => [[13, 'shenchi'], [14, 'shenchi'], [15, 'shenchi'], [16, 'shenchi'], [15, 'shenchi'], [16, 'shenchi'], [16, 'shenchi']]],
];
