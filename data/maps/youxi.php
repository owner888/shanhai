<?php
// 游戏之山, thirty li southeast. The classic: 杻, 橿 and 榖; jade and 封 stone. It names no beast.
declare(strict_types=1);

return [
    'id' => 'youxi',
    'name' => '游戏之山',
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
    'exits' => ['left' => ['gaoqian', 0], 'right' => ['congshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '游戏之山 —— 多杻橿榖，多玉，多封石'],
        ]],
    ],
];
