<?php
// 葱聋之山, three hundred li east (`conglong` is the 葱聋之山 of the first range). The classic: no grass or trees, much of a
// stone whose name the editions write differently. It names no beast.
declare(strict_types=1);

return [
    'id' => 'conglongwu',
    'name' => '葱聋之山',
    'range' => 19,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^---^^^------------^',
        '^---^^^------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^------------------^',
        '^-----------^^^----^',
        '^-----------^^^----^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['xianzhu', 0], 'right' => ['tiaogu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '葱聋之山 —— 无草木'],
        ]],
    ],
];
