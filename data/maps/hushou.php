<?php
// 虎首之山, thirty li east. The classic: 苴, 椆 and 椐. It names no beast.
declare(strict_types=1);

return [
    'id' => 'hushou',
    'name' => '虎首之山',
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
    'exits' => ['left' => ['yingshansy', 0], 'right' => ['yinghou', 0]],
    'signs' => [
        [9, 6, [
            ['say', '虎首之山 —— 多苴椆椐'],
        ]],
    ],
];
