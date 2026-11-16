<?php
// 姑媱之山, two hundred li east. The classic: 帝's daughter died here, called 女尸, and became the herb 䔄草, which makes one
// loved. It names no beast.
declare(strict_types=1);

return [
    'id' => 'guyao',
    'name' => '姑媱之山',
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
    'exits' => ['left' => ['guzhong', 0], 'right' => ['kushan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '姑媱之山 —— 帝女死焉，其名曰女尸，化为䔄草'],
            ['ifFlag', 'found_yaocao', 'had'],
            ['setFlag', 'found_yaocao'],
            ['give', 'yaocao', 1],
            ['say', '牌子旁边长着䔄草。你采了一份。'],
            ['label', 'had'],
        ]],
    ],
];
