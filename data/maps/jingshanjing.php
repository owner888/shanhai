<?php
// 荆山, a hundred li northeast. The classic: iron on its north side, red gold on its south; many 犛牛 (taken as 旄牛),
// leopards and tigers; pines, cypresses, bamboo, orange and pomelo; the 漳水 rises on it and runs southeast into the 雎,
// with gold and many 鲛鱼 in it; its beasts are mostly 闾 and elk.
declare(strict_types=1);

return [
    'id' => 'jingshanjing',
    'name' => '荆山',
    'range' => 22,
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
    'exits' => ['left' => ['jingshanba', 0], 'right' => ['jiaoshanba', 0]],
    'signs' => [
        [9, 6, [
            ['say', '荆山 —— 其阴多铁，其阳多赤金，其中多犛牛，多豹虎；漳水出焉，而东南流注于雎'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'jiaoyu'], [15, 'jiaoyu'], [20, 'jiaoyu']]],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[34, 'maoniu'], [35, 'bao'], [35, 'hu'], [37, 'lyu'], [36, 'mi'], [38, 'maoniu'], [38, 'bao']]],
];
