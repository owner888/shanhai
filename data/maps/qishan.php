<?php
// 岐山, eight hundred li south by water. The classic: peach and plum trees; its beasts are mostly tigers.
declare(strict_types=1);

return [
    'id' => 'qishan',
    'name' => '岐山',
    'range' => 13,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
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
    'exits' => ['left' => ['shihu', 0], 'right' => ['zhugou', 0]],
    'signs' => [
        [9, 6, [
            ['say', '岐山 —— 其木多桃李，其兽多虎'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's thirteenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[15, 'hu'], [17, 'hu'], [15, 'hu'], [15, 'hu'], [15, 'hu'], [16, 'hu'], [16, 'hu']]],
];
