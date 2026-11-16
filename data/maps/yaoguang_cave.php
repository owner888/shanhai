<?php
// The hole in 尧光之山 where 猾褢 lives. In a cave a wild beast may be met at every step.
declare(strict_types=1);

return [
    'id' => 'yaoguang_cave',
    'name' => '尧光之山 · 穴',
    'range' => 2,
    'indoor' => true,
    'rows' => [
        '^^^^^^^^^^^^^^^',
        '^:::::^^^:::::^',
        '^::::::::::::^^',
        '^^:::^^::::::^^',
        '^::::^^^:::::^^',
        '^:::::::::^^::^',
        '^^::::::::::::^',
        '^::::^^:::::::^',
        '^^^::::::^^:::^',
        '^^^^^^^D^^^^^^^',
    ],
    'warps' => [
        [7, 9, 'yaoguang', 10, 3, 'down'],
    ],
    'signs' => [
    ],
    // The cave's floor: 猾褢.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 蝮虫、蝮虫、蝮虫、蝮虫 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 6, 'slots' => [[6, 'fuchong'], [6, 'fuchong'], [5, 'fuchong'], [4, 'fuchong'], [7, 'huahuai'], [6, 'huahuai'], [6, 'huahuai']]],
];
