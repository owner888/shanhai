<?php
// 边春之山, a hundred and ten li north of 石者之山. The classic: much 葱, 葵, 韭, peach and plum; the 杠水 rises on it and
// runs west into 泑泽; the beast 幽鴳 lives there, a patterned ape that laughs and lies down when it sees someone.
declare(strict_types=1);

return [
    'id' => 'bianchun',
    'name' => '边春之山',
    'range' => 8,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T.......TT....TT...T',
        'T.......TT....TT...T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['shizhe', 0], 'right' => ['manlian', 0]],
    'signs' => [
        [9, 6, [
            ['say', '边春之山 —— 多葱、葵、韭、桃、李；杠水出焉，而西流注于泑泽'],
        ]],
    ],
    // The tall grass: 幽鴳.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 旄牛、尸鸠、橐驼、旄牛 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 2, 'slots' => [[21, 'maoniu'], [22, 'shijiu'], [22, 'tuotuo'], [23, 'maoniu'], [24, 'youyan'], [22, 'youyan'], [22, 'youyan']]],
];
