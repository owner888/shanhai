<?php
// 浮山, a hundred and twenty li west of 竹山. The classic: many 盼木, with leaves like the 枳's and no thorns, and
// insects living in the wood; the herb 薰草 grows there. It names no beast.
declare(strict_types=1);

return [
    'id' => 'fushan',
    'name' => '浮山',
    'range' => 4,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T.TT..TT....TT..TT.T',
        'T.TT..TT....TT..TT.T',
        'T..................T',
        'T....TT......TT....T',
        'T....TT......TT....T',
        'T..................T',
        '====================',
        'T..................T',
        'T..TT....TT....TT..T',
        'T..TT....TT....TT..T',
        'T..................T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['yuci', 0], 'right' => ['zhushan', 0]],
    'signs' => [
        [10, 6, [
            ['say', '浮山 —— 多盼木，枳叶而无伤，木虫居之'],
        ]],
    ],
];
