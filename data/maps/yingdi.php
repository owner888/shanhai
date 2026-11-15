<?php
// 英鞮之山, three hundred and fifty li west of 刚山之尾. The classic: on it many 漆木, below it much gold and jade; its
// birds and beasts are all white; the 涴水 rises on it and runs north into the marsh of 陵羊, and in it is the fish 冉遗,
// a fish's body with a snake's head and six feet.
declare(strict_types=1);

return [
    'id' => 'yingdi',
    'name' => '英鞮之山',
    'range' => 7,
    'rows' => [
        'TTTTTTTTTT~~TTTTTTTT',
        'T......~~~~~~~~....T',
        'T......~~~~~~~~....T',
        'T......~~~~~~~~....T',
        'T.........~~.......T',
        'T.........~~.......T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['zhongqu', 0], 'right' => ['gangwei', 0]],
    'signs' => [
        [9, 6, [
            ['say', '英鞮之山 —— 上多漆木，下多金玉，鸟兽尽白'],
        ]],
    ],
    // The 涴水: 冉遗之鱼.
    'water' => ['rate' => 2, 'slots' => [[20, 'ranyi'], [15, 'ranyi'], [20, 'ranyi']]],
];
