<?php
// 英山, seventy li west of 石脆之山. The classic: on it many 杻 and 橿; on its north side much iron, on its south much
// red gold; the 禺水 rises on it and runs north into the 招水, and in it is 䰷鱼; its beasts are mostly 㸲牛 and 羬羊;
// the bird 肥遗 lives there.
declare(strict_types=1);

return [
    'id' => 'yingshan',
    'name' => '英山',
    'range' => 4,
    'rows' => [
        'TTTTTTTTT~~~TTTTTTTT',
        'T.TT.....~~~....TT.T',
        'T..,,,,,.~~~.,,,,..T',
        'T..,,,,,.~~~.,,,,..T',
        'T..,,,,,..~~.,,,,..T',
        'T.........~~.......T',
        'T..................T',
        '====================',
        'T..................T',
        'T..,,,,,,..........T',
        'T..,,,,,,....TT....T',
        'T..,,,,,,....TT....T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['zhushan', 0], 'right' => ['shicui', 0]],
    'signs' => [
        [13, 6, [
            ['say', '英山 —— 其上多杻橿，其阴多铁，其阳多赤金；禺水出焉'],
        ]],
    ],
    // The 禺水: 䰷鱼.
    'water' => ['rate' => 2, 'slots' => [[20, 'bangyu'], [15, 'bangyu'], [20, 'bangyu']]],
    // The tall grass: 㸲牛, 羬羊 and the bird 肥遗.
    'wild' => ['rate' => 4, 'slots' => [[13, 'zuoniu'], [14, 'qianyang'], [15, 'feiyi'], [14, 'zuoniu'], [15, 'qianyang'], [15, 'feiyi'], [15, 'feiyi']]],
];
