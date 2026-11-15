<?php
// 高山, fifty li northwest of 数历之山. The classic: on it much silver, below it much 青碧 and 雄黄; its trees are mostly
// 棕, its grass mostly bamboo; the 泾水 rises on it and runs east into the 渭. It names no beast.
declare(strict_types=1);

return [
    'id' => 'gaoshan',
    'name' => '高山',
    'range' => 5,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^....^^^^^^^^^^',
        '^^^..........^^^^^^^',
        '^..TT..........TT..^',
        '^..TT..........TT..^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^.........~~~~~~~~~~',
        '^........~~~~~~~~~~~',
        '^..TT..............^',
        '^..TT..............^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['nuchuang', 0], 'right' => ['shuli', 0]],
    'signs' => [
        [9, 6, [
            ['say', '高山 —— 其上多银，其下多青碧、雄黄；泾水出焉，东流注于渭'],
        ]],
    ],
];
