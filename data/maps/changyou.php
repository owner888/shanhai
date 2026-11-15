<?php
// 长右之山, four hundred and fifty li southeast of 柜山. The classic: no grass or trees, much water; 长右 lives there.
declare(strict_types=1);

return [
    'id' => 'changyou',
    'name' => '长右之山',
    'range' => 2,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^-~~~~~-:::::-~~~~-^',
        '^-~~~~~-:::::-~~~~-^',
        '^-~~~~~-:::::------^',
        '^-------:::::------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^--::::::---~~~~~~-^',
        '^--::::::---~~~~~~-^',
        '^--::::::---~~~~~~-^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['guishan', 0], 'right' => ['yaoguang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '长右之山 —— 无草木，多水'],
        ]],
    ],
    // The scree: 长右, the one beast the classic gives this mountain.
    'wild' => ['rate' => 10, 'slots' => [[4, 'changyou'], [5, 'changyou'], [7, 'changyou'], [6, 'changyou'], [7, 'changyou'], [7, 'changyou'], [7, 'changyou']]],
];
