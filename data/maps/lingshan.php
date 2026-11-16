<?php
// 灵山, three hundred li northeast. The classic: gold and jade on it, 青雘 below; peach, plum, 梅 and apricot. It names no
// beast.
declare(strict_types=1);

return [
    'id' => 'lingshan',
    'name' => '灵山',
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
    'exits' => ['left' => ['dayao', 0], 'right' => ['longshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '灵山 —— 其上多金玉，其下多青雘，其木多桃李梅杏'],
        ]],
    ],
];
