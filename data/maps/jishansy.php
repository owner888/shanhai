<?php
// 鸡山, forty li southeast. The classic: fine 梓, mulberry, leeks. It names no beast.
declare(strict_types=1);

return [
    'id' => 'jishansy',
    'name' => '鸡山',
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
    'exits' => ['left' => ['jigu', 0], 'right' => ['gaoqian', 0]],
    'signs' => [
        [9, 6, [
            ['say', '鸡山 —— 其上多美梓，多桑，其草多韭'],
        ]],
    ],
];
