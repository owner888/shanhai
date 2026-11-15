<?php
// 羽山, three hundred and fifty li east of 尧光之山. The classic: below it much water, above it much rain; no grass
// or trees; many 蝮虫.
declare(strict_types=1);

return [
    'id' => 'yushan',
    'name' => '羽山',
    'range' => 2,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^--::::::---:::::--^',
        '^--::::::---:::::--^',
        '^--::::::---:::::--^',
        '^--::::::----------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^--~~~~~----~~~~~--^',
        '^~~~~~~~~~~~~~~~~~~^',
        '^~~~~~~~~~~~~~~~~~~^',
        '^~~~~~~~~~~~~~~~~~~^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['yaoguang', 0], 'right' => ['qufu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '羽山 —— 其下多水，其上多雨，无草木'],
        ]],
    ],
    // The scree: 蝮虫.
    'wild' => ['rate' => 10, 'slots' => [[6, 'fuchong'], [6, 'fuchong'], [6, 'fuchong'], [6, 'fuchong'], [7, 'fuchong'], [7, 'fuchong'], [7, 'fuchong']]],
];
