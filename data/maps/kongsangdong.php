<?php
// 空桑之山, the first of the Eastern Mountains' second range (`kongsang` is the 空桑之山 of the north). The classic: it looks
// north on the 食水, east on 沮吴, south on 沙陵, west on 湣泽; the beast 軨軨 lives there, an ox with a tiger's stripes, at whose
// sight the world has floods.
declare(strict_types=1);

return [
    'id' => 'kongsangdong',
    'name' => '空桑之山',
    'range' => 12,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '^===================',
        '^..................^',
        '^.......,,,,,......^',
        '^.......,,,,,^^....^',
        '^.......,,,,,^^....^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['right' => ['caoxi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '东次二经之首，曰空桑之山 —— 北临食水，东望沮吴，南望沙陵，西望湣泽'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回竹山吗？我送你一程。也可以歇一歇、去兽栏，或者买点东西。」', [['回竹山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买东西', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'zhushandong', 16, 7, 'left'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['end'],
            ['label', 'pen'],
            ['pen'],
            ['end'],
            ['label', 'shop'],
            ['shop', ['seal', 'seal2', 'seal3', 'bili', 'licao', 'yupei', 'yuzhang', 'yubi']],
            ['label', 'stay'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's twelfth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[23, 'lingling'], [23, 'lingling'], [25, 'lingling'], [25, 'lingling'], [27, 'lingling'], [27, 'lingling'], [25, 'lingling']]],
];
