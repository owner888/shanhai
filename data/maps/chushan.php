<?php
// 楮山, thirty li west. The classic: mistletoe, pepper, 椐 and 柘, clay. It names no beast.
declare(strict_types=1);

return [
    'id' => 'chushan',
    'name' => '楮山',
    'range' => 24,
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
    'exits' => ['left' => ['fuzhou', 0], 'right' => ['youyuan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '楮山 —— 多寓木，多椒椐，多柘，多垩'],
        ]],
    ],
];
