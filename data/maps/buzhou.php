<?php
// 不周之山, three hundred and seventy li northwest of 长沙之山. The classic: a fine fruit grows there, like a peach with
// leaves like the jujube's, and who eats it does not tire; to its east is 泑泽, where the 河 sinks. It names no beast.
// Its name is that it does not close: the rock above the road is in two with a gap between.
declare(strict_types=1);

return [
    'id' => 'buzhou',
    'name' => '不周之山',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^.^^^^^^^..^^^^^^^.^',
        '^.^^^^^^^..^^^^^^^.^',
        '^.^^^^^^^..^^^^^^^.^',
        '^..................^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^..................^',
        '^....TT............^',
        '^....TT............^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['mishan', 0], 'right' => ['changsha', 0]],
    'signs' => [
        [9, 6, [
            ['say', '不周之山 —— 爰有嘉果，其实如桃，其叶如枣，食之不劳'],
            ['ifFlag', 'found_jiaguo', 'had_jiaguo'],
            ['setFlag', 'found_jiaguo'],
            ['give', 'jiaguo', 1],
            ['say', '牌子旁边有嘉果。你采了一份。'],
            ['label', 'had_jiaguo'],
        ]],
    ],
];
