<?php
// 天虞之山, the first of the third range of the south. The classic says one thing of it: below it is much water, and it
// cannot be climbed. So the mountain is a wall behind the water, and the road keeps to its foot. The one who brought
// the player from 漆吴之山 waits at its west end.
declare(strict_types=1);

return [
    'id' => 'tianyu',
    'name' => '天虞之山',
    'range' => 3,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^~~~~~~~~~~~~~^^^^',
        '^~~~~~~~~~~~~~~~~~~^',
        '^~~~~~~~~~~~~~~~~~~^',
        '^..................^',
        '^===================',
        '^..................^',
        '^....TT............^',
        '^....TT.....~~~~...^',
        '^..........~~~~~~..^',
        '^..........~~~~~~..^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['right' => ['daoguo', 0]],
    'signs' => [
        [9, 6, [
            ['say', '天虞之山 —— 其下多水，不可以上'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回漆吴之山吗？我送你一程。也可以歇一歇、去兽栏，或者买点东西。」', [['回漆吴之山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买东西', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'qiwu', 12, 7, 'right'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['end'],
            ['label', 'pen'],
            ['pen'],
            ['end'],
            ['label', 'shop'],
            ['shop', ['seal', 'seal2', 'zhuyu', 'bili', 'kangmu', 'huangguan', 'rongcao', 'zhichu', 'yupei', 'jibai', 'wutiao', 'huangji', 'kuxin', 'yansuan']],
            ['label', 'stay'],
        ]],
    ],
];
