<?php
// 鹿蹄之山, the first of the Central Mountains' fourth range, the head of 釐山, which is walked westward. The classic: jade on
// it, gold below; the 甘水 rises on it and runs north into the 洛, with much 泠石 in it. It names no beast.
declare(strict_types=1);

return [
    'id' => 'luti',
    'name' => '鹿蹄之山',
    'range' => 18,
    'rows' => [
        '^^^^^^^^^~~^^^^^^^^^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^..................^',
        '^===================',
        '^..................^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['right' => ['fuzhushan', 0]],
    'signs' => [
        [12, 6, [
            ['say', '中次四经釐山之首，曰鹿蹄之山 —— 其上多玉，其下多金；甘水出焉，而北流注于洛'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回和山吗？我送你一程。也可以歇一歇、去兽栏，或者买点东西。」', [['回和山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买东西', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'heshan', 16, 7, 'left'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['end'],
            ['label', 'pen'],
            ['pen'],
            ['end'],
            ['label', 'shop'],
            ['shop', ['seal', 'seal3', 'licao', 'youmu', 'dixiu', 'yupei', 'jibai', 'wutiao', 'huangji', 'kuxin', 'yansuan', 'yuzhang', 'yubi']],
            ['label', 'stay'],
        ]],
    ],
];
