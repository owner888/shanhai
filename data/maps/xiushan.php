<?php
// 绣山, a hundred li north. The classic: jade and 青碧, 栒 trees, peony and 芎䓖; the 洧水 rises on it and runs east into the 河,
// with 鳠 and 黾 in it.
declare(strict_types=1);

return [
    'id' => 'xiushan',
    'name' => '绣山',
    'range' => 10,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T...^^.............T',
        'T...^^.............T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T...........~~~~~~~T',
        'T...TT......~~~~~~~T',
        'T...TT.............T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['tishou', 0], 'right' => ['songshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '绣山 —— 其上有玉、青碧，其木多栒，其草多芍药、芎䓖；洧水出焉，而东流注于河'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'huyu'], [15, 'meng'], [20, 'huyu']]],
];
