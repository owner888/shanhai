<?php
// 少阳之山, two hundred and fifty li on from 管涔之山. The classic: on it much jade, below it much red silver; the 酸水
// rises on it and runs east into the 汾水, with fine red earth in it. It names no beast.
declare(strict_types=1);

return [
    'id' => 'shaoyang',
    'name' => '少阳之山',
    'range' => 9,
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
        '^...........~~~~~~~^',
        '^...........~~~~~~~^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['guancen', 0], 'right' => ['xianyong', 0]],
    'signs' => [
        [9, 6, [
            ['say', '少阳之山 —— 其上多玉，其下多赤银；酸水出焉，而东流注于汾水'],
        ]],
    ],
];
