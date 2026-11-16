<?php
// 甘枣之山, the first of the Central Mountains, the head of 薄山. The classic: the 共水 rises on it and runs west into the 河;
// many 杻 trees; a herb called 箨 that clears dim sight; the beast 㔮 lives there, a rat with a marked forehead.
declare(strict_types=1);

return [
    'id' => 'ganzao',
    'name' => '甘枣之山',
    'range' => 15,
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
        'T~~~~~~~...........T',
        'T~~~~~~~...........T',
        'T..................T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['right' => ['lier', 0]],
    'signs' => [
        [9, 6, [
            ['say', '中山经薄山之首，曰甘枣之山 —— 共水出焉，而西流注于河；其上多杻木'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回太山吗？我送你一程。也可以歇一歇、去兽栏，或者买几枚契印。」', [['回太山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买契印', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'taishandong', 16, 7, 'left'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['end'],
            ['label', 'pen'],
            ['pen'],
            ['end'],
            ['label', 'shop'],
            ['shop', ['seal']],
            ['label', 'stay'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's fifteenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 6, 'slots' => [[30, 'na'], [25, 'na'], [35, 'na'], [20, 'na'], [30, 'na'], [28, 'na'], [28, 'na']]],
];
