<?php
// 发爽之山, five hundred li east of 丹穴之山. The classic: no grass or trees; much water; many white apes; the 汎水 rises
// on it and runs south into the 勃海. With no grass, the apes are met on the scree.
declare(strict_types=1);

return [
    'id' => 'fashuang',
    'name' => '发爽之山',
    'range' => 3,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^--~~~~------~~~~--^',
        '^--~~~~--::::~~~~--^',
        '^--------::::------^',
        '^--::::--::::------^',
        '^--::::------------^',
        '^------------------^',
        '====================',
        '^------~~~~--------^',
        '^--::::~~~~--::::--^',
        '^--::::~~~~--::::--^',
        '^-----~~~~~~-::::--^',
        '^-----~~~~~~-------^',
        '^^^^^^~~~~~~^^^^^^^^',
    ],
    'exits' => ['left' => ['danxue', 0], 'right' => ['maoshan', 0]],
    'signs' => [
        [12, 6, [
            ['say', '发爽之山 —— 无草木，多水，多白猿'],
        ]],
    ],
    // The scree: 白猿.
    'wild' => ['rate' => 10, 'slots' => [[10, 'baiyuan'], [11, 'baiyuan'], [12, 'baiyuan'], [10, 'baiyuan'], [12, 'baiyuan'], [10, 'baiyuan'], [10, 'baiyuan']]],
];
