<?php
// 凫丽之山, five hundred li south. The classic: gold and jade on it, needle-stone below; the beast 蠪侄 lives there, a fox
// with nine tails, nine heads and a tiger's claws, which cries like a baby and eats people.
declare(strict_types=1);

return [
    'id' => 'fuli',
    'name' => '凫丽之山',
    'range' => 12,
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
    'exits' => ['left' => ['gufeng', 0], 'right' => ['yinshandong', 0]],
    'signs' => [
        [9, 6, [
            ['say', '凫丽之山 —— 其上多金玉，其下多箴石'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's twelfth gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 麋、鹿、虎、麋 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[23, 'mi'], [23, 'yelu'], [25, 'hu'], [25, 'mi'], [27, 'longzhi'], [27, 'longzhi'], [25, 'longzhi']]],
];
