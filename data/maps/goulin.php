<?php
// 苟林之山 (苟床之山 in other editions), the first of the Central Mountains' fifth range, a second stretch of 薄山. The classic:
// no grass or trees, many strange stones. It names no beast.
declare(strict_types=1);

return [
    'id' => 'goulin',
    'name' => '苟林之山',
    'range' => 19,
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
    'exits' => ['right' => ['shoushan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '中次五经薄山之首，曰苟林之山 —— 无草木，多怪石'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回讙举之山吗？我送你一程。也可以歇一歇、去兽栏，或者买点东西。」', [['回讙举之山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买东西', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'huanju', 16, 7, 'left'],
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
