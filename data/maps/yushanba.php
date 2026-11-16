<?php
// 玉山, a hundred and fifty li southeast (`yushan` is 羽山, of the south; 玉山 of the west is `yushanxi`). The classic: gold
// and jade on it, 碧 and iron below, cypress. It names no beast.
declare(strict_types=1);

return [
    'id' => 'yushanba',
    'name' => '玉山',
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
    'exits' => ['left' => ['zhishan', 0], 'right' => ['huanshanba', 0]],
    'signs' => [
        [9, 6, [
            ['say', '玉山 —— 其上多金玉，其下多碧铁，其木多柏'],
        ]],
    ],
];
