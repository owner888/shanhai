<?php
// 中曲之山, three hundred li west of 英鞮之山. The classic: on its south side much jade, on its north 雄黄, white jade
// and gold; the beast 駮 lives there, a white horse with a black tail, one horn and a tiger's teeth and claws, which
// eats tigers and leopards; the tree 櫰木 grows there, whose fruit gives strength.
declare(strict_types=1);

return [
    'id' => 'zhongqu',
    'name' => '中曲之山',
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
        '^..................^',
        '^.......,,,,,......^',
        '^.......,,,,,..TT..^',
        '^.......,,,,,..TT..^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['guishansi', 0], 'right' => ['yingdi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '中曲之山 —— 其阳多玉，其阴多雄黄、白玉及金'],
            ['ifFlag', 'found_huaimu', 'had'],
            ['setFlag', 'found_huaimu'],
            ['give', 'huaimu', 1],
            ['say', '牌子旁边长着櫰木。你采了一份。'],
            ['label', 'had'],
        ]],
    ],
    // The tall grass: 駮.
    'wild' => ['rate' => 6, 'slots' => [[14, 'bo'], [13, 'bo'], [14, 'bo'], [13, 'bo'], [14, 'bo'], [16, 'bo'], [16, 'bo']]],
];
