<?php
// 隅阳之山, three hundred li northeast. The classic: gold and jade on it, 青雘 below; 梓 and mulberry; the 徐之水 rises on it and
// runs east into the 江, with much 丹粟 in it. It names no beast.
declare(strict_types=1);

return [
    'id' => 'yuyang',
    'name' => '隅阳之山',
    'range' => 23,
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
    'exits' => ['left' => ['lishanjiu', 0], 'right' => ['qishanjiu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '隅阳之山 —— 其上多金玉，其下多青雘；徐之水出焉，东流注于江'],
        ]],
    ],
];
