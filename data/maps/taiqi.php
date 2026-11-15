<?php
// 泰器之山, a hundred and eighty li west of 钟山. The classic: the 观水 rises on it and runs west into 流沙, and in it
// are many 文鳐鱼, a carp's shape with a bird's wings, at whose sight the harvest is great.
declare(strict_types=1);

return [
    'id' => 'taiqi',
    'name' => '泰器之山',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^------------------^',
        '^-------------^^---^',
        '^-------------^^---^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '~~~~~~~~~~---------^',
        '~~~~~~~~~~---------^',
        '~~~~~~~~~~---------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['huaijiang', 0], 'right' => ['zhongshan', 0]],
    'signs' => [
        [11, 6, [
            ['say', '泰器之山 —— 观水出焉，西流注于流沙，是多文鳐鱼'],
        ]],
    ],
    // The 观水: 文鳐鱼.
    'water' => ['rate' => 2, 'slots' => [[20, 'wenyaoyu'], [15, 'wenyaoyu'], [20, 'wenyaoyu']]],
];
