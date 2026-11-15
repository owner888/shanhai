<?php
// 大时之山, four hundred and eight li west of 南山. The classic: on it many 榖 and 柞, below it many 杻 and 橿; on its
// north side much silver, on its south much white jade; the 涔水 rises on it and runs north into the 渭, the 清水 south
// into the 汉水. It names no beast.
declare(strict_types=1);

return [
    'id' => 'dashi',
    'name' => '大时之山',
    'range' => 4,
    'rows' => [
        'TTTTTTTTT~~TTTTTTTTT',
        'T.TT.....~~....TT..T',
        'T.TT.....~~........T',
        'T.........~....TT..T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T.....~............T',
        'T..TT.~~.....TT....T',
        'T..TT.~~.....TT....T',
        'T.....~~...........T',
        'TTTTTT~~TTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['bozhong', 0], 'right' => ['nanshan', 0]],
    'signs' => [
        [13, 6, [
            ['say', '大时之山 —— 涔水北流注于渭，清水南流注于汉水'],
        ]],
    ],
];
