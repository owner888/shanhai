<?php
// 峚山, four hundred and twenty li northwest of 不周之山. The classic: on it many 丹木, with round leaves and red stems;
// the 丹水 rises on it and runs west into 稷泽, with much white jade in it, and a fluid of jade wells up there. It
// names no beast.
declare(strict_types=1);

return [
    'id' => 'mishan',
    'name' => '峚山',
    'range' => 6,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T.......TT.........T',
        'T.......TT...TT....T',
        'T............TT....T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        '~~~~~~~~...........T',
        '~~~~~~~~...........T',
        'T..................T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['zhongshan', 0], 'right' => ['buzhou', 0]],
    'signs' => [
        [10, 6, [
            ['say', '峚山 —— 其上多丹木；丹水出焉，西流注于稷泽，其中多白玉，是有玉膏'],
            ['ifFlag', 'found_danmu', 'had'],
            ['setFlag', 'found_danmu'],
            ['give', 'danmu', 1],
            ['say', '牌子旁边长着丹木。你采了一份。'],
            ['label', 'had'],
        ]],
    ],
];
