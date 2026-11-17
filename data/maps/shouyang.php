<?php
// 首阳之山, the first of the Central Mountains' tenth range, which is walked westward. The classic: gold and jade, no grass
// or trees. It names no beast.
declare(strict_types=1);

return [
    'id' => 'shouyang',
    'name' => '首阳之山',
    'range' => 24,
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
    'exits' => ['right' => ['huwei', 0]],
    'signs' => [
        [9, 6, [
            ['say', '中次十经之首，曰首阳之山 —— 其上多金玉，无草木'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回贾超之山吗？我送你一程。也可以歇一歇、去兽栏，或者买点东西。」', [['回贾超之山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买东西', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'jiachao', 16, 7, 'left'],
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
