<?php
// 京山, two hundred li east. The classic: fine jade, 漆 trees and bamboo; red copper on its south side, black whetstone on
// its north; the 高水 rises on it and runs south into the 河. It names no beast.
declare(strict_types=1);

return [
    'id' => 'jingshaner',
    'name' => '京山',
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
    'exits' => ['left' => ['pingshan', 0], 'right' => ['chongwei', 0]],
    'signs' => [
        [12, 6, [
            ['say', '京山 —— 有美玉，多漆木，多竹；高水出焉，南流注于河'],
        ]],
    ],
];
