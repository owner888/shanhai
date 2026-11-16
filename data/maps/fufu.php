<?php
// 夫夫之山, a hundred and fifty li east. The classic: gold on it, 青 and realgar below; mulberry and 楮, bamboo and 鸡鼓; the
// god 于儿 lives there, with a man's body and a snake in each hand, who wanders the deeps of the 江 and shines as he comes
// and goes. It names no beast.
declare(strict_types=1);

return [
    'id' => 'fufu',
    'name' => '夫夫之山',
    'range' => 26,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T...^^.............T',
        'T...^^.............T',
        'T..................T',
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
    'exits' => ['left' => ['fengbo', 0], 'right' => ['dongting', 0]],
    'signs' => [
        [9, 6, [
            ['say', '夫夫之山 —— 神于儿居之，其状人身而手操两蛇，常游于江渊，出入有光'],
        ]],
    ],
];
