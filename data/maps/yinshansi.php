<?php
// 阴山, the first of the Western Mountains' fourth range, walked north and then west: another of the name (the third
// range's is `yinshan`). The classic: on it much 榖 and no stone; the 阴水 rises on it and runs west into the 洛. It
// names no beast. The one who brought the player from 翼望之山 waits at its east end.
declare(strict_types=1);

return [
    'id' => 'yinshansi',
    'name' => '阴山',
    'range' => 7,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T....TT............T',
        'T....TT.....TT.....T',
        'T...........TT.....T',
        'T..................T',
        'T..................T',
        '===================T',
        'T..................T',
        'T~~~~~~~...........T',
        'T~~~~~~~...........T',
        'T..................T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['laoshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '阴山 —— 上多榖，无石；阴水出焉，西流注于洛'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 18, 7, 'left', 'guard', [
            ['ask', '「要回翼望之山吗？我送你一程。也可以歇一歇、去兽栏，或者买几枚契印。」', [['回翼望之山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买契印', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'yiwang', 3, 7, 'right'],
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
