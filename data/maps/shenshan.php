<?php
// 申山, seventy li north of 罢父之山. The classic: on it many 榖 and 柞, below it many 杻 and 橿; on its south side much
// gold and jade; the 区水 rises on it and runs east into the 河. It names no beast.
declare(strict_types=1);

return [
    'id' => 'shenshan',
    'name' => '申山',
    'range' => 7,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T...TT.............T',
        'T...TT...TT........T',
        'T........TT........T',
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
    'exits' => ['left' => ['niaoshan', 0], 'right' => ['bafu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '申山 —— 其上多榖柞，其下多杻橿，其阳多金玉'],
        ]],
    ],
];
