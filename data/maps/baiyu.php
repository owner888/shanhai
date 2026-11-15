<?php
// 白於之山, two hundred and fifty li west of 盂山. The classic: on it many pines and cypresses, below it many 栎 and 檀;
// its beasts are mostly 㸲牛 and 羬羊, its birds mostly owls; the 洛水 rises on its south side and runs east into the 渭,
// the 夹水 on its north and runs east into the 生水.
declare(strict_types=1);

return [
    'id' => 'baiyu',
    'name' => '白於之山',
    'range' => 7,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T.,,,,,,....~~~~~~~T',
        'T.,,,,,,....~~~~~~~T',
        'T.,,,,,,...........T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T...........,,,,,..T',
        'T...TT......,,,,,..T',
        'T...TT......,,,,,..T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['shenshou', 0], 'right' => ['yushansi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '白於之山 —— 上多松柏，下多栎檀；洛水出于其阳，而东流注于渭'],
        ]],
    ],
    // The tall grass: 㸲牛, 羬羊 and 鴞.
    'wild' => ['rate' => 10, 'slots' => [[15, 'zuoniu'], [16, 'qianyang'], [16, 'xiaoniao'], [15, 'zuoniu'], [17, 'qianyang'], [17, 'xiaoniao'], [17, 'xiaoniao']]],
];
