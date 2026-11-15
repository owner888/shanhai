<?php
// 祷过之山, five hundred li east of 天虞之山. The classic: on it much gold and jade; below it many 犀 and 兕, and many 象;
// the bird 瞿如 lives there; the 泿水 rises on it and runs south into the sea, and in it is 虎蛟.
declare(strict_types=1);

return [
    'id' => 'daoguo',
    'name' => '祷过之山',
    'range' => 3,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^....^^^^^^^^^^',
        '^..,,,,,....,,,,,,.^',
        '^..,,,,,....,,,,,,.^',
        '^..,,,,,....,,,,,,.^',
        '^..................^',
        '^..................^',
        '====================',
        '^.........~~.......^',
        '^..,,,,...~~.......^',
        '^..,,,,...~~~..TT..^',
        '^..,,,,..~~~~..TT..^',
        '^........~~~~~.....^',
        '^^^^^^^^^~~~~~^^^^^^',
    ],
    'exits' => ['left' => ['tianyu', 0], 'right' => ['danxue', 0]],
    'signs' => [
        [9, 6, [
            ['say', '祷过之山 —— 其上多金玉，其下多犀、兕，多象'],
        ]],
    ],
    // The 泿水: 虎蛟.
    'water' => ['rate' => 2, 'slots' => [[20, 'hujiao'], [15, 'hujiao'], [20, 'hujiao']]],
    // The tall grass: 犀, 兕 and 象 below the mountain, and the bird 瞿如. Its levels and its rate are those of the
    // first area before the reference's third gym (docs/numbers.md 14).
    'wild' => ['rate' => 4, 'slots' => [[5, 'xi'], [5, 'si'], [7, 'quru'], [7, 'xiang'], [7, 'xi'], [6, 'quru'], [6, 'si']]],
];
