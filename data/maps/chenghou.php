<?php
// 成侯之山, five hundred li east. The classic: 櫄 trees, 芃 herbs. It names no beast.
declare(strict_types=1);

return [
    'id' => 'chenghou',
    'name' => '成侯之山',
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
    'exits' => ['left' => ['chaoshan', 0], 'right' => ['zhaoge', 0]],
    'signs' => [
        [9, 6, [
            ['say', '成侯之山 —— 其上多櫄木，其草多芃'],
        ]],
    ],
];
