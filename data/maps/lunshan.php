<?php
// 伦山, five hundred li north. The classic: the 伦水 rises on it and runs east into the 河; a beast lives there like a 麋, its
// vent on its tail.
declare(strict_types=1);

return [
    'id' => 'lunshan',
    'name' => '伦山',
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
        '^..................^',
        '^...........~~~~~~~^',
        '^...........~~~~~~~^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['qianshanbei', 0], 'right' => ['jieshi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '伦山 —— 伦水出焉，而东流注于河'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the three areas before the reference's tenth gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 鹠、白蛇、橐驼、鹠 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[17, 'liu'], [17, 'baishe'], [18, 'tuotuo'], [18, 'liu'], [18, 'pijiu'], [16, 'pijiu'], [16, 'pijiu']]],
];
