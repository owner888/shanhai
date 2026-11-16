<?php
// 葛山之尾, five hundred li south by water and three hundred over drifting sand. The classic: no grass or trees, much
// whetstone. It names no beast.
declare(strict_types=1);

return [
    'id' => 'geshanwei',
    'name' => '葛山之尾',
    'range' => 12,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^---^^^------------^',
        '^---^^^------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^------------------^',
        '^-----------^^^----^',
        '^-----------^^^----^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['yigao', 0], 'right' => ['geshanshou', 0]],
    'signs' => [
        [9, 6, [
            ['say', '葛山之尾 —— 无草木，多砥砺'],
        ]],
    ],
];
