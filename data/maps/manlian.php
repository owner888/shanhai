<?php
// 蔓联之山, two hundred li north of 边春之山. The classic: no grass or trees; the beast 足訾 lives there, a maned ape with
// an ox's tail and a horse's hooves; and the bird 䴔, which lives and flies in flocks.
declare(strict_types=1);

return [
    'id' => 'manlian',
    'name' => '蔓联之山',
    'range' => 8,
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
        '^-------:::::------^',
        '^-------:::::------^',
        '^-------:::::------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['bianchun', 0], 'right' => ['danzhang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '蔓联之山 —— 其上无草木'],
        ]],
    ],
    // The scree: 足訾 and 䴔.
    'wild' => ['rate' => 2, 'slots' => [[22, 'zuzi'], [23, 'jiaoniao'], [23, 'zuzi'], [24, 'jiaoniao'], [25, 'zuzi'], [23, 'jiaoniao'], [22, 'zuzi']]],
];
