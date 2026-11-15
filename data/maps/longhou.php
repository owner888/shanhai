<?php
// 龙侯之山, two hundred li northeast. The classic: no grass or trees, much gold and jade; the 决决之水 rises on it and runs east
// into the 河, with many 人鱼 in it.
declare(strict_types=1);

return [
    'id' => 'longhou',
    'name' => '龙侯之山',
    'range' => 10,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^---^^-------------^',
        '^---^^-------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^-----------~~~~~~~^',
        '^-----------~~~~~~~^',
        '^------------------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['guishanbei', 0], 'right' => ['macheng', 0]],
    'signs' => [
        [9, 6, [
            ['say', '龙侯之山 —— 无草木，多金玉；决决之水出焉，而东流注于河'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'renyu'], [15, 'renyu'], [20, 'renyu']]],
];
