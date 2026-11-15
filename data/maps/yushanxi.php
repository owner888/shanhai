<?php
// 玉山, three hundred and fifty li west of 蠃母之山 (`yushan` is 羽山, of the south). The classic: it is where 西王母
// lives, a human shape with a leopard's tail and a tiger's teeth, who keeps heaven's plagues; the beast 狡 lives there,
// at whose sight the harvest is great, and the bird 胜遇, at whose sight there is flood.
declare(strict_types=1);

return [
    'id' => 'yushanxi',
    'name' => '玉山',
    'range' => 6,
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
        '^..........,,,,,...^',
        '^...^^^....,,,,,...^',
        '^...^^^....,,,,,...^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['xuanyuan', 0], 'right' => ['luomushan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '玉山 —— 是西王母所居也；其状如人，豹尾虎齿而善啸，蓬发戴胜'],
        ]],
    ],
    // The tall grass: 狡 and 胜遇.
    'wild' => ['rate' => 10, 'slots' => [[16, 'jiao'], [16, 'shengyu'], [16, 'jiao'], [16, 'shengyu'], [13, 'jiao'], [13, 'shengyu'], [13, 'jiao']]],
];
