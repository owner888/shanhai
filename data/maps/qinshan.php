<?php
// 钦山, two hundred li southeast. The classic: gold and jade and no stone; the 师水 rises on it and runs north into 皋泽, with
// many 鱃鱼 and patterned shells in it; the beast 当康 lives there, a pig with tusks, at whose sight the world has a great
// harvest.
declare(strict_types=1);

return [
    'id' => 'qinshan',
    'name' => '钦山',
    'range' => 14,
    'rows' => [
        '^^^^^^^^^~~^^^^^^^^^',
        '^........~~........^',
        '^..,,,,,.~~.,,,,,..^',
        '^..,,,,,.~~.,,,,,..^',
        '^..,,,,,.~~.,,,,,..^',
        '^........~~........^',
        '^..................^',
        '====================',
        '^..................^',
        '^..,,,,,...........^',
        '^..,,,,,...........^',
        '^..,,,,,...........^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['nvzheng', 0], 'right' => ['zitong', 0]],
    'signs' => [
        [12, 6, [
            ['say', '钦山 —— 多金玉而无石；师水出焉，而北流注于皋泽'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'xiuyu'], [15, 'wenbei'], [20, 'xiuyu']]],
    // Its wild ground. The range's levels are those of the areas before the reference's fourteenth gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 虎、麋、鹿、虎 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 6, 'slots' => [[6, 'hu'], [8, 'mi'], [8, 'yelu'], [12, 'hu'], [10, 'dangkang'], [8, 'dangkang'], [8, 'dangkang']]],
];
