<?php
// 讲山, thirty li north. The classic: jade, 柘 and cypress; a tree called 帝屋, which keeps off harm. It names no beast.
declare(strict_types=1);

return [
    'id' => 'jiangshan',
    'name' => '讲山',
    'range' => 21,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T...^^^............T',
        'T...^^^............T',
        'T..................T',
        'T..............TT..T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T...........^^^....T',
        'T...........^^^....T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['taishi', 0], 'right' => ['yingliang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '讲山 —— 其上多玉，多柘，多柏'],
            ['ifFlag', 'found_diwu', 'had'],
            ['setFlag', 'found_diwu'],
            ['give', 'diwu', 1],
            ['say', '牌子旁边长着帝屋。你采了一份。'],
            ['label', 'had'],
        ]],
    ],
];
