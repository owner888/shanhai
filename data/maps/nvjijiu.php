<?php
// 女几之山, the first of the Central Mountains' ninth range, the head of 岷山 (`nvji` is the 女几之山 of the eighth). The classic:
// 石涅 on it, 杻 and 橿, 菊 and 䒗; the 洛水 rises on it and runs east into the 江, with realgar in it; its beasts are mostly
// tigers and leopards.
declare(strict_types=1);

return [
    'id' => 'nvjijiu',
    'name' => '女几之山',
    'range' => 23,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
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
    'exits' => ['right' => ['minshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '中次九经岷山之首，曰女几之山 —— 其上多石涅，其木多杻橿，其草多菊䒗；洛水出焉，东注于江'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回琴鼓之山吗？我送你一程。也可以歇一歇、去兽栏，或者买点东西。」', [['回琴鼓之山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买东西', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'qingu', 16, 7, 'left'],
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
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[42, 'hu'], [43, 'bao'], [43, 'hu'], [45, 'bao'], [44, 'hu'], [46, 'bao'], [46, 'hu']]],
];
