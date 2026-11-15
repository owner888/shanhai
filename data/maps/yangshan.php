<?php
// 阳山, three hundred li east. The classic: jade on it, gold and copper below; the beast 领胡 lives there, an ox with a red
// tail and a lump on its neck; the bird 象蛇, a five-coloured hen pheasant; the 留水 rises on it and runs south into the 河,
// and in it is the fish 䱤父.
declare(strict_types=1);

return [
    'id' => 'yangshan',
    'name' => '阳山',
    'range' => 10,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^^^^^^^^^~~^^^^^^^^^',
    ],
    'exits' => ['left' => ['tianchi', 0], 'right' => ['benwen', 0]],
    'signs' => [
        [12, 6, [
            ['say', '阳山 —— 其上多玉，其下多金铜；留水出焉，而南流注于河'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'xianfu'], [15, 'xianfu'], [20, 'xianfu']]],
    // Its wild ground. The range's levels are those of the three areas before the reference's tenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[13, 'linghu'], [13, 'xiangshe'], [15, 'linghu'], [12, 'xiangshe'], [14, 'linghu'], [14, 'xiangshe'], [14, 'linghu']]],
];
