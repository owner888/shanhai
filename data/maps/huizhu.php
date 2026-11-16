<?php
// 煇诸之山, the first of the Central Mountains' second range, the head of 济山, which is walked westward. The classic:
// mulberry on it; its beasts are mostly 闾 and elk, its birds 鹖.
declare(strict_types=1);

return [
    'id' => 'huizhu',
    'name' => '煇诸之山',
    'range' => 16,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..............TT..T',
        'T..................T',
        'T===================',
        'T..................T',
        'T.......,,,,,......T',
        'T.......,,,,,^^....T',
        'T.......,,,,,^^....T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['right' => ['fashi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '中次二经济山之首，曰煇诸之山 —— 其上多桑，其兽多闾麋，其鸟多鹖'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回鼓镫之山吗？我送你一程。也可以在这里歇一歇，或者去兽栏看看。」', [['回鼓镫之山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'gudeng', 16, 7, 'left'],
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
    // Its wild ground. The range's levels are those of the areas before the reference's sixteenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[3, 'lyu'], [3, 'mi'], [5, 'he'], [4, 'lyu'], [6, 'mi'], [7, 'he'], [7, 'lyu']]],
];
