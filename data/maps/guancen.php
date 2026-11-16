<?php
// 管涔之山, the first of the Northern Mountains' second range: east of the 河, its head pillowed on the 汾. The classic:
// on it no trees and much grass, below it much jade; the 汾水 rises on it and runs west into the 河. It names no beast.
// The one who brought the player from 隄山 waits at its near end.
declare(strict_types=1);

return [
    'id' => 'guancen',
    'name' => '管涔之山',
    'range' => 9,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..................^',
        '^............^^^...^',
        '^............^^^...^',
        '^..................^',
        '^..................^',
        '^===================',
        '^..................^',
        '^~~~~~~~...........^',
        '^~~~~~~~...........^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['right' => ['shaoyang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '管涔之山 —— 其上无木而多草，其下多玉；汾水出焉，而西流注于河'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回隄山吗？我送你一程。也可以在这里歇一歇，或者去兽栏看看。」', [['回隄山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'tishan', 16, 7, 'left'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['end'],
            ['label', 'pen'],
            ['pen'],
            ['label', 'stay'],
        ]],
    ],
];
