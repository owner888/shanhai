<?php
// 邽山, two hundred and sixty li west of 中曲之山 (`guishan` is 柜山, `guishan4` and `guishanxi` the two 騩山). The classic:
// the beast 穷奇 lives there, an ox with a hedgehog's bristles, which eats people; the 濛水 rises on it and runs south
// into the 洋水, with many yellow shells in it, and 蠃鱼, a fish with a bird's wings, at whose sight there is flood.
declare(strict_types=1);

return [
    'id' => 'guishansi',
    'name' => '邽山',
    'range' => 7,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^.......~~.........^',
        '^.......~~.........^',
        '^.....~~~~~~.......^',
        '^.....~~~~~~.......^',
        '^.......~~.........^',
        '^^^^^^^^~~^^^^^^^^^^',
    ],
    'exits' => ['left' => ['niaoshu', 0], 'right' => ['zhongqu', 0]],
    'signs' => [
        [10, 6, [
            ['say', '邽山 —— 濛水出焉，南流注于洋水，其中多黄贝'],
        ]],
    ],
    // The 濛水: 蠃鱼.
    'water' => ['rate' => 2, 'slots' => [[20, 'luoyu'], [15, 'luoyu'], [20, 'luoyu']]],
    // The tall grass: 穷奇.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 白虎、白鹿、龟、白虎 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 6, 'slots' => [[13, 'baihu'], [14, 'bailu'], [15, 'gui'], [14, 'baihu'], [15, 'qiongqi'], [15, 'qiongqi'], [15, 'qiongqi']]],
];
