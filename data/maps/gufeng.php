<?php
// 姑逢之山, three hundred li south. The classic: no grass or trees, gold and jade; the beast 獙獙 lives there, a fox with
// wings, which calls like a wild goose, and at whose sight the world has drought.
declare(strict_types=1);

return [
    'id' => 'gufeng',
    'name' => '姑逢之山',
    'range' => 12,
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
        '^-------:::::------^',
        '^-------:::::^^----^',
        '^-------:::::^^----^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['goushi', 0], 'right' => ['fuli', 0]],
    'signs' => [
        [9, 6, [
            ['say', '姑逢之山 —— 无草木，多金玉'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's twelfth gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 虎、麋、鹿、虎 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[26, 'hu'], [26, 'mi'], [28, 'yelu'], [28, 'hu'], [30, 'bibi'], [30, 'bibi'], [28, 'bibi']]],
];
