<?php
// 葛山, two hundred li east. The classic: red gold on it, 瑊 stone below; 柤, chestnut, orange, pomelo, 楢 and 杻; its beasts
// are mostly 麢 and 㚟; much 嘉荣.
declare(strict_types=1);

return [
    'id' => 'geshan',
    'name' => '葛山',
    'range' => 23,
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
    'exits' => ['left' => ['guishanjiu', 0], 'right' => ['jiachao', 0]],
    'signs' => [
        [9, 6, [
            ['say', '葛山 —— 其上多赤金，其下多瑊石，其木多柤栗橘櫾楢杻'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[42, 'ling'], [43, 'chuotu'], [43, 'ling'], [45, 'chuotu'], [44, 'ling'], [46, 'chuotu'], [46, 'ling']]],
];
