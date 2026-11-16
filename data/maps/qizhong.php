<?php
// 跂踵之山, five hundred li south by water and five hundred over drifting sand. The classic: two hundred li round, no grass
// or trees, a great snake, much jade; a water forty li round that wells up, called 深泽, with many 蠵龟 in it, and the fish
// 鮯鮯, a carp with six feet and a bird's tail. The great snake is not in the data.
declare(strict_types=1);

return [
    'id' => 'qizhong',
    'name' => '跂踵之山',
    'range' => 13,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^------------------^',
        '^-------~~~--------^',
        '^-------~~~--------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['mengzi', 0], 'right' => ['muyu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '跂踵之山 —— 广员二百里，无草木，有大蛇，其上多玉；有水焉，广员四十里皆涌，其名曰深泽'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'gege'], [15, 'xigui'], [20, 'gege']]],
];
