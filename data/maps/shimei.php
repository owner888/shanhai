<?php
// 师每之山, fifty li east. The classic: whetstone on its south side, 青雘 on its north; cypress, 檀 and 柘; bamboo. It names no
// beast.
declare(strict_types=1);

return [
    'id' => 'shimei',
    'name' => '师每之山',
    'range' => 22,
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
    'exits' => ['left' => ['renju', 0], 'right' => ['qingu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '师每之山 —— 其阳多砥砺，其阴多青雘，其木多柏，多檀，多柘'],
        ]],
    ],
];
