<?php
// 祑雕之山, fifty li northeast. The classic: pines, cypresses, 机 and 桓. Its second character is a rare one, written here by
// its sound. It names no beast.
declare(strict_types=1);

return [
    'id' => 'zhidiao',
    'name' => '祑雕之山',
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
    'exits' => ['left' => ['gongli', 0], 'right' => ['jinli', 0]],
    'signs' => [
        [9, 6, [
            ['say', '祑雕之山 —— 其上多松柏机桓'],
        ]],
    ],
];
