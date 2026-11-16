<?php
// 彘山, a hundred and twenty li southeast. The classic: fine stone and 柘. It names no beast.
declare(strict_types=1);

return [
    'id' => 'zhishan',
    'name' => '彘山',
    'range' => 22,
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
    'exits' => ['left' => ['ruoshan', 0], 'right' => ['yushanba', 0]],
    'signs' => [
        [9, 6, [
            ['say', '彘山 —— 多美石，多柘'],
        ]],
    ],
];
