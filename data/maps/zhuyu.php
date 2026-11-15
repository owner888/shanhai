<?php
// 诸余之山, three hundred and eighty li north of 狂山. The classic: on it much copper and jade, below it many pines and
// cypresses; the 诸余之水 rises on it and runs east into the 旄水. It names no beast.
declare(strict_types=1);

return [
    'id' => 'zhuyu',
    'name' => '诸余之山',
    'range' => 9,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T...........~~~~~~~T',
        'T...........~~~~~~~T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T...TT...TT...TT...T',
        'T...TT...TT...TT...T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['kuangshan', 0], 'right' => ['duntou', 0]],
    'signs' => [
        [9, 6, [
            ['say', '诸余之山 —— 其上多铜玉，其下多松柏'],
        ]],
    ],
];
