<?php
// 招摇之山, its foot by the Western Sea. The classic: it stands over the Western Sea, with many cassia trees,
// much gold and jade; 狌狌 lives there.
declare(strict_types=1);

// With no beast yet, the elder calls the player back from the tall grass and from the road out, as the reference turns
// the player back from leaving town without one; the shaman's giving one sets the flag that ends it.
$back = fn(string $dir) => [
    ['say', '「等一等！」'],
    ['say', '「身边还没有异兽，可别往深草里、山外头走。草里的异兽会扑出来的。」'],
    ['say', '「屋里的巫祝在等你，先去见见她。」'],
    ['move', 'player', $dir, 1],
];

return [
    'id' => 'zhaoyao',
    'name' => '招摇之山 · 山麓',
    'range' => 1,
    'rows' => [
        '~~~TTTTTTTTTTTTTTTTT',
        '~~~T......TT.....,,T',
        '~~..........T...,,,T',
        '~~..HHHH.......,,,,T',
        '~~..HHHH...=...,,,,T',
        '~~..HDHH...=....,,.T',
        '~~...=.....=========',
        '~~...=======.......T',
        '~~~........=...^...T',
        '~~~..T.....=..^^...T',
        '~~.........=.......T',
        '~~....T....=....T..T',
        '~~~........=.......T',
        '~~~~.......=.....TTT',
        '~~~~TTTTTTT=TTTTTTTT',
    ],
    // East to 堂庭之山, whose road meets the edge a row lower.
    'exits' => ['right' => ['tangting', 1]],
    'warps' => [
        [5, 5, 'zhaoyao_hut', 3, 4, 'up'],
    ],
    'signs' => [
        [12, 7, [
            ['say', '招摇之山 —— 西海之滨'],
            ['ifFlag', 'found_mihu', 'had'],
            ['setFlag', 'found_mihu'],
            ['give', 'mihu', 1],
            ['say', '牌子旁边长着迷榖。你采了一份。'],
            ['label', 'had'],
        ]],
    ],
    'npcs' => [
        ['elder', 8, 5, 'down', 'elder', [
            ['ifFlag', 'metElder', 'again'],
            ['say', '年轻人，这里是招摇之山，脚下就是西海。山上多桂树，也多金玉。'],
            ['say', '山中有兽，名叫狌狌，长得像猿猴，一对白耳，跑起来谁也追不上。想见它，就往东边的深草里去。'],
            ['say', '顺着大路再往东，就是堂庭之山。'],
            ['setFlag', 'metElder'],
            ['end'],
            ['label', 'again'],
            ['say', '屋里的巫祝在等你，进去看看吧。'],
        ]],
        ['guard', 11, 13, 'up', 'guard', [
            ['say', '再往前的山路还没修通，改日再来吧。'],
        ]],
    ],
    // The tall grass: 狌狌, the one beast the classic gives this mountain.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 蝮虫、白猿、蝮虫、白猿 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[2, 'fuchong'], [2, 'baiyuan'], [3, 'fuchong'], [3, 'baiyuan'], [2, 'xingxing'], [3, 'xingxing'], [3, 'xingxing']]],
    // The tall grass's edge, each tile turning the player back the way out of it; and the road's end, where it leaves the map.
    'stops' => [
        [17, 1, 'metShaman', $back('left')], [16, 2, 'metShaman', $back('left')], [15, 3, 'metShaman', $back('left')],
        [15, 4, 'metShaman', $back('left')], [18, 4, 'metShaman', $back('down')], [16, 5, 'metShaman', $back('down')],
        [17, 5, 'metShaman', $back('down')], [19, 6, 'metShaman', $back('left')],
    ],
    'triggers' => [
        [3, 10, 'seenSea', [
            ['say', '海风从西海上吹来，带着桂花的香气。'],
        ]],
    ],
];
