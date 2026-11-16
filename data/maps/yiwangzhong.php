<?php
// 翼望之山, the first of the Central Mountains' eleventh range, a second stretch of 荆山 and the longest range of all,
// forty-eight hills (`yiwang` is the 翼望之山 of the west). The classic: the 湍水 rises on it and runs east into the 济; the 贶水
// southeast into the 汉, with many 蛟 in it; pines and cypresses, 漆 and 梓; red gold and 珉 stone.
declare(strict_types=1);

return [
    'id' => 'yiwangzhong',
    'name' => '翼望之山',
    'range' => 25,
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
    'exits' => ['right' => ['zhaogesy', 0]],
    'signs' => [
        [9, 6, [
            ['say', '翼望之山 —— 湍水出焉，东流注于济；贶水出焉，东南流注于汉，其中多蛟；其上多松柏，其下多漆梓'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回丙山吗？我送你一程。也可以歇一歇、去兽栏，或者买点东西。」', [['回丙山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买东西', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'bingshanshi', 16, 7, 'left'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['end'],
            ['label', 'pen'],
            ['pen'],
            ['end'],
            ['label', 'shop'],
            ['shop', ['seal', 'seal3', 'licao', 'youmu', 'dixiu', 'yupei', 'jibai', 'yuzhang', 'yubi', 'yugui']],
            ['label', 'stay'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'jiaolong'], [15, 'jiaolong'], [20, 'jiaolong']]],
];
