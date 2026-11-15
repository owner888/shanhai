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
            ['ask', '「要回莱山吗？我送你一程。累了也可以在这里歇一歇。」', [['回莱山', 'go'], ['歇一歇', 'rest'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'laishan', 3, 7, 'right'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['label', 'stay'],
        ]],
    ],
    // The tall grass: 举父 and 蛮蛮. The range's levels are those before the reference's sixth gym, which are the fifth's: no new ground lies between the two (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[16, 'jufu'], [16, 'manman'], [16, 'jufu'], [16, 'manman'], [13, 'jufu'], [13, 'manman'], [13, 'jufu']]],
];
