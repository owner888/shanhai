<?php
// 兔床之山, eight hundred li northeast. The classic: iron on its south side; 槠 and 芧; the herb 鸡谷, whose root is like a
// hen's egg. It names no beast.
declare(strict_types=1);

return [
    'id' => 'tuchuang',
    'name' => '兔床之山',
    'range' => 25,
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
    'exits' => ['left' => ['fengshan', 0], 'right' => ['pishan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '兔床之山 —— 其阳多铁，其木多槠芧，其草多鸡谷'],
            ['ifFlag', 'found_jigu', 'had'],
            ['setFlag', 'found_jigu'],
            ['give', 'jigu', 1],
            ['say', '牌子旁边长着鸡谷。你采了一份。'],
            ['label', 'had'],
        ]],
    ],
];
