<?php
// 空桑之山, two hundred li north. The classic: no grass or trees; snow in winter and summer; the 空桑之水 rises on it and runs
// east into the 虖沱. It names no beast. The snow is not drawn.
declare(strict_types=1);

return [
    'id' => 'kongsang',
    'name' => '空桑之山',
    'range' => 10,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^---^^-------------^',
        '^---^^-------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^-----------~~~~~~~^',
        '^-----------~~~~~~~^',
        '^------------------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['baima', 0], 'right' => ['taixi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '空桑之山 —— 无草木，冬夏有雪；空桑之水出焉，东流注于虖沱'],
        ]],
    ],
];
