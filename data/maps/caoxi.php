<?php
// 曹夕之山, six hundred li south. The classic: 榖 trees below and no water; many birds and beasts, none named.
declare(strict_types=1);

return [
    'id' => 'caoxi',
    'name' => '曹夕之山',
    'range' => 12,
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
    'exits' => ['left' => ['kongsangdong', 0], 'right' => ['yigao', 0]],
    'signs' => [
        [9, 6, [
            ['say', '曹夕之山 —— 其下多榖而无水，多鸟兽'],
        ]],
    ],
];
