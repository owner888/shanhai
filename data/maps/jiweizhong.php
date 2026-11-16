<?php
// 箕尾之山, two hundred li west (`jiwei` is the 箕尾之山 of the south). The classic: 榖 trees, 涂 stone, 㻬琈 jade. It names no
// beast.
declare(strict_types=1);

return [
    'id' => 'jiweizhong',
    'name' => '箕尾之山',
    'range' => 18,
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
    'exits' => ['left' => ['lishanzhong', 0], 'right' => ['bingshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '箕尾之山 —— 多榖，多涂石，其上多㻬琈之玉'],
        ]],
    ],
];
