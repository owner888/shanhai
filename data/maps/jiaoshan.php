<?php
// 教山, three hundred li northeast. The classic: much jade and no stone; the 教水 rises on it and runs west into the 河, dry
// in winter and running in summer; in it are two hills, the 发丸之山. It names no beast.
declare(strict_types=1);

return [
    'id' => 'jiaoshan',
    'name' => '教山',
    'range' => 10,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^-------------^^---^',
        '^-------------^^---^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^~~~~~~~-----------^',
        '^~~~~~~~-----------^',
        '^------------------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['wangwu', 0], 'right' => ['jingshanyi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '教山 —— 其上多玉而无石；教水出焉，西流注于河，是水冬干而夏流'],
        ]],
    ],
];
