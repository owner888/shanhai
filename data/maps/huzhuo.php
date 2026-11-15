<?php
// 虖勺之山, four hundred li east of 洵山. The classic: on it many 梓 and 枏 trees, below it many thorns and 杞; the
// 滂水 rises on it and runs east to the sea. It names no beast.
declare(strict_types=1);

return [
    'id' => 'huzhuo',
    'name' => '虖勺之山',
    'range' => 2,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T.TTTTTTTTTTTTTTTT.T',
        'T.TTTTTTTTTTTTTTTT.T',
        'T...TTT.....TTTT...T',
        'T...TTT.....TTTT...T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T.....~~~~~~~~~~~~~~',
        'T.....~~~~~~~~~~~~~~',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['xunshan', 0], 'right' => ['quwu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '虖勺之山 —— 滂水出焉，东流注于海'],
        ]],
    ],
];
