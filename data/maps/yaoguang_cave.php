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
    'wild' => ['rate' => 6, 'slots' => [[6, 'huahuai'], [6, 'huahuai'], [5, 'huahuai'], [4, 'huahuai'], [7, 'huahuai'], [6, 'huahuai'], [6, 'huahuai']]],
];
