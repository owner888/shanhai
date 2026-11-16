<?php
// 真陵之山, two hundred li east. The classic: gold on it, jade below; 榖, 柞, willow and 杻; the herb 荣草. It names no beast.
declare(strict_types=1);

return [
    'id' => 'zhenling',
    'name' => '真陵之山',
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
    'exits' => ['left' => ['jiangfu', 0], 'right' => ['yangdi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '真陵之山 —— 其上多黄金，其下多玉，其木多榖柞柳杻'],
        ]],
    ],
];
