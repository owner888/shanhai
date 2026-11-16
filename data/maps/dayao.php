<?php
// 大尧之山, a hundred li northeast. The classic: pines and cypresses, 梓, mulberry and 机, bamboo; its beasts are mostly
// leopards, tigers, 麢 and 㚟.
declare(strict_types=1);

return [
    'id' => 'dayao',
    'name' => '大尧之山',
    'range' => 22,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..............TT..T',
        'T..................T',
        '====================',
        'T..................T',
        'T.......,,,,,......T',
        'T.......,,,,,^^....T',
        'T.......,,,,,^^....T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['meishan', 0], 'right' => ['lingshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '大尧之山 —— 其木多松柏，多梓桑，多机，其草多竹，其兽多豹虎麢㚟'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[40, 'bao'], [41, 'hu'], [41, 'ling'], [43, 'chuotu'], [42, 'bao'], [44, 'hu'], [44, 'ling']]],
];
