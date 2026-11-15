<?php
// 仆勾之山, five hundred li east of 夷山. The classic: on it much gold and jade, below it much grass and trees; no
// birds or beasts; no water.
declare(strict_types=1);

return [
    'id' => 'pugou',
    'name' => '仆勾之山',
    'range' => 2,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T......^^^^^^......T',
        'T......^^^^^^......T',
        'T..TT..^^^^^^..TT..T',
        'T..TT..........TT..T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T.........TT.......T',
        'T...TTT...TT...TT..T',
        'T...TTT...TT...TT..T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['yishan', 0], 'right' => ['xianyin', 0]],
    'signs' => [
        [9, 6, [
            ['say', '仆勾之山 —— 无鸟兽，无水'],
        ]],
    ],
];
