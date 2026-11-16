<?php
// 声匈之山, fifty li east. The classic: 榖 trees, jade, 封 stone. It names no beast.
declare(strict_types=1);

return [
    'id' => 'shengxiong',
    'name' => '声匈之山',
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
    'exits' => ['left' => ['ouwu', 0], 'right' => ['daguisy', 0]],
    'signs' => [
        [9, 6, [
            ['say', '声匈之山 —— 其木多榖，多玉，上多封石'],
        ]],
    ],
];
