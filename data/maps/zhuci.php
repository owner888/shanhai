<?php
// 诸次之山, eighty li north of 上申之山. The classic: many trees and no grass; no bird or beast lives there; there are
// many snakes; the 诸次之水 rises on it and runs east into the 河.
declare(strict_types=1);

return [
    'id' => 'zhuci',
    'name' => '诸次之山',
    'range' => 7,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T------------------T',
        'T--TTT------TTT----T',
        'T--TTT------TTT----T',
        'T------------------T',
        'T------------------T',
        'T------------------T',
        '====================',
        'T------------------T',
        'T------::::::------T',
        'T------::::::------T',
        'T------::::::------T',
        'T------------------T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['haoshan', 0], 'right' => ['shangshen', 0]],
    'signs' => [
        [9, 6, [
            ['say', '诸次之山 —— 多木无草，鸟兽莫居，是多众蛇'],
        ]],
    ],
    // The scree: 众蛇.
    'wild' => ['rate' => 6, 'slots' => [[14, 'zhongshe'], [13, 'zhongshe'], [14, 'zhongshe'], [13, 'zhongshe'], [14, 'zhongshe'], [16, 'zhongshe'], [16, 'zhongshe']]],
];
