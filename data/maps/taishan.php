<?php
// 泰山, three hundred li south. The classic: jade on it, gold below; the beast 狪狪 lives there, a pig with a pearl in it;
// the 环水 rises on it and runs east, with much crystal in it.
declare(strict_types=1);

return [
    'id' => 'taishan',
    'name' => '泰山',
    'range' => 11,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^...........~~~~~~~^',
        '^...........~~~~~~~^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['dushan', 0], 'right' => ['zhushandong', 0]],
    'signs' => [
        [9, 6, [
            ['say', '泰山 —— 其上多玉，其下多金；环水出焉，东流注于汶'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the three areas before the reference's eleventh gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 虎、麋、鹿、虎 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[26, 'hu'], [27, 'mi'], [28, 'yelu'], [29, 'hu'], [29, 'tongtong'], [30, 'tongtong'], [30, 'tongtong']]],
];
