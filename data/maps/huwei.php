<?php
// 虎尾之山, fifty li west. The classic: pepper and 椐, 封 stone; red gold on its south side, iron on its north. It names no
// beast.
declare(strict_types=1);

return [
    'id' => 'huwei',
    'name' => '虎尾之山',
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
    'exits' => ['left' => ['shouyang', 0], 'right' => ['fanhui', 0]],
    'signs' => [
        [9, 6, [
            ['say', '虎尾之山 —— 其木多椒椐，多封石，其阳多赤金，其阴多铁'],
        ]],
    ],
];
