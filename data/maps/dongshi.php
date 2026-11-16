<?php
// 东始之山, three hundred and twenty li south. The classic: grey jade on it; a tree like a poplar with red veins, whose sap
// is like blood, called 芑; the 泚水 rises on it and runs northeast into the sea, with many fine shells and 茈鱼 in it, which
// has one head and ten bodies.
declare(strict_types=1);

return [
    'id' => 'dongshi',
    'name' => '东始之山',
    'range' => 14,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T...^^.............T',
        'T...^^.............T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T...........~~~~~~~T',
        'T...TT......~~~~~~~T',
        'T...TT.............T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['maoshandong', 0], 'right' => ['nvzheng', 0]],
    'signs' => [
        [9, 6, [
            ['say', '东始之山 —— 上多苍玉；泚水出焉，而东北流注于海'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'ciyu'], [15, 'meibei'], [20, 'ciyu']]],
];
