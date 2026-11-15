<?php
// 申首之山, three hundred li northwest of 白於之山. The classic: no grass or trees; snow in winter and summer; the 申水
// comes out on it and goes under below it, with much white jade in it. It names no beast. The snow is not drawn
// (docs/todo.md).
declare(strict_types=1);

return [
    'id' => 'shenshou',
    'name' => '申首之山',
    'range' => 7,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^---^^^------------^',
        '^---^^^-~~~--------^',
        '^-------~~~--------^',
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
    'exits' => ['left' => ['jinggu', 0], 'right' => ['baiyu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '申首之山 —— 无草木，冬夏有雪；申水出于其上，潜于其下'],
        ]],
    ],
];
