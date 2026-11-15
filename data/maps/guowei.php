<?php
// 虢山之尾, the tail of 虢山, four hundred li north. The classic: on it much jade and no stone; the 鱼水 rises on it and
// runs west into the 河, with many patterned shells in it. It names no beast.
declare(strict_types=1);

return [
    'id' => 'guowei',
    'name' => '虢山之尾',
    'range' => 8,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..................^',
        '^...........^^^....^',
        '^...........^^^....^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^~~~~~~~...........^',
        '^~~~~~~~...........^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['guoshan', 0], 'right' => ['danxun', 0]],
    'signs' => [
        [9, 6, [
            ['say', '虢山之尾 —— 其上多玉而无石；鱼水出焉，西流注于河，其中多文贝'],
        ]],
    ],
];
