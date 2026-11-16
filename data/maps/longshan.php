<?php
// 龙山, seventy li northeast. The classic: mistletoe, 碧 on it, red tin below. It names no beast.
declare(strict_types=1);

return [
    'id' => 'longshan',
    'name' => '龙山',
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
    'exits' => ['left' => ['lingshan', 0], 'right' => ['hengshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '龙山 —— 上多寓木，其上多碧，其下多赤锡'],
        ]],
    ],
];
