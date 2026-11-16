<?php
// 区吴之山, fifty li east. The classic: 苴 trees. It names no beast.
declare(strict_types=1);

return [
    'id' => 'ouwu',
    'name' => '区吴之山',
    'range' => 25,
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
    'exits' => ['left' => ['dazhi', 0], 'right' => ['shengxiong', 0]],
    'signs' => [
        [9, 6, [
            ['say', '区吴之山 —— 其木多苴'],
        ]],
    ],
];
