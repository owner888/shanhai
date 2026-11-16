<?php
// 狱法之山, two hundred li north of 少咸之山. The classic: the 瀤泽之水 rises on it and runs northeast into 泰泽, and in it
// is a fish like a carp with a cock's feet; the beast 山晖 lives there, a dog with a human face, which throws, laughs
// at who it sees and goes like the wind, and at whose sight there is great wind.
declare(strict_types=1);

return [
    'id' => 'yufa',
    'name' => '狱法之山',
    'range' => 8,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....~~~~~~~^',
        '^..,,,,,....~~~~~~~^',
        '^..,,,,,...........^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^..,,,,,...........^',
        '^..,,,,,...........^',
        '^..,,,,,...........^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['shaoxian', 0], 'right' => ['beiyue', 0]],
    'signs' => [
        [9, 6, [
            ['say', '狱法之山 —— 瀤泽之水出焉，而东北流注于泰泽'],
        ]],
    ],
    // The 瀤泽之水: 䲃鱼.
    'water' => ['rate' => 2, 'slots' => [[20, 'zaoyu'], [15, 'zaoyu'], [20, 'zaoyu']]],
    // The tall grass: 山晖.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 橐驼、麢、蕃、橐驼 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 2, 'slots' => [[24, 'tuotuo'], [25, 'ling'], [25, 'fan'], [26, 'tuotuo'], [22, 'shanhui'], [24, 'shanhui'], [26, 'shanhui']]],
];
