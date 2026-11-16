<?php
// 雅山, thirty li east. The classic: the 澧水 rises on it and runs east into the 瀙, with many great fish in it, of which it
// says no more; fine mulberry on it, 苴 below, red gold.
declare(strict_types=1);

return [
    'id' => 'yashan',
    'name' => '雅山',
    'range' => 25,
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
    'exits' => ['left' => ['nishan', 0], 'right' => ['xuanshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '雅山 —— 澧水出焉，东流注于瀙水，其中多大鱼；其上多美桑，其下多苴，多赤金'],
        ]],
    ],
];
