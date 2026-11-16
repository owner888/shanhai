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
    'wild' => ['rate' => 10, 'slots' => [[26, 'tongtong'], [27, 'tongtong'], [28, 'tongtong'], [29, 'tongtong'], [29, 'tongtong'], [30, 'tongtong'], [30, 'tongtong']]],
];
