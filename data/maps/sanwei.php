<?php
// 三危之山, two hundred and twenty li west of 符惕之山. The classic: the three blue birds live there; the mountain is
// round, a hundred li across; the beast 𢕟𢓨 lives there, which eats people, and the bird 鴟, one head on three bodies.
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
    // The tall grass: 𢕟𢓨, 鴟 and the three blue birds.
    'wild' => ['rate' => 2, 'slots' => [[16, 'chi'], [16, 'sanqingniao'], [16, 'aoye'], [16, 'chi'], [15, 'sanqingniao'], [15, 'aoye'], [15, 'chi']]],
];
