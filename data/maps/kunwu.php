<?php
// 昆吾之山, two hundred li west. The classic: red copper on it; the beast 蠪蚳 lives there, a pig with horns, which sounds
// like wailing.
declare(strict_types=1);

return [
    'id' => 'kunwu',
    'name' => '昆吾之山',
    'range' => 16,
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
    'exits' => ['left' => ['yangshanzhong', 0], 'right' => ['jianshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '昆吾之山 —— 其上多赤铜'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's sixteenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[3, 'longchi'], [3, 'longchi'], [5, 'longchi'], [4, 'longchi'], [6, 'longchi'], [7, 'longchi'], [7, 'longchi']]],
];
