<?php
// 虢山, three hundred and eighty li north of 涿光之山. The classic: on it many 漆, below it 桐 and 椐; on its south side
// much jade, on its north much iron; the 伊水 rises on it and runs west into the 河; its beasts are mostly camels, its
// birds mostly 寓, a rat's shape with a bird's wings.
declare(strict_types=1);

return [
    'id' => 'guoshan',
    'name' => '虢山',
    'range' => 8,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,.....TT....T',
        'T..,,,,,.....TT....T',
        'T..,,,,,...........T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T..........,,,,,...T',
        'T..........,,,,,...T',
        'T..........,,,,,...T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['zhuoguang', 0], 'right' => ['guowei', 0]],
    'signs' => [
        [9, 6, [
            ['say', '虢山 —— 其上多漆，其下多桐椐；其兽多橐驼，其鸟多寓'],
        ]],
    ],
    // The tall grass: 橐驼 and 寓.
    'wild' => ['rate' => 2, 'slots' => [[23, 'tuotuo'], [24, 'yuniao'], [24, 'tuotuo'], [25, 'yuniao'], [26, 'tuotuo'], [22, 'yuniao'], [24, 'tuotuo']]],
];
