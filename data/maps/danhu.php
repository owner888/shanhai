<?php
// 单狐之山, the first of the Northern Mountains' first range, which the classic walks north and the maps draw to the
// right. The classic: many 机 trees, and flowering herbs above; the 漨水 rises on it and runs west into the 泑水. It names
// no beast. The one who brought the player from 崦嵫之山 waits at its near end.
declare(strict_types=1);

return [
    'id' => 'danhu',
    'name' => '单狐之山',
    'range' => 8,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T....TT............T',
        'T....TT.....TT.....T',
        'T...........TT.....T',
        'T..................T',
        'T..................T',
        'T===================',
        'T..................T',
        'T~~~~~~............T',
        'T~~~~~~............T',
        'T..................T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['right' => ['qiuru', 0]],
    'signs' => [
        [9, 6, [
            ['say', '单狐之山 —— 多机木，其上多华草；漨水出焉，而西流注于泑水'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回崦嵫之山吗？我送你一程。也可以歇一歇、去兽栏，或者买几枚契印。」', [['回崦嵫之山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买契印', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'yanzi', 3, 7, 'right'],
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
];
