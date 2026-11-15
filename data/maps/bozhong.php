<?php
// 嶓冢之山, three hundred and twenty li west of 大时之山. The classic: the 汉水 rises on it and runs southeast into the
// 沔; the 嚻水 rises on it and runs north; its beasts are mostly 犀, 兕, 熊 and 罴, its birds mostly 白翰 and 赤鷩; the
// herb 蓇蓉 grows there.
declare(strict_types=1);

return [
    'id' => 'bozhong',
    'name' => '嶓冢之山',
    'range' => 4,
    'rows' => [
        '^^^^^^^~~^^^^^^^^^^^',
        '^......~~..........^',
        '^.,,,,.~~..,,,,,,..^',
        '^.,,,,.~~..,,,,,,..^',
        '^.,,,,..~..,,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^..,,,,,.....~~....^',
        '^..,,,,,.....~~~...^',
        '^..,,,,,......~~~..^',
        '^..............~~~.^',
        '^^^^^^^^^^^^^^^^~~^^',
    ],
    'exits' => ['left' => ['tiandi', 0], 'right' => ['dashi', 0]],
    'signs' => [
        [10, 6, [
            ['say', '嶓冢之山 —— 汉水出焉，而东南流注于沔；兽多犀兕熊罴'],
        ]],
    ],
    // The tall grass: 熊, 罴, 犀, 兕, and the birds 白翰 and 赤鷩.
    'wild' => ['rate' => 6, 'slots' => [[14, 'xiong'], [14, 'baihan'], [16, 'chibi'], [15, 'xi'], [12, 'si'], [16, 'pi'], [16, 'xiong']]],
];
