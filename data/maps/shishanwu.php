<?php
// 尸山, ten li east. The classic: grey jade; its beasts are mostly 麖; the 尸水 rises on it and runs south into the 洛, with
// fine jade in it.
declare(strict_types=1);

return [
    'id' => 'shishanwu',
    'name' => '尸山',
    'range' => 19,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^^^^^^^^^~~^^^^^^^^^',
    ],
    'exits' => ['left' => ['lishanwu', 0], 'right' => ['liangyu', 0]],
    'signs' => [
        [12, 6, [
            ['say', '尸山 —— 多苍玉，其兽多麖；尸水出焉，南流注于洛水'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[31, 'jinglu'], [32, 'jinglu'], [32, 'jinglu'], [34, 'jinglu'], [33, 'jinglu'], [35, 'jinglu'], [35, 'jinglu']]],
];
