<?php
// 条谷之山, five hundred li northeast. The classic: 槐 and 桐 trees, peony and 门冬. It names no beast.
declare(strict_types=1);

return [
    'id' => 'tiaogu',
    'name' => '条谷之山',
    'range' => 19,
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
    'exits' => ['left' => ['conglongwu', 0], 'right' => ['chaoshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '条谷之山 —— 其木多槐桐，其草多芍药、门冬'],
        ]],
    ],
];
