<?php
// 皮山, sixty li east. The classic: clay and ochre, pines and cypresses. It names no beast.
declare(strict_types=1);

return [
    'id' => 'pishan',
    'name' => '皮山',
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
    'exits' => ['left' => ['tuchuang', 0], 'right' => ['yaobi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '皮山 —— 多垩，多赭，其木多松柏'],
        ]],
    ],
];
