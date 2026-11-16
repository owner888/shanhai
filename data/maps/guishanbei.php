<?php
// 归山, the head of 太行之山 and the first of the Northern Mountains' third range. The classic: gold and jade on it, 碧 below;
// the beast 䮝 lives there, an antelope with four horns, a horse's tail and spurs, and the bird 䴅, a magpie with six
// feet, easily startled.
declare(strict_types=1);

return [
    'id' => 'guishanbei',
    'name' => '归山',
    'range' => 10,
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
    'exits' => ['right' => ['longhou', 0]],
    'signs' => [
        [9, 6, [
            ['say', '太行之山，其首曰归山 —— 其上有金玉，其下有碧'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回敦题之山吗？我送你一程。也可以歇一歇、去兽栏，或者买几枚契印。」', [['回敦题之山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买契印', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'dunti', 16, 7, 'left'],
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
    // Its wild ground. The range's levels are those of the three areas before the reference's tenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[13, 'hui'], [13, 'ben'], [15, 'hui'], [12, 'ben'], [14, 'hui'], [14, 'ben'], [14, 'hui']]],
];
