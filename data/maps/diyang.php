<?php
// 厎阳之山, four hundred li west of 薰吴之山. The classic: its trees are mostly 㮨, 枏 and 豫章; its beasts are mostly 犀,
// 兕, 虎, 犳 and 㸲牛.
declare(strict_types=1);

return [
    'id' => 'diyang',
    'name' => '厎阳之山',
    'range' => 5,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T.TT.,,,,,.TT.,,,,.T',
        'T.TT.,,,,,.TT.,,,,.T',
        'T....,,,,,....,,,,.T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T..TT....,,,,,..TT.T',
        'T..TT....,,,,,..TT.T',
        'T........,,,,,.....T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['zhongshou', 0], 'right' => ['xunwu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '厎阳之山 —— 其木多㮨、枏、豫章；其兽多犀、兕、虎、犳、㸲牛'],
        ]],
    ],
    // The tall grass: 犀, 兕, 虎, 犳 and 㸲牛.
    'wild' => ['rate' => 2, 'slots' => [[16, 'xi'], [16, 'si'], [16, 'hu'], [16, 'zhuo'], [15, 'zuoniu'], [15, 'zhuo'], [15, 'hu']]],
];
