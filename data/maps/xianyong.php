<?php
// 县雍之山, fifty li north of 少阳之山. The classic: on it much jade, below it much copper; its beasts are mostly 闾 and
// 麋, its birds 白翟 and 白䳑; the 晋水 rises on it and runs southeast into the 汾水, and in it are many 鮆鱼.
declare(strict_types=1);

return [
    'id' => 'xianyong',
    'name' => '县雍之山',
    'range' => 9,
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
        '^...........~~~~~~~^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['shaoyang', 0], 'right' => ['huqi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '县雍之山 —— 其上多玉，其下多铜；晋水出焉，而东南流注于汾水'],
        ]],
    ],
    // The 晋水: 鮆鱼.
    'water' => ['rate' => 2, 'slots' => [[20, 'jiyu'], [15, 'jiyu'], [20, 'jiyu']]],
    // The tall grass: 闾, 麋, 白翟 and 白䳑. The range's levels are those of the five areas before the reference's ninth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[28, 'lyu'], [28, 'mi'], [30, 'baidi'], [30, 'baiyou'], [32, 'lyu'], [30, 'mi'], [30, 'baidi']]],
];
