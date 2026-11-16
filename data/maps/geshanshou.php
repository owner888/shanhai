<?php
// 葛山之首, three hundred and eighty li south. The classic: no grass or trees; the 澧水 rises on it and runs east into 余泽, and
// in it is the 珠蟞鱼, shaped like a lung, with four eyes and six feet, which bears pearls.
declare(strict_types=1);

return [
    'id' => 'geshanshou',
    'name' => '葛山之首',
    'range' => 12,
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
    'exits' => ['left' => ['geshanwei', 0], 'right' => ['yue', 0]],
    'signs' => [
        [9, 6, [
            ['say', '葛山之首 —— 无草木；澧水出焉，东流注于余泽'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'zhubie'], [15, 'zhubie'], [20, 'zhubie']]],
];
