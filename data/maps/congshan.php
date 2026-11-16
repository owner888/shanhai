<?php
// 从山, thirty-five li southeast. The classic: pines and cypresses on it, bamboo below; the 从水 comes out on it and goes
// under below it, with many three-footed turtles in it, with forked tails.
declare(strict_types=1);

return [
    'id' => 'congshan',
    'name' => '从山',
    'range' => 25,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T.......~~~........T',
        'T.......~~~........T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['youxi', 0], 'right' => ['yingzhen', 0]],
    'signs' => [
        [9, 6, [
            ['say', '从山 —— 其上多松柏，其下多竹；从水出于其上，潜于其下，其中多三足鳖'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'sanzubie'], [15, 'sanzubie'], [20, 'sanzubie']]],
];
