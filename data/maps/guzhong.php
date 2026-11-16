<?php
// 鼓钟之山, three hundred li east. The classic: where 帝台 feasts the hundred gods; a herb called 焉酸 that works against
// poison; whetstone on it and below. It names no beast.
declare(strict_types=1);

return [
    'id' => 'guzhong',
    'name' => '鼓钟之山',
    'range' => 21,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^...^^^............^',
        '^...^^^............^',
        '^..................^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^..................^',
        '^...........^^^....^',
        '^...........^^^....^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['xiuyushan', 0], 'right' => ['guyao', 0]],
    'signs' => [
        [9, 6, [
            ['say', '鼓钟之山 —— 帝台之所以觞百神也；其上多砺，其下多砥'],
        ]],
    ],
];
