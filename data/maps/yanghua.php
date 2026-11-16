<?php
// 阳华之山, the classic's last of the Central Mountains' sixth range, the head of 缟羝山, and the game's first: the range's one
// god lives at its other end, 平逢之山, so the road is walked eastward, against the classic's order. The classic: gold and
// jade on its south side, 青 and realgar on its north; yams and a herb called 苦辛 that cures fever; the 杨水 rises on it and
// runs southwest into the 洛, with many 人鱼 in it.
declare(strict_types=1);

return [
    'id' => 'yanghua',
    'name' => '阳华之山',
    'range' => 20,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^.............^^...^',
        '^.............^^...^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^===================',
        '^..................^',
        '^~~~~~~~...........^',
        '^~~~~~~~...........^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['right' => ['kuafu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '阳华之山 —— 其阳多金玉，其阴多青雄黄；杨水出焉，而西南流注于洛'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回阳虚之山吗？我送你一程。累了也可以在这里歇一歇。」', [['回阳虚之山', 'go'], ['歇一歇', 'rest'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'yangxu', 16, 7, 'left'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['label', 'stay'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'renyu'], [15, 'renyu'], [20, 'renyu']]],
];
