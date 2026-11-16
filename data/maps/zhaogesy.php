<?php
// 朝歌之山, a hundred and fifty li northeast (`zhaoge` is the 朝歌之山 of the fifth). The classic: the 潕水 rises on it and runs
// southeast into the 荥, with many 人鱼 in it; 梓 and 楠; its beasts are mostly 麢 and elk; a herb called 莽草 that poisons
// fish.
declare(strict_types=1);

return [
    'id' => 'zhaogesy',
    'name' => '朝歌之山',
    'range' => 25,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T...........~~~~~~~T',
        'T...TT......~~~~~~~T',
        'T...TT.............T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['yiwangzhong', 0], 'right' => ['diqun', 0]],
    'signs' => [
        [9, 6, [
            ['say', '朝歌之山 —— 潕水出焉，东南流注于荥，其中多人鱼；其上多梓楠，其兽多麢麋'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'renyu'], [15, 'renyu'], [20, 'renyu']]],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[46, 'ling'], [47, 'mi'], [47, 'ling'], [49, 'mi'], [48, 'ling'], [50, 'mi'], [50, 'ling']]],
];
