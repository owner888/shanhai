<?php
// 鹿吴之山, five hundred li east of 区吴之山. The classic: on it no grass or trees; much metal and stone; the 泽更之水
// rises on it and runs south into the 滂水; in the water is a beast, 蛊雕, which eats people.
declare(strict_types=1);

return [
    'id' => 'luwu',
    'name' => '鹿吴之山',
    'range' => 2,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^--^^^^------^^^^--^',
        '^--^^^^------^^^^--^',
        '^------------^^^^--^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^-------~~~~-------^',
        '^-------~~~~-------^',
        '^-------~~~~-------^',
        '^-----~~~~~~~~-----^',
        '^-----~~~~~~~~-----^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['quwu', 0], 'right' => ['qiwu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '鹿吴之山 —— 水有兽焉，是食人'],
        ]],
    ],
    // The 泽更之水: 蛊雕.
    'water' => ['rate' => 6, 'slots' => [[6, 'gudiao'], [7, 'gudiao'], [5, 'gudiao']]],
];
