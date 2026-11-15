<?php
// 高是之山, three hundred li north. The classic: the 滋水 rises on it and runs south into the 虖沱; 棕 trees and 条 herbs. It
// names no beast.
declare(strict_types=1);

return [
    'id' => 'gaoshi',
    'name' => '高是之山',
    'range' => 10,
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
    'exits' => ['left' => ['tongrong', 0], 'right' => ['lushanbei', 0]],
    'signs' => [
        [12, 6, [
            ['say', '高是之山 —— 滋水出焉，而南流注于虖沱；其木多棕，其草多条'],
        ]],
    ],
];
