<?php
// 帝囷之山, two hundred li southeast. The classic: 㻬琈 jade on its south side, iron on its north; the 帝囷之水 comes out on it
// and goes under below it, with many 鸣蛇: met on the ground by the water, being of fire.
declare(strict_types=1);

return [
    'id' => 'diqun',
    'name' => '帝囷之山',
    'range' => 25,
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
        '^..................^',
        '^.......~~~........^',
        '^.......~~~........^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['zhaogesy', 0], 'right' => ['shishanshi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '帝囷之山 —— 其阳多㻬琈之玉，其阴多铁；帝囷之水出于其上，潜于其下，多鸣蛇'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 鸜鹆、玄豹、闾、鸜鹆 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[46, 'quyu'], [47, 'xuanbao'], [47, 'lyu'], [49, 'quyu'], [48, 'mingshe'], [50, 'mingshe'], [50, 'mingshe']]],
];
