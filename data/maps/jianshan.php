<?php
// 葌山, a hundred and twenty li west. The classic: the 葌水 rises on it and runs north into the 伊水; gold and jade on it, 青
// and realgar below; a tree called 芒草 that poisons fish. It names no beast.
declare(strict_types=1);

return [
    'id' => 'jianshan',
    'name' => '葌山',
    'range' => 16,
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
    'exits' => ['left' => ['kunwu', 0], 'right' => ['dusu', 0]],
    'signs' => [
        [12, 6, [
            ['say', '葌山 —— 葌水出焉，而北流注于伊水；其上多金玉，其下多青雄黄'],
        ]],
    ],
];
