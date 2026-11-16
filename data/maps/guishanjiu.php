<?php
// 騩山, a hundred and forty li east; one of the range's 冢 (the third 騩山). The classic: fine jade and red gold on its south
// side, iron on its north; 桃枝, 荆 and 芑. It names no beast.
declare(strict_types=1);

return [
    'id' => 'guishanjiu',
    'name' => '騩山',
    'range' => 23,
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
    'exits' => ['left' => ['xiongshan', 0], 'right' => ['geshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '騩山 —— 其阳多美玉、赤金，其阴多铁，其木多桃枝荆芑'],
        ]],
    ],
];
