<?php
// 钟山, four hundred and twenty li northwest of 峚山. The classic: its son was 鼓, a human face on a dragon's body, who
// with 钦䲹 killed 葆江 at 昆仑; the god put both to death; 钦䲹 became the bird 大鹗, at whose sight there is great war,
// and 鼓 the bird 鵕鸟, at whose sight there is drought. The two birds are what the grass holds.
declare(strict_types=1);

return [
    'id' => 'zhongshan',
    'name' => '钟山',
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
        '^..................^',
        '^.......^^^........^',
        '^.......^^^........^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['taiqi', 0], 'right' => ['mishan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '钟山 —— 其子曰鼓，其状如人面而龙身'],
        ]],
    ],
    // The tall grass: 鵕鸟 and 大鹗.
    'wild' => ['rate' => 2, 'slots' => [[16, 'junniao'], [16, 'dae'], [16, 'junniao'], [16, 'dae'], [15, 'junniao'], [15, 'dae'], [15, 'junniao']]],
];
