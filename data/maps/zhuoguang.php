<?php
// 涿光之山, three hundred and fifty li north of 谯明之山. The classic: the 嚻水 rises on it and runs west into the 河, and
// in it is the fish 鳛鳛, a magpie's shape with ten wings; on it many pines and cypresses; its beasts are mostly 麢羊,
// its birds mostly 蕃.
declare(strict_types=1);

return [
    'id' => 'zhuoguang',
    'name' => '涿光之山',
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
        'T~~~~~~~~..........T',
        'T~~~~~~~~..........T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['qiaoming', 0], 'right' => ['guoshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '涿光之山 —— 嚻水出焉，而西流注于河；其上多松柏，其下多棕枏'],
        ]],
    ],
    // The 嚻水: 鳛鳛之鱼.
    'water' => ['rate' => 2, 'slots' => [[20, 'xixi'], [15, 'xixi'], [20, 'xixi']]],
    // The tall grass: 麢 and 蕃.
    'wild' => ['rate' => 2, 'slots' => [[22, 'ling'], [23, 'fan'], [23, 'ling'], [24, 'fan'], [25, 'ling'], [23, 'fan'], [22, 'ling']]],
];
