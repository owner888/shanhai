<?php
// 姑儿之山, four hundred li south. The classic: 漆 trees on it, mulberry and 柘 below; the 姑儿之水 rises on it and runs north
// into the sea, with many 鳡鱼 in it.
declare(strict_types=1);

return [
    'id' => 'guer',
    'name' => '姑儿之山',
    'range' => 11,
    'rows' => [
        'TTTTTTTTT~~TTTTTTTTT',
        'T........~~........T',
        'T........~~........T',
        'T........~~........T',
        'T........~~........T',
        'T........~~........T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T...TT.............T',
        'T...TT.............T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['fantiao', 0], 'right' => ['gaoshidong', 0]],
    'signs' => [
        [12, 6, [
            ['say', '姑儿之山 —— 其上多漆，其下多桑柘；姑儿之水出焉，北流注于海'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'ganyu'], [15, 'ganyu'], [20, 'ganyu']]],
];
