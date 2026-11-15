<?php
// 长留之山, two hundred li west of 积石之山. The classic: the white god 少昊 lives there; its beasts all have patterned
// tails, its birds patterned heads; much patterned jade. It names none of the beasts.
declare(strict_types=1);

return [
    'id' => 'changliu',
    'name' => '长留之山',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^...^^.......^^^...^',
        '^...^^.......^^^...^',
        '^..................^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^..................^',
        '^.......^^^........^',
        '^.......^^^........^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['zhange', 0], 'right' => ['jishi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '长留之山 —— 其神白帝少昊居之；其兽皆文尾，其鸟皆文首'],
        ]],
    ],
];
