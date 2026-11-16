<?php
// 前山, two hundred li southeast (`qianshan` is 钤山, of the west). The classic: 槠 and cypress; gold on its south side,
// ochre on its north. It names no beast.
declare(strict_types=1);

return [
    'id' => 'qianshansy',
    'name' => '前山',
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
    'exits' => ['left' => ['shishanshi', 0], 'right' => ['fengshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '前山 —— 其木多槠，多柏；其阳多金，其阴多赭'],
        ]],
    ],
];
