<?php
// 轩辕之山, two hundred li northeast (`xuanyuan` is 轩辕之丘, of the west). The classic: copper on it, bamboo below; the bird 黄鸟
// lives there, an owl with a white head.
declare(strict_types=1);

return [
    'id' => 'xuanyuanbei',
    'name' => '轩辕之山',
    'range' => 10,
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
        '^.......,,,,,^^....^',
        '^.......,,,,,^^....^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['taitou', 0], 'right' => ['yeli', 0]],
    'signs' => [
        [9, 6, [
            ['say', '轩辕之山 —— 其上多铜，其下多竹'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the three areas before the reference's tenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[17, 'huangniao'], [17, 'huangniao'], [18, 'huangniao'], [18, 'huangniao'], [18, 'huangniao'], [16, 'huangniao'], [16, 'huangniao']]],
];
