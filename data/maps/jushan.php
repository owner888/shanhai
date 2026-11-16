<?php
// 崌山, a hundred and fifty li east. The classic: the 江 rises on it and runs east into the great 江, with many strange
// snakes and 䲀鱼 in it; 楢, 杻, 梅 and 梓; its beasts are mostly 夔牛, 麢, 㚟, rhinoceros and 兕; the bird 窃脂 lives there, an owl
// with a red body and a white head, which wards off fire. The strange snakes are met on its ground.
declare(strict_types=1);

return [
    'id' => 'jushan',
    'name' => '崌山',
    'range' => 23,
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
    'exits' => ['left' => ['laishanjiu', 0], 'right' => ['gaoliang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '崌山 —— 江水出焉，东流注于大江，其中多怪蛇，多䲀鱼；其木多楢杻，多梅梓'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'zeyu'], [15, 'zeyu'], [20, 'zeyu']]],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[42, 'kuiniu'], [43, 'ling'], [43, 'chuotu'], [45, 'xi'], [44, 'si'], [46, 'guaishe'], [46, 'qiezhi']]],
];
