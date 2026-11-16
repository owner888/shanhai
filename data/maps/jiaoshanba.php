<?php
// 骄山, a hundred and fifty li northeast; the range's 冢 (`jiaoshan` is 教山, of the north). The classic: jade on it, 青雘
// below; pines and cypresses; the god 鼍围 lives there, with a man's face, a ram's horns and a tiger's claws, who wanders
// the deeps of the 雎 and the 漳 and shines as he comes and goes. It names no beast.
declare(strict_types=1);

return [
    'id' => 'jiaoshanba',
    'name' => '骄山',
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
    'exits' => ['left' => ['jingshanjing', 0], 'right' => ['nvji', 0]],
    'signs' => [
        [9, 6, [
            ['say', '骄山 —— 其上多玉，其下多青雘；神鼍围处之，其状如人面，羊角虎爪'],
        ]],
    ],
];
