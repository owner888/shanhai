<?php
// 婴䃌之山, thirty li southeast. The classic: pines and cypresses on it, 梓 and 櫄 below. It names no beast.
declare(strict_types=1);

return [
    'id' => 'yingzhen',
    'name' => '婴䃌之山',
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
    'exits' => ['left' => ['congshan', 0], 'right' => ['bishansy', 0]],
    'signs' => [
        [9, 6, [
            ['say', '婴䃌之山 —— 其上多松柏，其下多梓櫄'],
        ]],
    ],
];
