<?php
// 橐山, ninety li east. The classic: 樗 and 𣖾 trees; gold and jade on its south side, iron on its north; the 橐水 rises on it
// and runs north into the 河, with many 脩辟之鱼 in it, frogs with white beaks, which call like kites.
declare(strict_types=1);

return [
    'id' => 'tuoshan',
    'name' => '橐山',
    'range' => 20,
    'rows' => [
        'TTTTTTTTT~~TTTTTTTTT',
        'T........~~........T',
        'T........~~........T',
        'T........~~........T',
        'T........~~........T',
        'T........~~........T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T...TT.............T',
        'T...TT.............T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['changzheng', 0], 'right' => ['fushanliu', 0]],
    'signs' => [
        [12, 6, [
            ['say', '橐山 —— 其木多樗，多𣖾木；橐水出焉，而北流注于河'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'xiubi'], [15, 'xiubi'], [20, 'xiubi']]],
];
