<?php
// 白石之山, fifty li east. The classic: the 惠水 rises on its south side and runs south into the 洛, with much crystal in it;
// the 涧水 on its north and runs northwest into the 穀水. It names no beast.
declare(strict_types=1);

return [
    'id' => 'baishi',
    'name' => '白石之山',
    'range' => 20,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^..................^',
        '====================',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^^^^^^^^^~~^^^^^^^^^',
    ],
    'exits' => ['left' => ['gushan', 0], 'right' => ['louzhuo', 0]],
    'signs' => [
        [12, 6, [
            ['say', '白石之山 —— 惠水出于其阳，而南流注于洛，其中多水玉'],
        ]],
    ],
];
