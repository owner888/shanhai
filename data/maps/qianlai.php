<?php
// 钱来之山, the first of the Western Mountains' first range, which the classic walks westward: each map's way on is to
// the left. The classic: on it many pines, below it much 洗石, a stone to wash with; 羬羊 lives there. The one who
// brought the player from 南禺之山 waits at its east end.
declare(strict_types=1);

return [
    'id' => 'qianlai',
    'name' => '钱来之山',
    'range' => 4,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T.TT..TTT...TT..TT.T',
        'T..T...T....T....T.T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..................T',
        'T..................T',
        '===================^',
        '^..................^',
        '^..^^....,,,,..^^..^',
        '^..^^....,,,,..^^..^',
        '^........,,,,......^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['songguo', 0]],
    'signs' => [
        [9, 6, [
            ['say', '钱来之山 —— 其上多松，其下多洗石'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 18, 7, 'left', 'guard', [
            ['ask', '「要回南禺之山吗？我送你一程。也可以在这里歇一歇，或者去兽栏看看。」', [['回南禺之山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'nanyu', 12, 7, 'right'],
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
    // The tall grass: 羬羊. Its levels are those of the first area before the reference's fourth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[4, 'qianyang'], [4, 'qianyang'], [5, 'qianyang'], [5, 'qianyang'], [5, 'qianyang'], [6, 'qianyang'], [6, 'qianyang']]],
];
