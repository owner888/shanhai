<?php
// 符惕之山, two hundred li west of 阴山. The classic: on it many 棕 and 枏, below it much gold and jade; the spirit 江疑
// lives there; strange rains fall on it, and wind and cloud come out of it. It names no beast.
declare(strict_types=1);

return [
    'id' => 'futi',
    'name' => '符惕之山',
    'range' => 6,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..TTT........TTT..T',
        'T..TTT...TT...TTT..T',
        'T........TT........T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['sanwei', 0], 'right' => ['yinshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '符惕之山 —— 神江疑居之；是山也，多怪雨，风云之所出也'],
        ]],
    ],
];
