<?php
// 咸山, seventy li northeast. The classic: jade on it, copper below, pines and cypresses, 茈草; the 条菅之水 rises on it and
// runs southwest into 长泽. It names no beast.
declare(strict_types=1);

return [
    'id' => 'xianshan',
    'name' => '咸山',
    'range' => 10,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T.............^^...T',
        'T.............^^...T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T~~~~~~~...........T',
        'T~~~~~~~...........T',
        'T..................T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['macheng', 0], 'right' => ['tianchi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '咸山 —— 其上有玉，其下多铜；条菅之水出焉，而西南流注于长泽'],
        ]],
    ],
];
