<?php
// 尧光之山, three hundred and forty li east of 长右之山. The classic: on its south side much jade, on its north much
// gold; 猾褢 lives there, in a hole, and sleeps the winter. So the beast is in the cave, whose mouth is in the cliff.
declare(strict_types=1);

return [
    'id' => 'yaoguang',
    'name' => '尧光之山',
    'range' => 2,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^^^^^D^^^^^^^^^',
        '^..................^',
        '^....T.........T...^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^..................^',
        '^..^^^.......^^^^..^',
        '^..^^^.......^^^^..^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['changyou', 0], 'right' => ['yushan', 0]],
    'warps' => [
        [10, 2, 'yaoguang_cave', 7, 8, 'up'],
    ],
    'signs' => [
        [8, 6, [
            ['say', '尧光之山 —— 阳多玉，阴多金；有兽穴居'],
        ]],
    ],
];
