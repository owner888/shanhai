<?php
// 龟山, a hundred and thirty li southeast (`guishan` is the 騩山 of the west). The classic: 榖, 柞, 椆 and 椐; gold on it, 青 and
// realgar below; much 扶竹. It names no beast.
declare(strict_types=1);

return [
    'id' => 'guishanse',
    'name' => '龟山',
    'range' => 26,
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
    'exits' => ['left' => ['yunshan', 0], 'right' => ['bingshanse', 0]],
    'signs' => [
        [9, 6, [
            ['say', '龟山 —— 其木多榖柞椆椐，其上多黄金，其下多青、雄黄，多扶竹'],
        ]],
    ],
];
