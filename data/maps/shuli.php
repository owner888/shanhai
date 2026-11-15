<?php
// 数历之山, a hundred and seventy li west of 泰冒之山. The classic: on it much gold, below it much silver; its trees are
// mostly 杻 and 橿, its birds mostly 鹦䳇; the 楚水 rises on it and runs south into the 渭, with white pearls in it.
declare(strict_types=1);

return [
    'id' => 'shuli',
    'name' => '数历之山',
    'range' => 5,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T.TT.,,,,,..TT.....T',
        'T.TT.,,,,,..TT.....T',
        'T....,,,,,.........T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T......~~....,,,,..T',
        'T......~~....,,,,..T',
        'T......~~~...,,,,..T',
        'T.......~~.........T',
        'TTTTTTTT~~TTTTTTTTTT',
    ],
    'exits' => ['left' => ['gaoshan', 0], 'right' => ['taimao', 0]],
    'signs' => [
        [12, 6, [
            ['say', '数历之山 —— 其上多黄金，其下多银；楚水出焉，南流注于渭'],
        ]],
    ],
    // The tall grass: 鹦䳇. Its levels are those of the first area before the reference's fifth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[16, 'yingwu'], [16, 'yingwu'], [16, 'yingwu'], [16, 'yingwu'], [13, 'yingwu'], [13, 'yingwu'], [13, 'yingwu']]],
];
