<?php
// 白马之山, a hundred and eighty li north. The classic: stone and jade on its south side, iron and red copper on its north;
// the 木马之水 rises on it and runs northeast into the 虖沱. It names no beast.
declare(strict_types=1);

return [
    'id' => 'baima',
    'name' => '白马之山',
    'range' => 10,
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
    'exits' => ['left' => ['weilong', 0], 'right' => ['kongsang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '白马之山 —— 其阳多石玉，其阴多铁，多赤铜；木马之水出焉，而东北流注于虖沱'],
        ]],
    ],
];
