<?php
// 流沙, the flowing sands, four hundred li west of 乐游之山 by water: not a mountain, but the classic counts its way
// through it, and with it the range has the twenty-three it says. It is walked here, not crossed by water (docs/todo.md).
declare(strict_types=1);

return [
    'id' => 'liusha',
    'name' => '流沙',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^------------------^',
        '^---^^-------------^',
        '^---^^-------------^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^------------------^',
        '^------------^^^---^',
        '^------------^^^---^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['luomushan', 0], 'right' => ['leyou', 0]],
    'signs' => [
        [9, 6, [
            ['say', '流沙 —— 西水行四百里，曰流沙'],
        ]],
    ],
];
