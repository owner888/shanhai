<?php
// 尸胡之山, the first of the Eastern Mountains' third range, which is walked by water from hill to hill. The classic: it
// looks north on 祥山; gold and jade on it, thorns below; the beast 妴胡 lives there, an elk with a fish's eyes.
declare(strict_types=1);

return [
    'id' => 'shihu',
    'name' => '尸胡之山',
    'range' => 13,
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
    'exits' => ['right' => ['qishan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '东次三经之首，曰尸胡之山 —— 北望祥山，其上多金玉，其下多棘'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回䃌山吗？我送你一程。也可以歇一歇、去兽栏，或者买点东西。」', [['回䃌山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买东西', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'yinshandong', 16, 7, 'left'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['end'],
            ['label', 'pen'],
            ['pen'],
            ['end'],
            ['label', 'shop'],
            ['shop', ['seal', 'seal2', 'seal3', 'bili', 'yupei']],
            ['label', 'stay'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's thirteenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[15, 'wanhu'], [15, 'wanhu'], [15, 'wanhu'], [15, 'wanhu'], [15, 'wanhu'], [18, 'wanhu'], [18, 'wanhu']]],
];
