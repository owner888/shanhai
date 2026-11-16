<?php
// 长石之山, a hundred and forty li east. The classic: no grass or trees, gold and jade; west of it a valley called 共谷, with
// much bamboo; the 共水 rises on it and runs southwest into the 洛. It names no beast.
declare(strict_types=1);

return [
    'id' => 'changshi',
    'name' => '长石之山',
    'range' => 20,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^^^^^^^^^~~^^^^^^^^^',
    ],
    'exits' => ['left' => ['fushanliu', 0], 'right' => ['mishanliu', 0]],
    'signs' => [
        [12, 6, [
            ['say', '长石之山 —— 无草木，多金玉；其西有谷焉，名曰共谷，多竹'],
        ]],
    ],
];
