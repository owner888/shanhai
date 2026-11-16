<?php
// 熊山, a hundred and fifty li east; the range's 帝, where its rites are greatest. The classic: a cave on it, the bears'
// cave, where a god-man always comes and goes; it opens in summer and closes in winter, and if it opens in winter there
// is war; white jade on it, white gold below. It names no beast.
declare(strict_types=1);

return [
    'id' => 'xiongshan',
    'name' => '熊山',
    'range' => 23,
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
    'exits' => ['left' => ['yushanjiu', 0], 'right' => ['guishanjiu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '熊山 —— 有穴焉，熊之穴，恒出入神人；夏启而冬闭，是穴也，冬启乃必有兵'],
        ]],
    ],
];
