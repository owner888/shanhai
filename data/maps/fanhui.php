<?php
// 繁缋之山, fifty li southwest. The classic: 楢 and 杻, the herb 枝勾. It names no beast.
declare(strict_types=1);

return [
    'id' => 'fanhui',
    'name' => '繁缋之山',
    'range' => 24,
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
    'exits' => ['left' => ['huwei', 0], 'right' => ['yongshi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '繁缋之山 —— 其木多楢杻，其草多枝勾'],
        ]],
    ],
];
