<?php
// 卑山, forty li east. The classic: peach, plum, 苴 and 梓, and vines. It names no beast.
declare(strict_types=1);

return [
    'id' => 'beishansy',
    'name' => '卑山',
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
    'exits' => ['left' => ['dashu', 0], 'right' => ['yidi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '卑山 —— 其上多桃李苴梓，多累'],
        ]],
    ],
];
