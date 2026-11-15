<?php
// 上申之山, twenty li north of 鸟山. The classic: on it no grass or trees and much 硌石, below it many 榛 and 楛; its
// beasts are mostly white deer; its birds mostly 当扈, a pheasant's shape, which flies by its beard; the 汤水 rises on
// it and runs east into the 河. With no grass above, they are met on the scree.
declare(strict_types=1);

return [
    'id' => 'shangshen',
    'name' => '上申之山',
    'range' => 7,
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
        '^------------------^',
        '^---TT-------TT----^',
        '^---TT-------TT----^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['zhuci', 0], 'right' => ['niaoshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '上申之山 —— 上无草木，而多硌石，下多榛楛；兽多白鹿'],
        ]],
    ],
    // The scree: 白鹿 and 当扈. The range's levels are those of the four areas before the reference's seventh gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[13, 'bailu'], [14, 'danghu'], [15, 'bailu'], [16, 'danghu'], [15, 'bailu'], [16, 'danghu'], [16, 'bailu']]],
];
