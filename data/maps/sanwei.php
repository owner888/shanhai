<?php
// 三危之山, two hundred and twenty li west of 符惕之山. The classic: the three blue birds live there; the mountain is
// round, a hundred li across; the beast 獓因 lives there, which eats people, and the bird 鴟, one head on three bodies.
declare(strict_types=1);

return [
    'id' => 'sanwei',
    'name' => '三危之山',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^.,,,,,,....,,,,,,.^',
        '^.,,,,,,....,,,,,,.^',
        '^.,,,,,,....,,,,,,.^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^......,,,,,,......^',
        '^......,,,,,,......^',
        '^......,,,,,,......^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['guishanxi', 0], 'right' => ['futi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '三危之山 —— 三青鸟居之；是山也，广员百里'],
        ]],
    ],
    // The tall grass: 獓因, 鴟 and the three blue birds.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 积蛇、鹰鸇、积蛇、鹰鸇 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 2, 'slots' => [[16, 'jishe'], [16, 'yingzhan'], [16, 'jishe'], [16, 'yingzhan'], [15, 'chi'], [15, 'sanqingniao'], [15, 'aoye']]],
];
