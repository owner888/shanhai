<?php
// 号山, a hundred and eighty li north of 诸次之山. The classic: its trees are mostly 漆 and 棕, its herbs 药, 虈 and 芎䓖;
// much 汵石; the 端水 rises on it and runs east into the 河. It names no beast.
declare(strict_types=1);

return [
    'id' => 'haoshan',
    'name' => '号山',
    'range' => 7,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..TT.........TT...T',
        'T..TT...TT....TT...T',
        'T.......TT.........T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T............~~~~~~T',
        'T............~~~~~~T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['yushansi', 0], 'right' => ['zhuci', 0]],
    'signs' => [
        [10, 6, [
            ['say', '号山 —— 其木多漆、棕；端水出焉，而东流注于河'],
        ]],
    ],
];
