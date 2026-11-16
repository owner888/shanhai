<?php
// 北号之山, the first of the Eastern Mountains' fourth range. The classic: it stands on the northern sea; a tree like a
// poplar with red flowers, whose fruit, like a jujube without a stone, keeps off fever; the 食水 rises on it and runs
// northeast into the sea; the beast 猲狙 lives there, a wolf with a red head and a rat's eyes, which eats people; and the
// bird 鬿雀, a fowl with a white head, a rat's feet and a tiger's claws, which eats people too.
declare(strict_types=1);

return [
    'id' => 'beihao',
    'name' => '北号之山',
    'range' => 14,
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
    'exits' => ['right' => ['maoshandong', 0]],
    'signs' => [
        [9, 6, [
            ['say', '东次四经之首，曰北号之山 —— 临于北海；食水出焉，而东北流注于海'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回无皋之山吗？我送你一程。累了也可以在这里歇一歇。」', [['回无皋之山', 'go'], ['歇一歇', 'rest'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'wugao', 16, 7, 'left'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['label', 'stay'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's fourteenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[5, 'geju'], [5, 'qique'], [8, 'geju'], [10, 'qique'], [10, 'geju'], [10, 'qique'], [10, 'geju']]],
];
