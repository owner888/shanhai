<?php
// 历儿之山, twenty li east. The classic: 橿 and 櫔 trees, whose fruit keeps one from forgetting. It is the range's 冢, the hill
// its rites are greatest at. It names no beast.
declare(strict_types=1);

return [
    'id' => 'lier',
    'name' => '历儿之山',
    'range' => 15,
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
    'exits' => ['left' => ['ganzao', 0], 'right' => ['quzhu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '历儿之山 —— 其上多橿，多櫔木'],
        ]],
    ],
];
