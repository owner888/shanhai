<?php
// 视山, fifty li southeast. The classic: leeks on it; a well called 天井, with water in summer and dry in winter; mulberry,
// fine clay, gold and jade. It names no beast.
declare(strict_types=1);

return [
    'id' => 'shishanshi',
    'name' => '视山',
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
    'exits' => ['left' => ['diqun', 0], 'right' => ['qianshansy', 0]],
    'signs' => [
        [9, 6, [
            ['say', '视山 —— 其上多韭；有井焉，名曰天井，夏有水，冬竭'],
        ]],
    ],
];
