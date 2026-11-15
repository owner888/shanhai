<?php
// 浮玉之山, five hundred li east of 句余之山. The classic: to its north the lake 具区 is seen; 彘 lives there, which
// eats people; the 苕水 rises on its north side and runs north into 具区, and in it are many 鮆鱼.
declare(strict_types=1);

return [
    'id' => 'fuyu',
    'name' => '浮玉之山',
    'range' => 2,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T~~~~~~~~~~~~~~~~~~T',
        'T~~~~~~~~~~~~~~~~~~T',
        'T.,,,,,.~~~..,,,,,.T',
        'T.,,,,,.~~~..,,,,,.T',
        'T.,,,,,......,,,,,.T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T...,,,,,...TT.....T',
        'T...,,,,,...TT.....T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['gouyu', 0], 'right' => ['chengshan', 0]],
    'signs' => [
        [11, 6, [
            ['say', '浮玉之山 —— 北望具区；苕水出于其阴'],
        ]],
    ],
    // The 苕水: 鮆鱼.
    'water' => ['rate' => 6, 'slots' => [[4, 'jiyu'], [5, 'jiyu'], [4, 'jiyu']]],
    // The tall grass: 彘.
    'wild' => ['rate' => 10, 'slots' => [[6, 'zhi'], [6, 'zhi'], [6, 'zhi'], [6, 'zhi'], [7, 'zhi'], [7, 'zhi'], [7, 'zhi']]],
];
