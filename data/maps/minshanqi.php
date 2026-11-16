<?php
// 敏山, thirty-five li east. The classic: a tree called 葪柏, which keeps off cold; 㻬琈 jade on its south side. It names no
// beast.
declare(strict_types=1);

return [
    'id' => 'minshanqi',
    'name' => '敏山',
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
    'exits' => ['left' => ['yishanqi', 0], 'right' => ['dagui', 0]],
    'signs' => [
        [9, 6, [
            ['say', '敏山 —— 其阳多㻬琈之玉'],
        ]],
    ],
];
