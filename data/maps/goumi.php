<?php
// 勾檷之山, three hundred li east; one of the range's 冢. The classic: jade on it, gold below; oak and 柘, peony. It names no
// beast.
declare(strict_types=1);

return [
    'id' => 'goumi',
    'name' => '勾檷之山',
    'range' => 23,
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
    'exits' => ['left' => ['qishanjiu', 0], 'right' => ['fengyu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '勾檷之山 —— 其上多玉，其下多黄金，其木多栎柘，其草多芍药'],
        ]],
    ],
];
