<?php
// 章莪之山, two hundred and eighty li west of 长留之山. The classic: no grass or trees, much 瑶 and 碧; the beast 狰 lives
// there, a red leopard with five tails and one horn, and the bird 毕方, a crane with one leg, at whose sight there is
// strange fire. With no grass, they are met on the scree.
declare(strict_types=1);

return [
    'id' => 'zhange',
    'name' => '章莪之山',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^--:::::----:::::--^',
        '^--:::::----:::::--^',
        '^--:::::----:::::--^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^-------:::::------^',
        '^-------:::::------^',
        '^-------:::::------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['yinshan', 0], 'right' => ['changliu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '章莪之山 —— 无草木，多瑶碧'],
        ]],
    ],
    // The scree: 狰 and 毕方.
    'wild' => ['rate' => 2, 'slots' => [[16, 'zheng'], [16, 'bifang'], [16, 'zheng'], [16, 'bifang'], [15, 'zheng'], [15, 'bifang'], [15, 'zheng']]],
];
