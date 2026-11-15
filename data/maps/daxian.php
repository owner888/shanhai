<?php
// 大咸之山, two hundred and eighty li north of 小咸之山. The classic: no grass or trees, below it much jade; the mountain
// is square and cannot be climbed; a snake lives there, 长蛇, with a boar's bristles. So the mountain is a block of rock
// and the snake is met on the scree at its foot.
declare(strict_types=1);

return [
    'id' => 'daxian',
    'name' => '大咸之山',
    'range' => 8,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^--^^^^^^^^^^^^^^--^',
        '^--^^^^^^^^^^^^^^--^',
        '^--^^^^^^^^^^^^^^--^',
        '^--^^^^^^^^^^^^^^--^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^---:::::--:::::---^',
        '^---:::::--:::::---^',
        '^---:::::--:::::---^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['xiaoxian', 0], 'right' => ['dunhong', 0]],
    'signs' => [
        [9, 6, [
            ['say', '大咸之山 —— 无草木，其下多玉；是山也，四方，不可以上'],
        ]],
    ],
    // The scree: 长蛇.
    'wild' => ['rate' => 2, 'slots' => [[21, 'changshe'], [22, 'changshe'], [22, 'changshe'], [23, 'changshe'], [24, 'changshe'], [22, 'changshe'], [22, 'changshe']]],
];
