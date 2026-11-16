<?php
// 黄山, a hundred and eighty li west of 皋涂之山. The classic: no grass or trees, much bamboo; the 盼水 rises on it and
// runs west into the 赤水, with much jade in it; the beast 敏 lives there, and the bird 鹦䳇. With no grass, they are
// met on the scree.
declare(strict_types=1);

return [
    'id' => 'huangshan',
    'name' => '黄山',
    'range' => 4,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^--::::::---:::::--^',
        '^--::::::---:::::--^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '~~~~~~~~-----::::--^',
        '~~~~~~~~~----::::--^',
        '^----~~~~----::::--^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['cuishan', 0], 'right' => ['gaotu', 0]],
    'signs' => [
        [10, 6, [
            ['say', '黄山 —— 无草木，多竹箭；盼水出焉，西流注于赤水'],
        ]],
    ],
    // The scree: 敏 and the bird 鹦䳇.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 兕、罴、麢、兕 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 6, 'slots' => [[14, 'si'], [14, 'pi'], [16, 'ling'], [15, 'si'], [12, 'yingwu'], [16, 'minniu'], [16, 'yingwu']]],
];
