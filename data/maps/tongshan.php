<?php
// 铜山, a hundred and thirty li east. The classic: gold, silver and iron; 榖, 柞, 柤, chestnut, orange and pomelo; its beasts
// are mostly 犳.
declare(strict_types=1);

return [
    'id' => 'tongshan',
    'name' => '铜山',
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
    'exits' => ['left' => ['qishanba', 0], 'right' => ['meishan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '铜山 —— 其上多金银铁，其木多榖柞柤栗橘櫾，其兽多犳'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[40, 'zhuo'], [41, 'zhuo'], [41, 'zhuo'], [43, 'zhuo'], [42, 'zhuo'], [44, 'zhuo'], [44, 'zhuo']]],
];
