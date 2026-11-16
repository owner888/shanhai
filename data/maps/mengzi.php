<?php
// 孟子之山, seven hundred li south by water. The classic: 梓 and 桐, peach and plum, 菌 and 蒲; its beasts are mostly elk and
// deer; a hundred li round; a water called 碧阳 comes out on it, with many 鳣 and 鲔 in it.
declare(strict_types=1);

return [
    'id' => 'mengzi',
    'name' => '孟子之山',
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
    'exits' => ['left' => ['hushe', 0], 'right' => ['qizhong', 0]],
    'signs' => [
        [9, 6, [
            ['say', '孟子之山 —— 其木多梓桐，多桃李，其草多菌蒲，其兽多麋鹿；有水出焉，名曰碧阳'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'zhan'], [15, 'wei'], [20, 'zhan']]],
    // Its wild ground. The range's levels are those of the areas before the reference's thirteenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 6, 'slots' => [[10, 'mi'], [11, 'yelu'], [12, 'mi'], [12, 'yelu'], [15, 'mi'], [12, 'yelu'], [12, 'mi']]],
];
