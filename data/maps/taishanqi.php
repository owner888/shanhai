<?php
// 太山, ten li southeast (the third of the name: `taishan` is 泰山 and `taishandong` 太山, both of the east). The classic: a
// herb called 梨, which cures 疽; the 太水 rises on its south side and runs southeast into the 役水; the 承水 on its north and
// runs northeast into it. It names no beast.
declare(strict_types=1);

return [
    'id' => 'taishanqi',
    'name' => '太山',
    'range' => 21,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^...^^.............^',
        '^...^^.............^',
        '^..................^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^...........~~~~~~~^',
        '^...........~~~~~~~^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['shaoxing', 0], 'right' => ['moshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '太山 —— 太水出于其阳，而东南流注于役水'],
        ]],
    ],
];
