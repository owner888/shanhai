<?php
// 剡山, two hundred li northeast. The classic: gold and jade; the beast 合窳 lives there, a pig with a man's face, a yellow
// body and a red tail, which cries like a baby, eats people and snakes, and at whose sight the world has floods.
declare(strict_types=1);

return [
    'id' => 'shanshan',
    'name' => '剡山',
    'range' => 14,
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
    'exits' => ['left' => ['zitong', 0], 'right' => ['taishandong', 0]],
    'signs' => [
        [9, 6, [
            ['say', '剡山 —— 多金玉'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's fourteenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[5, 'heyu'], [5, 'heyu'], [8, 'heyu'], [10, 'heyu'], [10, 'heyu'], [10, 'heyu'], [10, 'heyu']]],
];
