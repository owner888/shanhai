<?php
// 若山, a hundred and twenty li south. The classic: 㻬琈 jade, ochre, 封 stone, mistletoe, 柘. It names no beast.
declare(strict_types=1);

return [
    'id' => 'ruoshan',
    'name' => '若山',
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
    'exits' => ['left' => ['shishanba', 0], 'right' => ['zhishan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '若山 —— 其上多㻬琈之玉，多赭，多封石，多寓木，多柘'],
        ]],
    ],
];
