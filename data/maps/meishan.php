<?php
// 美山, a hundred li northeast. The classic: its beasts are mostly 兕 and oxen, 闾 and 麈, boar and deer; gold on it, 青雘
// below.
declare(strict_types=1);

return [
    'id' => 'meishan',
    'name' => '美山',
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
    'exits' => ['left' => ['tongshan', 0], 'right' => ['dayao', 0]],
    'signs' => [
        [9, 6, [
            ['say', '美山 —— 其兽多兕牛，多闾麈，多豕鹿，其上多金，其下多青雘'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[34, 'si'], [35, 'lyu'], [35, 'zhulu'], [37, 'yeshi'], [36, 'yelu'], [38, 'si'], [38, 'lyu']]],
];
