<?php
// 杻阳之山, three hundred and seventy li east of 猨翼之山. The classic: on its south side much red gold, on its north
// much white gold; 鹿蜀 lives there; the 怪水 rises on it and runs east into the 宪翼之水, and in it are many 旋龟.
declare(strict_types=1);

return [
    'id' => 'niuyang',
    'name' => '杻阳之山',
    'range' => 1,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^..,,,..^^^..,,,.^^',
        '^..,,,,...^...,,,,.^',
        '^..,,,.........,,,.^',
        'T.......~~~~.......T',
        'T.....~~~~~~~~~~~~~~',
        'T......~~~.........T',
        '====================',
        'T..................T',
        'T..,,,....^....,,..T',
        'T.,,,,...^^^..,,,,.T',
        '^..,,...^^^^^..,,..^',
        '^^.....^^^^^^^....^^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['yuanyi', 0], 'right' => ['dishan', 0]],
    'signs' => [
        [9, 8, [
            ['say', '杻阳之山 —— 阳多赤金，阴多白金；怪水东流'],
        ]],
    ],
    // The tall grass: 鹿蜀, the mountain's one beast of the land; seldom met.
    // The 怪水: 旋龟. Seldom met, as 鹿蜀 is.
    'water' => ['rate' => 2, 'slots' => [[20, 'xuangui'], [15, 'xuangui'], [20, 'xuangui']]],
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 蝮虫、白猿、蝮虫、白猿 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 4, 'slots' => [[3, 'fuchong'], [4, 'baiyuan'], [5, 'fuchong'], [3, 'baiyuan'], [6, 'lushu'], [5, 'lushu'], [5, 'lushu']]],
];
