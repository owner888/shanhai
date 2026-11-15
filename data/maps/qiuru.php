<?php
// 求如之山, two hundred and fifty li north of 单狐之山. The classic: on it much copper, below it much jade, no grass or
// trees; the 滑水 rises on it and runs west, and in it are 滑鱼, like an eel with a red back, and 水马, a horse with
// patterned forelegs and an ox's tail.
declare(strict_types=1);

return [
    'id' => 'qiuru',
    'name' => '求如之山',
    'range' => 8,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^------------------^',
        '^------------^^^---^',
        '^------------^^^---^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^~~~~~~~~~---------^',
        '^~~~~~~~~~---------^',
        '^~~~~~~~~~---------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['danhu', 0], 'right' => ['daishan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '求如之山 —— 其上多铜，其下多玉，无草木；滑水出焉'],
        ]],
    ],
    // The 滑水: 滑鱼 and 水马.
    'water' => ['rate' => 2, 'slots' => [[20, 'huayub'], [15, 'shuima'], [20, 'huayub']]],
];
