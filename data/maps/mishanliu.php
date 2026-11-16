<?php
// 密山, a hundred li east (`mishan` is 峚山, of the west). The classic: jade on its south side, iron on its north; no grass
// or trees; the 豪水 rises on it and runs south into the 洛, with many 旋龟 in it, with a bird's head and a turtle's tail,
// which sound like wood being split.
declare(strict_types=1);

return [
    'id' => 'mishanliu',
    'name' => '密山',
    'range' => 20,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^^^^^^^^^~~^^^^^^^^^',
    ],
    'exits' => ['left' => ['changshi', 0], 'right' => ['gushan', 0]],
    'signs' => [
        [12, 6, [
            ['say', '密山 —— 其阳多玉，其阴多铁；豪水出焉，而南流注于洛'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'xuangui'], [15, 'xuangui'], [20, 'xuangui']]],
];
