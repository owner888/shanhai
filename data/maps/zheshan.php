<?php
// 柘山, a hundred and seventy li north. The classic: gold and jade on its south side, iron on its north; the 历聚之水 rises on
// it and runs north into the 洧水. It names no beast.
declare(strict_types=1);

return [
    'id' => 'zheshan',
    'name' => '柘山',
    'range' => 10,
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
    'exits' => ['left' => ['dunyu', 0], 'right' => ['weilong', 0]],
    'signs' => [
        [12, 6, [
            ['say', '柘山 —— 其阳有金玉，其阴有铁；历聚之水出焉，而北流注于洧水'],
        ]],
    ],
];
