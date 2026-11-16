<?php
// 大苦之山, fifty-seven li east. The classic: 㻬琈 jade and 麋 jade; a herb called 牛伤; the 狂水 rises on its south side and runs
// southwest into the 伊水, with many three-footed turtles in it.
declare(strict_types=1);

return [
    'id' => 'dakushan',
    'name' => '大苦之山',
    'range' => 21,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^..................^',
        '====================',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^^^^^^^^^~~^^^^^^^^^',
    ],
    'exits' => ['left' => ['fanggao', 0], 'right' => ['banshi', 0]],
    'signs' => [
        [12, 6, [
            ['say', '大苦之山 —— 多㻬琈之玉，多麋玉；其阳狂水出焉，西南流注于伊水'],
            ['ifFlag', 'found_niushang', 'had'],
            ['setFlag', 'found_niushang'],
            ['give', 'niushang', 1],
            ['say', '牌子旁边长着牛伤。你采了一份。'],
            ['label', 'had'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'sanzugui'], [15, 'sanzugui'], [20, 'sanzugui']]],
];
