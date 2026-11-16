<?php
// 仁举之山, a hundred and fifty li northeast. The classic: 榖 and 柞; red gold on its south side, ochre on its north. It names
// no beast.
declare(strict_types=1);

return [
    'id' => 'renju',
    'name' => '仁举之山',
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
    'exits' => ['left' => ['huanshanba', 0], 'right' => ['shimei', 0]],
    'signs' => [
        [9, 6, [
            ['say', '仁举之山 —— 其木多榖柞，其阳多赤金，其阴多赭'],
        ]],
    ],
];
