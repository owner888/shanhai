<?php
// 又原之山, twenty li west. The classic: 青雘 on its south side, iron on its north; its birds are mostly 鸜鹆.
declare(strict_types=1);

return [
    'id' => 'youyuan',
    'name' => '又原之山',
    'range' => 24,
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
    'exits' => ['left' => ['chushan', 0], 'right' => ['zhuoshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '又原之山 —— 其阳多青雘，其阴多铁，其鸟多鸜鹆'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[44, 'quyu'], [45, 'quyu'], [45, 'quyu'], [47, 'quyu'], [46, 'quyu'], [48, 'quyu'], [48, 'quyu']]],
];
