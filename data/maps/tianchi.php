<?php
// 天池之山, two hundred li northeast. The classic: no grass or trees, much patterned stone; the beast 飞鼠 lives there, a hare
// with a rat's head, which flies by its back; the 渑水 comes out on it and goes under below.
declare(strict_types=1);

return [
    'id' => 'tianchi',
    'name' => '天池之山',
    'range' => 10,
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
        '^------------------^',
        '^-------~~~--------^',
        '^-------~~~--------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['xianshan', 0], 'right' => ['yangshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '天池之山 —— 其上无草木，多文石；渑水出焉，潜于其下'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the three areas before the reference's tenth gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 白蛇、橐驼、鹠、白蛇 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[17, 'baishe'], [19, 'tuotuo'], [16, 'liu'], [17, 'baishe'], [16, 'feishu'], [18, 'feishu'], [18, 'feishu']]],
];
