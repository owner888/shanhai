<?php
// 盂山, two hundred and twenty li north of 号山 (`yushan` is 羽山, `yushanxi` 玉山). The classic: on its north side much
// iron, on its south much copper; its beasts are mostly white wolves and white tigers, its birds white pheasants and
// 白翟; the 生水 rises on it and runs east into the 河. The white pheasant is 白翰 (data/beasts.php).
declare(strict_types=1);

return [
    'id' => 'yushansi',
    'name' => '盂山',
    'range' => 7,
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
        '^.......,,,,,......^',
        '^.......,,,,,......^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['baiyu', 0], 'right' => ['haoshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '盂山 —— 其兽多白狼、白虎，其鸟多白雉、白翟'],
        ]],
    ],
    // The tall grass: 白狼, 白虎, 白翰 and 白翟.
    'wild' => ['rate' => 6, 'slots' => [[13, 'bailang'], [14, 'baihan'], [15, 'baidi'], [14, 'baihu'], [15, 'bailang'], [15, 'baidi'], [15, 'baihu']]],
];
