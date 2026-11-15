<?php
// 鸟鼠同穴之山, two hundred and twenty li west of 邽山. The classic: on it many white tigers and much white jade; the 渭水
// rises on it and runs east into the 河, with many 鰠鱼 in it, at whose stirring there is great war; the 滥水 rises on
// its west side and runs west into the 汉水, with many 𩶯魮之鱼 in it, which bear pearls and jade.
declare(strict_types=1);

return [
    'id' => 'niaoshu',
    'name' => '鸟鼠同穴之山',
    'range' => 7,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....~~~~~~~^',
        '^..,,,,,....~~~~~~~^',
        '^..,,,,,...........^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^~~~~~~............^',
        '^~~~~~~...^^^......^',
        '^.........^^^......^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['yanzi', 0], 'right' => ['guishansi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '鸟鼠同穴之山 —— 其上多白虎、白玉；渭水出焉，而东流注于河'],
        ]],
    ],
    // The 渭水 and the 滥水: 鰠鱼 and 𩶯魮之鱼.
    'water' => ['rate' => 2, 'slots' => [[20, 'saoyu'], [15, 'rupi'], [20, 'saoyu']]],
    // The tall grass: 白虎.
    'wild' => ['rate' => 10, 'slots' => [[15, 'baihu'], [16, 'baihu'], [16, 'baihu'], [15, 'baihu'], [17, 'baihu'], [17, 'baihu'], [17, 'baihu']]],
];
