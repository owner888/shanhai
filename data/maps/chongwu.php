<?php
// 崇吾之山, the first of the Western Mountains' third range, walked west and northwest. The classic: a tree grows there
// with round leaves and a white calyx, red flowers and black grain, whose fruit is like the 枳's and is good for one's
// descendants; the beast 举父 lives there, and the bird 蛮蛮, which has one wing and one eye and flies only paired, and
// at whose sight there is flood. The one who brought the player from 莱山 waits at its east end.
declare(strict_types=1);

return [
    'id' => 'chongwu',
    'name' => '崇吾之山',
    'range' => 6,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,.TT.,,,,,..T',
        'T..,,,,,.TT.,,,,,..T',
        'T..................T',
        'T..................T',
        '===================T',
        'T..................T',
        'T.......,,,,,......T',
        'T.......,,,,,......T',
        'T.......,,,,,......T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['changsha', 0]],
    'signs' => [
        [10, 6, [
            ['say', '崇吾之山 —— 有木焉，员叶而白柎，赤华而黑理，其实如枳，食之宜子孙'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 18, 7, 'left', 'guard', [
            ['ask', '「要回莱山吗？我送你一程。也可以歇一歇、去兽栏，或者买点东西。」', [['回莱山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买东西', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'laishan', 3, 7, 'right'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['end'],
            ['label', 'pen'],
            ['pen'],
            ['end'],
            ['label', 'shop'],
            ['shop', ['seal', 'seal2', 'bili', 'licao', 'kangmu', 'rongcao', 'zhichu', 'wutiao', 'huangji', 'kuxin', 'yansuan', 'yuzhang']],
            ['label', 'stay'],
        ]],
    ],
    // The tall grass: 举父 and 蛮蛮. The range's levels are those before the reference's sixth gym, which are the fifth's: no new ground lies between the two (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 积蛇、鹰鸇、积蛇、鹰鸇 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[16, 'jishe'], [16, 'yingzhan'], [16, 'jishe'], [16, 'yingzhan'], [13, 'manman'], [13, 'jufu'], [13, 'manman']]],
];
