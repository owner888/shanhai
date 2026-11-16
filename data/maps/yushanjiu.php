<?php
// 玉山, two hundred li northeast (the fourth of the name or its sound). The classic: copper on its south side, red gold on
// its north; 豫章, 楢 and 杻; its beasts are mostly boar, deer, 麢 and 㚟; its birds 鸩.
declare(strict_types=1);

return [
    'id' => 'yushanjiu',
    'name' => '玉山',
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
    'exits' => ['left' => ['fengyu', 0], 'right' => ['xiongshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '玉山 —— 其阳多铜，其阴多赤金，其木多豫章楢杻'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[42, 'yeshi'], [43, 'yelu'], [43, 'ling'], [45, 'chuotu'], [44, 'zhenniao'], [46, 'yeshi'], [46, 'yelu']]],
];
