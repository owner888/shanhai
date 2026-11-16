<?php
// 陆䣀之山, two hundred li east. The classic: 㻬琈 jade on it, clay below, 杻 and 橿. It names no beast.
declare(strict_types=1);

return [
    'id' => 'lugui',
    'name' => '陆䣀之山',
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
    'exits' => ['left' => ['lunshanba', 0], 'right' => ['guangshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '陆䣀之山 —— 其上多㻬琈之玉，其下多垩，其木多杻橿'],
        ]],
    ],
];
