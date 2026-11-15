<?php
// 积石之山, three hundred li west of 轩辕之丘. The classic: below it is a gate of stone, through which the 河 pours
// westward; there is nothing the mountain does not have. It names no beast.
declare(strict_types=1);

return [
    'id' => 'jishi',
    'name' => '积石之山',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^-----^^---^^------^',
        '^-----^^---^^------^',
        '^~~~~~~~~~~~~~~~~~~^',
        '^~~~~~~~~~~~~~~~~~~^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['changliu', 0], 'right' => ['xuanyuan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '积石之山 —— 其下有石门，河水冒以西流'],
        ]],
    ],
];
