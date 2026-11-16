<?php
// 渠猪之山, fifteen li east. The classic: bamboo; the 渠猪之水 rises on it and runs south into the 河, with many 豪鱼 in it,
// sturgeon-like with a red beak and red feathers on the tail.
declare(strict_types=1);

return [
    'id' => 'quzhu',
    'name' => '渠猪之山',
    'range' => 15,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T........~~........T',
        'T........~~........T',
        'T...TT...~~........T',
        'T...TT...~~........T',
        'T........~~........T',
        'TTTTTTTTT~~TTTTTTTTT',
    ],
    'exits' => ['left' => ['lier', 0], 'right' => ['conglong', 0]],
    'signs' => [
        [12, 6, [
            ['say', '渠猪之山 —— 其上多竹；渠猪之水出焉，而南流注于河'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'haoyu'], [15, 'haoyu'], [20, 'haoyu']]],
];
