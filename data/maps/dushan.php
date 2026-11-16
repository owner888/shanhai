<?php
// 独山, three hundred li south. The classic: gold and jade on it, fine stone below; the 末涂之水 rises on it and runs
// southeast into the 沔, and in it are many 条庸, yellow snakes with a fish's fins, which shine as they come and go, and at
// whose sight a town has drought: met here on the ground by the water, being of fire (docs/elements.md 6).
declare(strict_types=1);

return [
    'id' => 'dushan',
    'name' => '独山',
    'range' => 11,
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
        '^...........~~~~~~~^',
        '^...........~~~~~~~^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['chaishan', 0], 'right' => ['taishan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '独山 —— 其上多金玉，其下多美石；末涂之水出焉，而东南流注于沔'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the three areas before the reference's eleventh gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 鹿、虎、麋、鹿 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[26, 'yelu'], [27, 'hu'], [28, 'mi'], [29, 'yelu'], [29, 'tiaoyong'], [30, 'tiaoyong'], [30, 'tiaoyong']]],
];
