<?php
// 柜山, the first of the second range of the south. The classic: to its west 流黄, to its north 诸毗 is seen, to its
// east 长右; the 英水 rises on it and runs southwest into the 赤水, with much white jade and red grains in it;
// 狸力 lives there, and the bird 鴸. The one who brought the player from 箕尾之山 waits at its west end.
declare(strict_types=1);

return [
    'id' => 'guishan',
    'name' => '柜山',
    'range' => 2,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,.....,,,,,,.T',
        'T..,,,,.....,,,,,,.T',
        'T........TT.,,,,,,.T',
        'T..................T',
        'T..................T',
        'T===================',
        'T..................T',
        'T.....~~~~.........T',
        'T..~~~~~~~...,,,,,.T',
        'T~~~~~~......,,,,,.T',
        'T~~~~..............T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['right' => ['changyou', 0]],
    'signs' => [
        [9, 6, [
            ['say', '柜山 —— 英水出焉，西南流注于赤水'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回箕尾之山吗？我送你一程。累了也可以在这里歇一歇。」', [['回箕尾之山', 'go'], ['歇一歇', 'rest'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'jiwei', 12, 7, 'right'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['label', 'stay'],
        ]],
    ],
    // The tall grass: 狸力 and 鴸, the two beasts the classic gives this mountain.
    'wild' => ['rate' => 10, 'slots' => [[4, 'lili'], [5, 'zhu'], [7, 'lili'], [6, 'zhu'], [7, 'lili'], [7, 'zhu'], [7, 'lili']]],
];
