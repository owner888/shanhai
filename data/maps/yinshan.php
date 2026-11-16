<?php
// 阴山, three hundred li west of 章莪之山. The classic: the 浊浴之水 rises on it and runs south into 蕃泽, with many
// patterned shells in it; the beast 天狗 lives there, which keeps ill away.
declare(strict_types=1);

return [
    'id' => 'yinshan',
    'name' => '阴山',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^.........,,,,,,...^',
        '^.........,,,,,,...^',
        '^.........,,,,,,...^',
        '^..................^',
        '^..................^',
        '====================',
        '^.....~~...........^',
        '^.....~~...,,,,,...^',
        '^.....~~...,,,,,...^',
        '^.....~~...,,,,,...^',
        '^.....~~...........^',
        '^^^^^^~~^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['futi', 0], 'right' => ['zhange', 0]],
    'signs' => [
        [9, 6, [
            ['say', '阴山 —— 浊浴之水出焉，而南流注于蕃泽，其中多文贝'],
        ]],
    ],
    // The tall grass: 天狗.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 鹰鸇、积蛇、鹰鸇、积蛇 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[16, 'yingzhan'], [16, 'jishe'], [16, 'yingzhan'], [16, 'jishe'], [13, 'tiangou'], [13, 'tiangou'], [13, 'tiangou']]],
];
