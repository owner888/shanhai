<?php
// 服山, thirty-five li east. The classic: 苴 trees; 封 stone on it, red tin below. It names no beast.
declare(strict_types=1);

return [
    'id' => 'fushansy',
    'name' => '服山',
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
    'exits' => ['left' => ['aoshan', 0], 'right' => ['yaoshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '服山 —— 其木多苴，其上多封石，其下多赤锡'],
        ]],
    ],
];
