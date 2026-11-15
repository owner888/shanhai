<?php
// 马成之山, two hundred li northeast. The classic: patterned stone on it, gold and jade on its north side; the beast 天马
// lives there, a white dog with a black head, which flies when it sees someone; and the bird 鶌鶋.
declare(strict_types=1);

return [
    'id' => 'macheng',
    'name' => '马成之山',
    'range' => 10,
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
    'exits' => ['left' => ['longhou', 0], 'right' => ['xianshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '马成之山 —— 其上多文石，其阴多金玉'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the three areas before the reference's tenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[17, 'tianma'], [17, 'quju'], [18, 'tianma'], [18, 'quju'], [18, 'tianma'], [16, 'quju'], [16, 'tianma']]],
];
