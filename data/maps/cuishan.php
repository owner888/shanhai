<?php
// 翠山, two hundred li west of 黄山. The classic: on it many 棕 and 枏, below it much bamboo; on its south side much
// gold and jade; on its north many 旄牛, 麢 and 麝; its birds are mostly 鸓. So the grass that holds them is on the
// north side.
declare(strict_types=1);

return [
    'id' => 'cuishan',
    'name' => '翠山',
    'range' => 4,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..,,,,,,..,,,,,,..T',
        'T..,,,,,,..,,,,,,..T',
        'T..,,,,,,..,,,,,,..T',
        'T..................T',
        'T....TT......TT....T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T...^^.......^^^...T',
        'T...^^.......^^^...T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['guishan4', 0], 'right' => ['huangshan', 0]],
    'signs' => [
        [10, 6, [
            ['say', '翠山 —— 其阳多黄金、玉，其阴多旄牛、麢、麝'],
        ]],
    ],
    // The tall grass, on the north side: 旄牛, 麢, 麝, and the bird 鸓.
    'wild' => ['rate' => 10, 'slots' => [[13, 'ling'], [14, 'she'], [15, 'maoniu'], [16, 'ling'], [15, 'leiniao'], [15, 'leiniao'], [15, 'leiniao']]],
];
