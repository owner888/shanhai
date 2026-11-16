<?php
// 大支之山, twenty-five li east. The classic: gold on its south side; 榖 and 柞; no grass. It names no beast.
declare(strict_types=1);

return [
    'id' => 'dazhi',
    'name' => '大支之山',
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
    'exits' => ['left' => ['gaoshansy', 0], 'right' => ['ouwu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '大支之山 —— 其阳多金，其木多榖柞，无草'],
        ]],
    ],
];
