<?php
// 单张之山, a hundred and eighty li north of 蔓联之山. The classic: no grass or trees; the beast 诸犍 lives there, a
// long-tailed leopard with a human head, an ox's ears and one eye; and the bird 白鵺; the 栎水 rises on it and runs
// south into the 杠水.
declare(strict_types=1);

return [
    'id' => 'danzhang',
    'name' => '单张之山',
    'range' => 8,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^--:::::----:::::--^',
        '^--:::::----:::::--^',
        '^--:::::----:::::--^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^^^^^^^^^~~^^^^^^^^^',
    ],
    'exits' => ['left' => ['manlian', 0], 'right' => ['guanti', 0]],
    'signs' => [
        [11, 6, [
            ['say', '单张之山 —— 其上无草木；栎水出焉，而南流注于杠水'],
        ]],
    ],
    // The scree: 诸犍 and 白鵺.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 麢、蕃、马、麢 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 2, 'slots' => [[23, 'ling'], [24, 'fan'], [24, 'ma'], [25, 'ling'], [26, 'baiye'], [22, 'zhujian'], [24, 'baiye']]],
];
