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
    'wild' => ['rate' => 10, 'slots' => [[23, 'zhuru'], [23, 'zhuru'], [25, 'zhuru'], [25, 'zhuru'], [27, 'zhuru'], [27, 'zhuru'], [25, 'zhuru']]],
];
