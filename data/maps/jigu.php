<?php
// 即谷之山, thirty-five li southeast. The classic: fine jade; many black leopards, 闾, 麈, 麢 and 㚟; 珉 on its south side, 青雘 on
// its north.
declare(strict_types=1);

return [
    'id' => 'jigu',
    'name' => '即谷之山',
    'range' => 25,
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
    'exits' => ['left' => ['yigu', 0], 'right' => ['jishansy', 0]],
    'signs' => [
        [9, 6, [
            ['say', '即谷之山 —— 多美玉，多玄豹，多闾麈，多麢㚟；其阳多珉，其阴多青雘'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[38, 'xuanbao'], [39, 'lyu'], [39, 'zhulu'], [41, 'ling'], [40, 'chuotu'], [42, 'xuanbao'], [42, 'lyu']]],
];
