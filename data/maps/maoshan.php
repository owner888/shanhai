<?php
// 旄山之尾, the tail of 旄山, four hundred li east of 发爽之山. The classic: to its south is a valley, 育遗, with many
// strange birds; the south wind, 凯风, comes out of it. It names none of the birds.
declare(strict_types=1);

return [
    'id' => 'maoshan',
    'name' => '旄山之尾',
    'range' => 3,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^..........^^^^^^^',
        '^..............^^^^^',
        '^..T...........T...^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^^^^^^......^^^^^^^^',
        '^^^^^^......^^^^^^^^',
        '^^^^^^^....^^^^^^^^^',
        '^^^^^^^....^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['fashuang', 0], 'right' => ['feishan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '旄山之尾 —— 其南有谷，曰育遗'],
        ]],
        [7, 12, [
            ['say', '育遗之谷 —— 多怪鸟，凯风自是出'],
        ]],
    ],
];
