<?php
// 柴桑之山, ninety li south. The classic: silver on it, green jade below, 汵 stone and ochre; willow, 芑, 楮 and mulberry; its
// beasts are mostly elk and deer; many white snakes and flying snakes.
declare(strict_types=1);

return [
    'id' => 'chaisang',
    'name' => '柴桑之山',
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
    'exits' => ['left' => ['yangdi', 0], 'right' => ['rongyu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '柴桑之山 —— 其上多银，其下多碧，多汵石、赭，其兽多麋鹿，多白蛇、飞蛇'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[39, 'mi'], [40, 'yelu'], [40, 'baishe'], [42, 'feishe'], [41, 'mi'], [43, 'yelu'], [43, 'baishe']]],
];
