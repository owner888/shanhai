<?php
// 篇遇之山, the first of the Central Mountains' twelfth range, 洞庭山, the last range of the book, fifteen hills. The classic:
// no grass or trees, much gold. It names no beast.
declare(strict_types=1);

return [
    'id' => 'pianyu',
    'name' => '篇遇之山',
    'range' => 26,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^---^^^------------^',
        '^---^^^------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^===================',
        '^------------------^',
        '^------------------^',
        '^-----------^^^----^',
        '^-----------^^^----^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['right' => ['yunshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '篇遇之山 —— 无草木，多黄金'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回几山吗？我送你一程。也可以歇一歇、去兽栏，或者买点东西。」', [['回几山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买东西', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'jishanji', 16, 7, 'left'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['end'],
            ['label', 'pen'],
            ['pen'],
            ['end'],
            ['label', 'shop'],
            ['shop', ['seal', 'seal3', 'licao', 'youmu', 'dixiu', 'yupei', 'jibai', 'wutiao', 'huangji', 'kuxin', 'yansuan', 'yuzhang', 'yubi', 'yugui']],
            ['label', 'stay'],
        ]],
    ],
];
