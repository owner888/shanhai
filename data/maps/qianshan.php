<?php
// 钤山, the first of the Western Mountains' second range, walked westward like the first. The classic: on it much
// copper, below it much jade; its trees are mostly 杻 and 橿. It names no beast. The one who brought the player from 騩山
// waits at its east end.
declare(strict_types=1);

return [
    'id' => 'qianshan',
    'name' => '钤山',
    'range' => 5,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T.TT....TT....TT...T',
        'T.TT....TT....TT...T',
        'T..................T',
        'T......TT......TT..T',
        'T..................T',
        'T..................T',
        '===================^',
        '^..................^',
        '^..^^........^^....^',
        '^..^^........^^....^',
        '^......TT..........^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['taimao', 0]],
    'signs' => [
        [9, 6, [
            ['say', '钤山 —— 其上多铜，其下多玉，其木多杻橿'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 18, 7, 'left', 'guard', [
            ['ask', '「要回騩山吗？我送你一程。也可以歇一歇、去兽栏，或者买点东西。」', [['回騩山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买东西', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'guishan4', 7, 7, 'right'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['end'],
            ['label', 'pen'],
            ['pen'],
            ['end'],
            ['label', 'shop'],
            ['shop', ['seal', 'zhuyu', 'bili', 'licao', 'yupei', 'jibai', 'yuzhang']],
            ['label', 'stay'],
        ]],
    ],
];
