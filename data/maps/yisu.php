<?php
// 宜苏之山, forty li east. The classic: gold and jade on it, 蔓居 trees below; the 滽滽之水 rises on it and runs north into the 河,
// with many yellow shells in it.
declare(strict_types=1);

return [
    'id' => 'yisu',
    'name' => '宜苏之山',
    'range' => 17,
    'rows' => [
        '^^^^^^^^^~~^^^^^^^^^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^..................^',
        '====================',
        '^..................^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['guishanzhong', 0], 'right' => ['heshan', 0]],
    'signs' => [
        [12, 6, [
            ['say', '宜苏之山 —— 其上多金玉，其下多蔓居之木；滽滽之水出焉，而北流注于河'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'huangbei'], [15, 'huangbei'], [20, 'huangbei']]],
];
