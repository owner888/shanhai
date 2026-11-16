<?php
// 峄皋之山, four hundred li southwest. The classic: gold and jade on it, white clay below; the 峄皋之水 rises on it and runs
// east into the 激女之水, with many 蜃珧 in it.
declare(strict_types=1);

return [
    'id' => 'yigao',
    'name' => '峄皋之山',
    'range' => 12,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^...^^.............^',
        '^...^^.............^',
        '^..................^',
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
    'exits' => ['left' => ['caoxi', 0], 'right' => ['geshanwei', 0]],
    'signs' => [
        [9, 6, [
            ['say', '峄皋之山 —— 其上多金玉，其下多白垩；峄皋之水出焉，东流注于激女之水'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'shenyao'], [15, 'shenyao'], [20, 'shenyao']]],
];
