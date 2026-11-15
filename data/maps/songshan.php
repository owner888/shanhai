<?php
// 松山, a hundred and twenty li north. The classic: the 阳水 rises on it and runs northeast into the 河. It names no beast.
declare(strict_types=1);

return [
    'id' => 'songshan',
    'name' => '松山',
    'range' => 10,
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
    'exits' => ['left' => ['xiushan', 0], 'right' => ['dunyu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '松山 —— 阳水出焉，东北流注于河'],
        ]],
    ],
];
