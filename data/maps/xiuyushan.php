<?php
// 休与之山, the first of the Central Mountains' seventh range, the head of 苦山. The classic: a stone on it called 帝台之棋,
// five-coloured and patterned; a herb called 夙条, good for arrow shafts. It names no beast.
declare(strict_types=1);

return [
    'id' => 'xiuyushan',
    'name' => '休与之山',
    'range' => 21,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^...^^^............^',
        '^...^^^............^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^===================',
        '^..................^',
        '^..................^',
        '^...........^^^....^',
        '^...........^^^....^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['right' => ['guzhong', 0]],
    'signs' => [
        [9, 6, [
            ['say', '中次七经苦山之首，曰休与之山 —— 其上有石焉，名曰帝台之棋，五色而文'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回平逢之山吗？我送你一程。也可以在这里歇一歇，或者去兽栏看看。」', [['回平逢之山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'pingfeng', 16, 7, 'left'],
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
