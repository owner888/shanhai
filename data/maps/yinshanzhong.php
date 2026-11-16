<?php
// 阴山, thirty-five li north (`yinshan` is the 阴山 of the west). The classic: whetstone and patterned stone; the 少水 rises
// on it; 雕棠 trees, whose fruit cures deafness. It names no beast.
declare(strict_types=1);

return [
    'id' => 'yinshanzhong',
    'name' => '阴山',
    'range' => 15,
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
    'exits' => ['left' => ['hegu', 0], 'right' => ['gudeng', 0]],
    'signs' => [
        [9, 6, [
            ['say', '阴山 —— 多砺石、文石；少水出焉，其中多雕棠'],
        ]],
    ],
];
