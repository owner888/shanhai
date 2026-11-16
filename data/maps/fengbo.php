<?php
// 风伯之山, fifty li southeast. The classic: gold and jade on it, 痠 stone, patterned stone and iron below; willow, 杻, 檀 and
// 楮; a wood east of it called 莽浮之林, with fine trees, birds and beasts, none of them named.
declare(strict_types=1);

return [
    'id' => 'fengbo',
    'name' => '风伯之山',
    'range' => 26,
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
    'exits' => ['left' => ['bingshanse', 0], 'right' => ['fufu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '风伯之山 —— 其上多金玉，其下多痠石、文石，多铁；其东有林焉，名曰莽浮之林'],
        ]],
    ],
];
