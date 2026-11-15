<?php
// 符禺之山, eighty li west of 小华之山 (`fuyu` is 浮玉之山, of the second range). The classic: on its south side much
// copper, on its north much iron; the tree 文茎 and the herb 条 grow on it; the 符禺之水 rises on it and runs north into
// the 渭; its beasts are mostly 葱聋, its birds mostly 鴖.
declare(strict_types=1);

return [
    'id' => 'fuyu4',
    'name' => '符禺之山',
    'range' => 4,
    'rows' => [
        '^^^^^^^^^^~~^^^^^^^^',
        '^..TT.....~~.......^',
        '^..TT.,,,.~~.,,,,,.^',
        '^.....,,,.~~.,,,,,.^',
        '^.....,,,.~~.,,,,,.^',
        '^.........~~.......^',
        '^..................^',
        '====================',
        '^..................^',
        '^.,,,,,........T...^',
        '^.,,,,,....T.......^',
        '^.,,,,,........T...^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['shicui', 0], 'right' => ['xiaohua', 0]],
    'signs' => [
        [13, 6, [
            ['say', '符禺之山 —— 其阳多铜，其阴多铁；符禺之水出焉，北流注于渭'],
        ]],
    ],
    // The tall grass: 葱聋 and 鴖.
    'wild' => ['rate' => 10, 'slots' => [[13, 'conglong'], [14, 'min'], [15, 'conglong'], [16, 'min'], [15, 'conglong'], [15, 'min'], [15, 'conglong']]],
];
