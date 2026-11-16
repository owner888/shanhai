<?php
// 阳帝之山, a hundred and twenty li southeast. The classic: fine copper; 橿, 杻, 檿 and 楮; its beasts are mostly 麢 and musk
// deer.
declare(strict_types=1);

return [
    'id' => 'yangdi',
    'name' => '阳帝之山',
    'range' => 26,
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
    'exits' => ['left' => ['zhenling', 0], 'right' => ['chaisang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '阳帝之山 —— 多美铜，其木多橿、杻、檿、楮，其兽多麢、麝'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[48, 'ling'], [49, 'she'], [49, 'ling'], [51, 'she'], [50, 'ling'], [52, 'she'], [52, 'ling']]],
];
