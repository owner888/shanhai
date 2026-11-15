<?php
// 灌湘之山, five hundred li east of 阳夹之山. The classic: on it many trees and no grass; many strange birds, and no
// beasts. So there are trees above and bare ground under them, and nothing is met.
declare(strict_types=1);

return [
    'id' => 'guanxiang',
    'name' => '灌湘之山',
    'range' => 3,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'TTTTTTTTTTTTTTTTTTTT',
        'T-TT--TTT--TT-TTT--T',
        'T--T---T---T---T---T',
        'T------------------T',
        'T------------------T',
        'T------------------T',
        '====================',
        'T------------------T',
        'T------------------T',
        'T---^^-------^^^---T',
        'T---^^-------^^^---T',
        'T------------------T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['yangjia', 0], 'right' => ['jishan3', 0]],
    'signs' => [
        [10, 6, [
            ['say', '灌湘之山 —— 上多木，无草；多怪鸟，无兽'],
        ]],
    ],
];
