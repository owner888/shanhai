<?php
// 历山, ten li east. The classic: 槐 trees, jade on its south side. It names no beast.
declare(strict_types=1);

return [
    'id' => 'lishanwu',
    'name' => '历山',
    'range' => 19,
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
    'exits' => ['left' => ['huaishan', 0], 'right' => ['shishanwu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '历山 —— 其木多槐，其阳多玉'],
        ]],
    ],
];
