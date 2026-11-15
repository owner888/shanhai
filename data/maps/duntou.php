<?php
// 敦头之山, three hundred and fifty li north of 诸余之山. The classic: on it much gold and jade, no grass or trees; the
// 旄水 rises on it and runs east into 印泽, and in it are many 𩣡马, with an ox's tail, a white body and one horn.
declare(strict_types=1);

return [
    'id' => 'duntou',
    'name' => '敦头之山',
    'range' => 9,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^---^^^------------^',
        '^---^^^------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^---------~~~~~~~~~^',
        '^---------~~~~~~~~~^',
        '^---------~~~~~~~~~^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['zhuyu', 0], 'right' => ['gouwu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '敦头之山 —— 其上多金玉，无草木；旄水出焉，而东流注于印泽'],
        ]],
    ],
    // The 旄水: 𩣡马.
    'water' => ['rate' => 2, 'slots' => [[20, 'boma'], [15, 'boma'], [20, 'boma']]],
];
