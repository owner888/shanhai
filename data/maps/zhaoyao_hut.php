<?php
// The shaman's hut at the foot of 招摇之山.
declare(strict_types=1);

return [
    'id' => 'zhaoyao_hut',
    'name' => '巫祝的小屋',
    'rows' => [
        'HHHHHHHH',
        'H______H',
        'H______H',
        'H______H',
        'H______H',
        'HHHDHHHH',
    ],
    'warps' => [
        [3, 5, 'zhaoyao', 5, 6, 'down'],
    ],
    'npcs' => [
        ['shaman', 4, 2, 'down', 'shaman', [
            ['ifFlag', 'metShaman', 'again'],
            ['say', '山海之间，异兽无数。你若想走遍群山，得先有一只与你同行的异兽。'],
            ['say', '鹿蜀、旋龟、灌灌，三者择一。等我把契印备好，你再来选。'],
            ['say', '在那之前，先让这只鹿蜀陪你走走。'],
            ['giveBeast', 'lushu', 5],
            ['setFlag', 'metShaman'],
            ['end'],
            ['label', 'again'],
            ['say', '契印还没备好。带着鹿蜀去山麓东边的深草里看看吧。'],
        ]],
    ],
];
