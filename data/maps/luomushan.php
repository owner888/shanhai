<?php
// 蠃母之山, two hundred li on from 流沙. The classic: the spirit 长乘 keeps it, a human shape with a 犳's tail; on it
// much jade, below it much blue stone, and no water. It names no beast.
declare(strict_types=1);

return [
    'id' => 'luomushan',
    'name' => '蠃母之山',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^--^^^-------------^',
        '^--^^^------^^^^---^',
        '^-----------^^^^---^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^------------------^',
        '^------^^^---------^',
        '^------^^^---------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['yushanxi', 0], 'right' => ['liusha', 0]],
    'signs' => [
        [9, 6, [
            ['say', '蠃母之山 —— 神长乘司之，其神状如人而犳尾；其上多玉，其下多青石而无水'],
        ]],
    ],
];
