<?php
// 岐山, a hundred and fifty li east (`qishan` is the 岐山 of the east). The classic: red gold on its south side, white 珉 on
// its north; gold and jade on it, 青雘 below; 樗 trees; the god 涉鼍 lives there, with a man's body, a square face and three
// feet. It names no beast.
declare(strict_types=1);

return [
    'id' => 'qishanba',
    'name' => '岐山',
    'range' => 22,
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
    'exits' => ['left' => ['guangshan', 0], 'right' => ['tongshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '岐山 —— 其阳多赤金，其阴多白珉；神涉鼍处之，其状人身而方面三足'],
        ]],
    ],
];
