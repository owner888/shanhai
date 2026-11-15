<?php
// 景山, two hundred li north: the second of the name in this range. The classic: fine jade; the 景水 rises on it and runs
// southeast into 海泽. It names no beast.
declare(strict_types=1);

return [
    'id' => 'jingshansan',
    'name' => '景山',
    'range' => 10,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^...^^.............^',
        '^...^^.............^',
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
    'exits' => ['left' => ['xishanbei', 0], 'right' => ['tishou', 0]],
    'signs' => [
        [9, 6, [
            ['say', '景山 —— 有美玉；景水出焉，东南流注于海泽'],
        ]],
    ],
];
