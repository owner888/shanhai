<?php
// 泰室之山, thirty li east; one of the range's three 冢. The classic: a tree called 栯木, with leaves like a pear's and red
// veins, which keeps one from jealousy; a herb called 䔄草; fine stone. It names no beast.
declare(strict_types=1);

return [
    'id' => 'taishi',
    'name' => '泰室之山',
    'range' => 21,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T...^^^............T',
        'T...^^^............T',
        'T..................T',
        'T..............TT..T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T...........^^^....T',
        'T...........^^^....T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['shaoshi', 0], 'right' => ['jiangshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '泰室之山 —— 其上有木焉，叶状如梨而赤理，其名曰栯木；上多美石'],
        ]],
    ],
];
