<?php
// 江浮之山, a hundred li southeast. The classic: silver and whetstone on it; no grass or trees; its beasts are mostly boar
// and deer.
declare(strict_types=1);

return [
    'id' => 'jiangfu',
    'name' => '江浮之山',
    'range' => 26,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^--:::::----:::::--^',
        '^--:::::----:::::--^',
        '^--:::::----:::::--^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^-------:::::------^',
        '^-------:::::^^----^',
        '^-------:::::^^----^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['yaoshanyao', 0], 'right' => ['zhenling', 0]],
    'signs' => [
        [9, 6, [
            ['say', '江浮之山 —— 其上多银、砥砺，无草木，其兽多豕鹿'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[39, 'yeshi'], [40, 'yelu'], [40, 'yeshi'], [42, 'yelu'], [41, 'yeshi'], [43, 'yelu'], [43, 'yeshi']]],
];
