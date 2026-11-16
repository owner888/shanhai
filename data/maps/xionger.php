<?php
// 熊耳之山, two hundred li west. The classic: 漆 trees on it, 棕 below; the 浮濠之水 rises on it and runs west into the 洛, with
// crystal and many 人鱼 in it; a herb called 葶苧 that poisons fish.
declare(strict_types=1);

return [
    'id' => 'xionger',
    'name' => '熊耳之山',
    'range' => 18,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T.............^^...T',
        'T.............^^...T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T~~~~~~~...........T',
        'T~~~~~~~...........T',
        'T..................T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['baibian', 0], 'right' => ['mushan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '熊耳之山 —— 其上多漆，其下多棕；浮濠之水出焉，而西流注于洛'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'renyu'], [15, 'renyu'], [20, 'renyu']]],
];
