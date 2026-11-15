<?php
// 槐江之山, three hundred and twenty li west of 泰器之山. The classic: the 丘时之水 rises on it and runs north into the
// 泑水, with many 蠃母 in it; on it much 青雄黄, 琅玕, gold and jade; it is the god's level garden, which the spirit 英招
// keeps, a horse's body with a human face; hawks and falcons nest there.
declare(strict_types=1);

return [
    'id' => 'huaijiang',
    'name' => '槐江之山',
    'range' => 6,
    'rows' => [
        '^^^^^^^^~~^^^^^^^^^^',
        '^.......~~.........^',
        '^.......~~.,,,,,,..^',
        '^.......~~.,,,,,,..^',
        '^.......~~.,,,,,,..^',
        '^.......~~.........^',
        '^..................^',
        '====================',
        '^..................^',
        '^..,,,,,...........^',
        '^..,,,,,...........^',
        '^..,,,,,...........^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['kunlun', 0], 'right' => ['taiqi', 0]],
    'signs' => [
        [11, 6, [
            ['say', '槐江之山 —— 实惟帝之平圃，神英招司之，其状马身而人面'],
        ]],
    ],
    // The 丘时之水: 蠃母.
    'water' => ['rate' => 2, 'slots' => [[20, 'luomu'], [15, 'luomu'], [20, 'luomu']]],
    // The tall grass: 鹰鸇.
    'wild' => ['rate' => 10, 'slots' => [[16, 'yingzhan'], [16, 'yingzhan'], [16, 'yingzhan'], [16, 'yingzhan'], [13, 'yingzhan'], [13, 'yingzhan'], [13, 'yingzhan']]],
];
