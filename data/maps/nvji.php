<?php
// 女几之山, a hundred and twenty li northeast. The classic: jade on it, gold below; its beasts are mostly leopards and
// tigers, 闾, elk, 麖 and 麂; its birds 白鷮, 翟 and 鸩. The tigers and the 翟 are not on this map's ground, which holds seven.
declare(strict_types=1);

return [
    'id' => 'nvji',
    'name' => '女几之山',
    'range' => 22,
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
        '^.......,,,,,......^',
        '^.......,,,,,^^....^',
        '^.......,,,,,^^....^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['jiaoshanba', 0], 'right' => ['yizhu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '女几之山 —— 其上多玉，其下多黄金，其兽多豹虎，多闾麋麖麂，其鸟多白鷮，多翟，多鸩'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[40, 'zhenniao'], [41, 'baijiao'], [41, 'jilu'], [43, 'jinglu'], [42, 'lyu'], [44, 'mi'], [44, 'bao']]],
];
