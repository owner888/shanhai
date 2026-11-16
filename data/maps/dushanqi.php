<?php
// 堵山, twenty-seven li east (`dushan` is 独山, of the east). The classic: the god 天愚 lives there, and there is much strange
// wind and rain; a tree called 天楄. It names no beast.
declare(strict_types=1);

return [
    'id' => 'dushanqi',
    'name' => '堵山',
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
    'exits' => ['left' => ['kushan', 0], 'right' => ['fanggao', 0]],
    'signs' => [
        [9, 6, [
            ['say', '堵山 —— 神天愚居之，是多怪风雨'],
        ]],
    ],
];
