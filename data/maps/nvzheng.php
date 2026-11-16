<?php
// 女烝之山, three hundred li southeast. The classic: no grass or trees; the 石膏水 rises on it and runs west into the 鬲水, and
// in it are many 薄鱼, sturgeon with one eye, at whose sight the world has drought: met here on the scree by the water,
// being of fire (docs/elements.md 6).
declare(strict_types=1);

return [
    'id' => 'nvzheng',
    'name' => '女烝之山',
    'range' => 14,
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
        '^~~~~~~~-----------^',
        '^~~~~~~~-----------^',
        '^------------------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['dongshi', 0], 'right' => ['qinshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '女烝之山 —— 其上无草木；石膏水出焉，而西注于鬲水'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's fourteenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[5, 'boyu'], [5, 'boyu'], [8, 'boyu'], [10, 'boyu'], [10, 'boyu'], [10, 'boyu'], [10, 'boyu']]],
];
