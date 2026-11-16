<?php
// 丰山, forty li east: the second 丰山 of the range. The classic: 封 stone, mulberry, 羊桃, which cures swellings of the skin.
// It names no beast.
declare(strict_types=1);

return [
    'id' => 'fengshaner',
    'name' => '丰山',
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
    'exits' => ['left' => ['hengshansy', 0], 'right' => ['yushanyu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '丰山 —— 其上多封石，其木多桑，多羊桃'],
        ]],
    ],
];
