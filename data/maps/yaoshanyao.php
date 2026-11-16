<?php
// 尧山, a hundred and fifty-nine li southeast (`yaoshan` is 杳山, of the eleventh). The classic: yellow clay on its north
// side, gold on its south; 荆, 芑, willow and 檀; yam. It names no beast.
declare(strict_types=1);

return [
    'id' => 'yaoshanyao',
    'name' => '尧山',
    'range' => 26,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^...^^^............^',
        '^...^^^............^',
        '^..................^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^..................^',
        '^...........^^^....^',
        '^...........^^^....^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['jigong', 0], 'right' => ['jiangfu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '尧山 —— 其阴多黄垩，其阳多黄金，其木多荆芑柳檀'],
        ]],
    ],
];
