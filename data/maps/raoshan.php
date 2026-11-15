<?php
// 饶山, five hundred li by land and five hundred by water north. The classic: no grass or trees, much 瑶 and 碧; its beasts
// are mostly camels, its birds 鹠; the 历虢之水 rises on it and runs east into the 河, and in it is 师鱼, which kills who eats
// it.
declare(strict_types=1);

return [
    'id' => 'raoshan',
    'name' => '饶山',
    'range' => 10,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^--:::::----:::::--^',
        '^--:::::----:::::--^',
        '^--:::::----:::::--^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^-----------~~~~~~~^',
        '^-----------~~~~~~~^',
        '^------------------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['yanshan', 0], 'right' => ['qianshanbei', 0]],
    'signs' => [
        [9, 6, [
            ['say', '饶山 —— 是无草木，多瑶碧；其兽多橐驼，其鸟多鹠'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'shiyu'], [15, 'shiyu'], [20, 'shiyu']]],
    // Its wild ground. The range's levels are those of the three areas before the reference's tenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[17, 'tuotuo'], [19, 'liu'], [16, 'tuotuo'], [17, 'liu'], [16, 'tuotuo'], [18, 'liu'], [18, 'tuotuo']]],
];
