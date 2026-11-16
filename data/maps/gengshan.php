<?php
// 耿山, three hundred li south. The classic: no grass or trees, much 水碧, many great snakes; the beast 朱獳 lives there, a
// fox with a fish's fins, at whose sight a state is afraid. The great snakes, of which the classic says nothing more,
// are not in the data.
declare(strict_types=1);

return [
    'id' => 'gengshan',
    'name' => '耿山',
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
    'exits' => ['left' => ['dufu', 0], 'right' => ['luqi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '耿山 —— 无草木，多水碧，多大蛇'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's twelfth gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 鹿、虎、麋、鹿 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[23, 'yelu'], [23, 'hu'], [25, 'mi'], [25, 'yelu'], [27, 'zhuru'], [27, 'zhuru'], [25, 'zhuru']]],
];
