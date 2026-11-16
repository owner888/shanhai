<?php
// 涿山, fifty li west. The classic: 榖, 柞 and 杻; 㻬琈 jade on its south side. It names no beast.
declare(strict_types=1);

return [
    'id' => 'zhuoshan',
    'name' => '涿山',
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
    'exits' => ['left' => ['youyuan', 0], 'right' => ['bingshanshi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '涿山 —— 其木多榖柞杻，其阳多㻬琈之玉'],
        ]],
    ],
];
