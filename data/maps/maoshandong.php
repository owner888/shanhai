<?php
// 旄山, three hundred li south (`maoshan` is the 旄山 of the south). The classic: no grass or trees; the 苍体之水 rises on it
// and runs west into the 展水, with many 鱃鱼 in it, carp with great heads.
declare(strict_types=1);

return [
    'id' => 'maoshandong',
    'name' => '旄山',
    'range' => 14,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^-------------^^---^',
        '^-------------^^---^',
        '^------------------^',
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
    'exits' => ['left' => ['beihao', 0], 'right' => ['dongshi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '旄山 —— 无草木；苍体之水出焉，而西流注于展水'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'xiuyu'], [15, 'xiuyu'], [20, 'xiuyu']]],
];
