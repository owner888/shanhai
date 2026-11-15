<?php
// 泰戏之山, three hundred li north. The classic: no grass or trees, gold and jade; the beast 䍶䍶 lives there, a sheep with
// one horn and one eye, the eye behind its ear; the 虖沱之水 rises on it and runs east.
declare(strict_types=1);

return [
    'id' => 'taixi',
    'name' => '泰戏之山',
    'range' => 10,
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
        '^-----------~~~~~~~^',
        '^-----------~~~~~~~^',
        '^------------------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['kongsang', 0], 'right' => ['shishanbei', 0]],
    'signs' => [
        [9, 6, [
            ['say', '泰戏之山 —— 无草木，多金玉；虖沱之水出焉，而东流注于溇水'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the three areas before the reference's tenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[17, 'dongdong'], [17, 'dongdong'], [18, 'dongdong'], [18, 'dongdong'], [18, 'dongdong'], [16, 'dongdong'], [16, 'dongdong']]],
];
