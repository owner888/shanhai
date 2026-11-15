<?php
// 鸟山, two hundred li north of 申山. The classic: on it many mulberries, below it many 楮; on its north side much iron,
// on its south much jade; the 辱水 rises on it and runs east into the 河. It names no beast.
declare(strict_types=1);

return [
    'id' => 'niaoshan',
    'name' => '鸟山',
    'range' => 7,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T...TT........TT...T',
        'T...TT...TT...TT...T',
        'T........TT........T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T..........~~~~~~~~T',
        'T..........~~~~~~~~T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['shangshen', 0], 'right' => ['shenshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '鸟山 —— 其上多桑，其下多楮；其阴多铁，其阳多玉'],
        ]],
    ],
];
