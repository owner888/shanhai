<?php
// 刚山之尾, the tail of 刚山, two hundred li west of it. The classic: the 洛水 rises on it and runs north into the 河,
// and in it are many 蛮蛮, a rat's body with a turtle's head: not the bird of the name.
declare(strict_types=1);

return [
    'id' => 'gangwei',
    'name' => '刚山之尾',
    'range' => 7,
    'rows' => [
        '^^^^^^^^~~^^^^^^^^^^',
        '^.......~~.........^',
        '^.....~~~~~~.......^',
        '^.....~~~~~~.......^',
        '^.....~~~~~~.......^',
        '^.......~~.........^',
        '^..................^',
        '====================',
        '^..................^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['yingdi', 0], 'right' => ['gangshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '刚山之尾 —— 洛水出焉，而北流注于河，其中多蛮蛮'],
        ]],
    ],
    // The 洛水: the 蛮蛮 of the water.
    'water' => ['rate' => 2, 'slots' => [[20, 'manmanshou'], [15, 'manmanshou'], [20, 'manmanshou']]],
];
