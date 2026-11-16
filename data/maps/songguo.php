<?php
// 松果之山, forty-five li west of 钱来之山. The classic: the 濩水 rises on it and runs north into the 渭, with much copper
// in it; the bird 䳋渠 lives there.
declare(strict_types=1);

return [
    'id' => 'songguo',
    'name' => '松果之山',
    'range' => 4,
    'rows' => [
        '^^^^^^^^~~~^^^^^^^^^',
        '^.......~~~........^',
        '^.,,,,..~~~..,,,,,.^',
        '^.,,,,..~~...,,,,,.^',
        '^.,,,,..~~...,,,,,.^',
        '^.......~~.........^',
        '^..................^',
        '====================',
        '^..................^',
        '^...TT.............^',
        '^...TT.....,,,,....^',
        '^..........,,,,....^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['taihua', 0], 'right' => ['qianlai', 0]],
    'signs' => [
        [11, 6, [
            ['say', '松果之山 —— 濩水出焉，北流注于渭，其中多铜'],
        ]],
    ],
    // The tall grass: 䳋渠.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 麝、旄牛、尸鸠、麝 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[4, 'she'], [4, 'maoniu'], [5, 'shijiu'], [5, 'she'], [5, 'tongqu'], [6, 'tongqu'], [6, 'tongqu']]],
];
