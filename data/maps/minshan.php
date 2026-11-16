<?php
// 岷山, three hundred li northeast; one of the range's 冢. The classic: the 江 rises on it and runs northeast into the sea,
// with many 良龟 and 鼍 in it; gold and jade on it, white 珉 below; 梅 and 棠; its beasts are mostly rhinoceros, elephants and
// 夔牛; its birds 翰 and 鷩.
declare(strict_types=1);

return [
    'id' => 'minshan',
    'name' => '岷山',
    'range' => 23,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^...........~~~~~~~^',
        '^...........~~~~~~~^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['nvjijiu', 0], 'right' => ['laishanjiu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '岷山 —— 江水出焉，东北流注于海，其中多良龟，多鼍；其上多金玉，其下多白珉'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'lianggui'], [15, 'tuo'], [20, 'lianggui']]],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[42, 'xi'], [43, 'xiang'], [43, 'kuiniu'], [45, 'xi'], [44, 'xiang'], [46, 'kuiniu'], [46, 'xi']]],
];
