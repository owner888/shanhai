<?php
// 求山, a hundred li southeast. The classic: the 求水 comes out on it and goes under below it, with fine ochre in it; 苴 and
// bamboo; gold on its south side, iron on its north. It names no beast.
declare(strict_types=1);

return [
    'id' => 'qiushan',
    'name' => '求山',
    'range' => 25,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T.......~~~........T',
        'T.......~~~........T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['lishi', 0], 'right' => ['chouyang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '求山 —— 求水出于其上，潜于其下，中有美赭；其阳多金，其阴多铁'],
        ]],
    ],
];
