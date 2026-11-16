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
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 橐驼、麢、蕃、橐驼 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 2, 'slots' => [[22, 'tuotuo'], [23, 'ling'], [23, 'fan'], [24, 'tuotuo'], [25, 'jiaoniao'], [23, 'zuzi'], [22, 'jiaoniao']]],
];
