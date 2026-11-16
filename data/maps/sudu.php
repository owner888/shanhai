<?php
// 樕蛛之山, the first of the Eastern Mountains. The classic: it looks north on 乾昧; the 食水 rises on it and runs northeast
// into the sea, and in it is the fish 鳙鳙, shaped like a brindled ox, with a pig's cry.
declare(strict_types=1);

return [
    'id' => 'sudu',
    'name' => '樕蛛之山',
    'range' => 11,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^...^^.............^',
        '^...^^.............^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^===================',
        '^..................^',
        '^...........~~~~~~~^',
        '^...........~~~~~~~^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['right' => ['leishan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '东山经之首，曰樕蛛之山 —— 北临乾昧；食水出焉，而东北流注于海'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回北山的錞于毋逢之山吗？我送你一程。也可以歇一歇、去兽栏，或者买点东西。」', [['回北山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买东西', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'chunyu', 16, 7, 'left'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['end'],
            ['label', 'pen'],
            ['pen'],
            ['end'],
            ['label', 'shop'],
            ['shop', ['seal', 'seal2', 'seal3', 'zhuyu', 'bili', 'licao', 'youmu', 'kangmu', 'huangguan', 'rongcao', 'zhichu', 'yupei', 'jibai']],
            ['label', 'stay'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'yongyong'], [15, 'yongyong'], [20, 'yongyong']]],
];
