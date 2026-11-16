<?php
// 岳山, three hundred li south. The classic: mulberry on it, 樗 below; the 泺水 rises on it and runs east into a marsh, with
// gold and jade in it. It names no beast.
declare(strict_types=1);

return [
    'id' => 'yueshan',
    'name' => '岳山',
    'range' => 11,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T...^^.............T',
        'T...^^.............T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T...........~~~~~~~T',
        'T...TT......~~~~~~~T',
        'T...TT.............T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['gaoshidong', 0], 'right' => ['chaishan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '岳山 —— 其上多桑，其下多樗；泺水出焉，东流注于泽'],
        ]],
    ],
];
