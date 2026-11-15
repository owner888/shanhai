<?php
// 皇人之山, five hundred li west of 众兽之山. The classic: on it much gold and jade, below it much 青雄黄; the 皇水 rises on
// it and runs west into the 赤水, with much 丹粟 in it. It names no beast.
declare(strict_types=1);

return [
    'id' => 'huangren',
    'name' => '皇人之山',
    'range' => 5,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^......^^^^^^^^^',
        '^^...........^^^^^^^',
        '^..............^^^^^',
        '~~~~~..............^',
        '~~~~~~.............^',
        '^..................^',
        '====================',
        '^..................^',
        '^....^^............^',
        '^....^^.......^^...^',
        '^.............^^...^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['zhonghuang', 0], 'right' => ['zhongshou', 0]],
    'signs' => [
        [9, 6, [
            ['say', '皇人之山 —— 其上多金玉，其下多青雄黄；皇水出焉，西流注于赤水'],
        ]],
    ],
];
