<?php
// 倚帝之山, thirty li east. The classic: jade on it, gold below; the beast 狙如 lives there, a 鼣 rat with white ears and a
// white beak, at whose sight a state has great war.
declare(strict_types=1);

return [
    'id' => 'yidi',
    'name' => '倚帝之山',
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
    'exits' => ['left' => ['beishansy', 0], 'right' => ['nishan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '倚帝之山 —— 其上多玉，其下多金'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[46, 'juru'], [47, 'juru'], [47, 'juru'], [49, 'juru'], [48, 'juru'], [50, 'juru'], [50, 'juru']]],
];
