<?php
// 岐山, two hundred and fifty li east (the third of the name). The classic: white gold on it, iron below; 梅, 梓, 杻 and 楢;
// the 减水 rises on it and runs southeast into the 江. It names no beast.
declare(strict_types=1);

return [
    'id' => 'qishanjiu',
    'name' => '岐山',
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
    'exits' => ['left' => ['yuyang', 0], 'right' => ['goumi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '岐山 —— 其上多白金，其下多铁；减水出焉，东南流注于江'],
        ]],
    ],
];
