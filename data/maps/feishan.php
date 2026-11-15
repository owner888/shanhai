<?php
// 非山之首, the head of 非山, four hundred li east of 旄山之尾. The classic: on it much gold and jade; no water; below it
// many 蝮虫. So the grass that holds them is on the low side.
declare(strict_types=1);

return [
    'id' => 'feishan',
    'name' => '非山之首',
    'range' => 3,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^..........^^^^^^',
        '^^..............^^^^',
        '^..................^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^.,,,,,,....,,,,,,.^',
        '^.,,,,,,....,,,,,,.^',
        '^.,,,,,,..T.,,,,,,.^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['maoshan', 0], 'right' => ['yangjia', 0]],
    'signs' => [
        [9, 6, [
            ['say', '非山之首 —— 其上多金玉，无水，其下多蝮虫'],
        ]],
    ],
    // The tall grass: 蝮虫.
    'wild' => ['rate' => 10, 'slots' => [[12, 'fuchong'], [14, 'fuchong'], [13, 'fuchong'], [10, 'fuchong'], [12, 'fuchong'], [10, 'fuchong'], [12, 'fuchong']]],
];
