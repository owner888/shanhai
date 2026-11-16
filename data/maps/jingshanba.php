<?php
// 景山, the first of the Central Mountains' eighth range, the head of 荆山 (the third 景山: the other two are of the north).
// The classic: gold and jade, 杼 and 檀; the 雎水 rises on it and runs southeast into the 江, with 丹粟 and many 文鱼 in it.
declare(strict_types=1);

return [
    'id' => 'jingshanba',
    'name' => '景山',
    'range' => 22,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T...^^.............T',
        'T...^^.............T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T===================',
        'T..................T',
        'T...........~~~~~~~T',
        'T...TT......~~~~~~~T',
        'T...TT.............T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['right' => ['jingshanjing', 0]],
    'signs' => [
        [9, 6, [
            ['say', '中次八经荆山之首，曰景山 —— 其上多金玉，其木多杼檀；雎水出焉，东南流注于江'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回大騩之山吗？我送你一程。也可以歇一歇、去兽栏，或者买点东西。」', [['回大騩之山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买东西', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'dagui', 16, 7, 'left'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['end'],
            ['label', 'pen'],
            ['pen'],
            ['end'],
            ['label', 'shop'],
            ['shop', ['seal', 'seal3', 'licao', 'youmu', 'dixiu', 'yupei', 'jibai']],
            ['label', 'stay'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'wenyu'], [15, 'wenyu'], [20, 'wenyu']]],
];
