<?php
// 衡山, fifty li southeast. The classic: mistletoe, 榖 and 柞; yellow and white clay. It names no beast.
declare(strict_types=1);

return [
    'id' => 'hengshan',
    'name' => '衡山',
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
    'exits' => ['left' => ['longshan', 0], 'right' => ['shishanba', 0]],
    'signs' => [
        [9, 6, [
            ['say', '衡山 —— 上多寓木、榖柞，多黄垩、白垩'],
        ]],
    ],
];
