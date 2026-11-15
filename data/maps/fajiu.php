<?php
// 发鸠之山, two hundred li north. The classic: many 柘 trees; the bird 精卫 lives there, a crow with a patterned head, a white
// beak and red feet: 炎帝's youngest daughter, drowned in the Eastern Sea, who carries wood and stone from the western
// hills to fill it; the 漳水 rises on it and runs east into the 河.
declare(strict_types=1);

return [
    'id' => 'fajiu',
    'name' => '发鸠之山',
    'range' => 10,
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
    'exits' => ['left' => ['shenqun', 0], 'right' => ['shaoshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '发鸠之山 —— 其上多柘木；漳水出焉，东流注于河'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the three areas before the reference's tenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[13, 'jingwei'], [13, 'jingwei'], [15, 'jingwei'], [12, 'jingwei'], [14, 'jingwei'], [14, 'jingwei'], [14, 'jingwei']]],
];
