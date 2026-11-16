<?php
// 鹿台之山, two hundred li west of 龙首之山. The classic: on it much white jade, below it much silver; its beasts are
// mostly 㸲牛, 羬羊 and 白豪; the bird 凫徯 lives there, a cock with a human face, whose call is its name, and at whose
// sight there is war.
declare(strict_types=1);

return [
    'id' => 'lutai',
    'name' => '鹿台之山',
    'range' => 5,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^.,,,,,....,,,,,,..^',
        '^.,,,,,....,,,,,,..^',
        '^.,,,,,....,,,,,,..^',
        '^..................^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^....TT....,,,,,...^',
        '^....TT....,,,,,...^',
        '^..........,,,,,...^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['niaowei', 0], 'right' => ['longshou', 0]],
    'signs' => [
        [9, 6, [
            ['say', '鹿台之山 —— 其上多白玉，其下多银；其兽多㸲牛、羬羊、白豪'],
        ]],
    ],
    // The tall grass: 㸲牛, 羬羊, 白豪, and the bird 凫徯.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 虎 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[16, 'zuoniu'], [16, 'baihao'], [16, 'hu'], [16, 'zuoniu'], [13, 'qianyang'], [13, 'fuxi'], [13, 'qianyang']]],
];
