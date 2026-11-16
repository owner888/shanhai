<?php
// 傅山, fifty li east. The classic: no grass or trees, much 瑶 and 碧; the 厌染之水 rises on its south side and runs south into
// the 洛, with many 人鱼 in it; west of it the forest 墦冢.
declare(strict_types=1);

return [
    'id' => 'fushanliu',
    'name' => '傅山',
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
    'exits' => ['left' => ['tuoshan', 0], 'right' => ['changshi', 0]],
    'signs' => [
        [12, 6, [
            ['say', '傅山 —— 无草木，多瑶碧；厌染之水出于其阳，而南流注于洛'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'renyu'], [15, 'renyu'], [20, 'renyu']]],
];
