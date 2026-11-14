<?php
// 招摇之山, its foot by the Western Sea. The classic: it stands over the Western Sea, with many cassia trees,
// much gold and jade; 狌狌 lives there.
declare(strict_types=1);

return [
    'id' => 'zhaoyao',
    'name' => '招摇之山 · 山麓',
    'rows' => [
        '~~~TTTTTTTTTTTTTTTTT',
        '~~~T......TT.....,,T',
        '~~..........T...,,,T',
        '~~..HHHH.......,,,,T',
        '~~..HHHH...=...,,,,T',
        '~~..HDHH...=....,,.T',
        '~~...=.....=.......T',
        '~~...=======.......T',
        '~~~........=...^...T',
        '~~~..T.....=..^^...T',
        '~~.........=.......T',
        '~~....T....=....T..T',
        '~~~........=.......T',
        '~~~~.......=.....TTT',
        '~~~~TTTTTTT=TTTTTTTT',
    ],
    'warps' => [
        [5, 5, 'zhaoyao_hut', 3, 4, 'up'],
    ],
    'signs' => [
        [12, 7, [
            ['say', '招摇之山 —— 西海之滨'],
        ]],
    ],
    'npcs' => [
        ['elder', 8, 5, 'down', 'elder', [
            ['ifFlag', 'metElder', 'again'],
            ['say', '年轻人，这里是招摇之山，脚下就是西海。山上多桂树，也多金玉。'],
            ['say', '山中有兽，名叫狌狌，长得像猿猴，一对白耳，跑起来谁也追不上。想见它，就往东边的深草里去。'],
            ['setFlag', 'metElder'],
            ['end'],
            ['label', 'again'],
            ['say', '屋里的巫祝在等你，进去看看吧。'],
        ]],
        ['guard', 11, 13, 'up', 'guard', [
            ['say', '再往前的山路还没修通，改日再来吧。'],
        ]],
    ],
    'triggers' => [
        [3, 10, 'seenSea', [
            ['say', '海风从西海上吹来，带着桂花的香气。'],
        ]],
    ],
];
