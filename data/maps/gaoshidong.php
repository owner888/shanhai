<?php
// 高氏之山, four hundred li south (`gaoshi` is 高是之山, of the north). The classic: jade on it, needle-stone below; the 诸绳之水
// rises on it and runs east into a marsh, with gold and jade in it. It names no beast.
declare(strict_types=1);

return [
    'id' => 'gaoshidong',
    'name' => '高氏之山',
    'range' => 11,
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
    'exits' => ['left' => ['guer', 0], 'right' => ['yueshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '高氏之山 —— 其上多玉，其下多箴石；诸绳之水出焉，东流注于泽'],
        ]],
    ],
];
