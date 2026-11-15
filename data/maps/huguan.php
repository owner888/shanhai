<?php
// 湖灌之山, three hundred and eighty li north of 姑灌之山. The classic: on its south side much jade, on its north much
// 碧; many horses; the 湖灌之水 rises on it and runs east into the sea, with many 䱇 in it; a tree grows there with
// leaves like the willow's and a red grain.
declare(strict_types=1);

return [
    'id' => 'huguan',
    'name' => '湖灌之山',
    'range' => 9,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,,.....TT...^',
        '^..,,,,,,.....TT...^',
        '^..,,,,,,..........^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^...,,,,,...~~~~~~~^',
        '^...,,,,,...~~~~~~~^',
        '^...,,,,,...~~~~~~~^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['guguan', 0], 'right' => ['huanshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '湖灌之山 —— 其阳多玉，其阴多碧，多马；湖灌之水出焉，而东流注于海'],
        ]],
    ],
    // The 湖灌之水: 䱇.
    'water' => ['rate' => 2, 'slots' => [[20, 'shanyu'], [15, 'shanyu'], [20, 'shanyu']]],
    // The tall grass: 马.
    'wild' => ['rate' => 10, 'slots' => [[14, 'ma'], [13, 'ma'], [15, 'ma'], [16, 'ma'], [16, 'ma'], [16, 'ma'], [16, 'ma']]],
];
