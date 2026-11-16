<?php
// 犲山, three hundred li south. The classic: no grass or trees on it, much water below, with many 堪㐨之鱼 in it; a beast
// lives there shaped like 夸父 with a pig's bristles, which calls like a shout, and at whose sight the world has floods.
// The classic gives it no name.
declare(strict_types=1);

return [
    'id' => 'chaishan',
    'name' => '犲山',
    'range' => 11,
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
    'exits' => ['left' => ['yueshan', 0], 'right' => ['dushan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '犲山 —— 其上无草木，其下多水'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'kanyu'], [15, 'kanyu'], [20, 'kanyu']]],
    // Its wild ground. The range's levels are those of the three areas before the reference's eleventh gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 麋、鹿、虎、麋 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[30, 'mi'], [29, 'yelu'], [31, 'hu'], [32, 'mi'], [33, 'chaishou'], [33, 'chaishou'], [33, 'chaishou']]],
];
