<?php
// 石脆之山, sixty li west of 符禺之山. The classic: its trees are mostly 棕 and 枏; on its south side much 㻬琈 jade, on
// its north much copper; the 灌水 rises on it and runs north into the 禺水, with red earth in it. It names no beast.
declare(strict_types=1);

return [
    'id' => 'shicui',
    'name' => '石脆之山',
    'range' => 4,
    'rows' => [
        'TTTTTTT~~TTTTTTTTTTT',
        'T......~~.....TT...T',
        'T..TT..~~..........T',
        'T..TT..~~~.....TT..T',
        'T.......~~.........T',
        'T.......~~.........T',
        'T..................T',
        '====================',
        'T..................T',
        'T....TT.......TT...T',
        'T....TT.......TT...T',
        'T..................T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['yingshan', 0], 'right' => ['fuyu4', 0]],
    'signs' => [
        [11, 6, [
            ['say', '石脆之山 —— 其木多棕枏；灌水出焉，北流注于禺水'],
            ['ifFlag', 'found_liuzhe', 'had'],
            ['setFlag', 'found_liuzhe'],
            ['give', 'liuzhe', 1],
            ['say', '牌子脚下有一块流赭。你捡了起来。'],
            ['label', 'had'],
        ]],
    ],
];
