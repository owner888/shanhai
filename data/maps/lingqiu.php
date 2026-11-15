<?php
// 令丘之山, four hundred li east of 鸡山. The classic: no grass or trees; much fire; to its south is a valley, 中谷, out
// of which the northeast wind, 条风, comes; the bird 顒 lives there, at whose sight there is great drought. With no
// grass, 顒 is met on the scree.
declare(strict_types=1);

return [
    'id' => 'lingqiu',
    'name' => '令丘之山',
    'range' => 3,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^--::::::---:::::--^',
        '^--::::::---:::::--^',
        '^--::::::---:::::--^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^^^^^^-----^^^^^^^^^',
        '^^^^^^-:::-^^^^^^^^^',
        '^^^^^^^:::^^^^^^^^^^',
        '^^^^^^^---^^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['jishan3', 0], 'right' => ['lunzhe', 0]],
    'signs' => [
        [10, 6, [
            ['say', '令丘之山 —— 无草木，多火'],
        ]],
        [6, 10, [
            ['say', '中谷 —— 条风自是出'],
        ]],
    ],
    // The scree: 顒.
    'wild' => ['rate' => 10, 'slots' => [[12, 'yong'], [12, 'yong'], [14, 'yong'], [13, 'yong'], [10, 'yong'], [10, 'yong'], [10, 'yong']]],
];
