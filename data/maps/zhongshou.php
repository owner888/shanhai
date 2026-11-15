<?php
// 众兽之山, two hundred and fifty li west of 厎阳之山. The classic: on it much 㻬琈 jade, below it many 檀 and 楮, and much
// gold; its beasts are mostly 犀 and 兕.
declare(strict_types=1);

return [
    'id' => 'zhongshou',
    'name' => '众兽之山',
    'range' => 5,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^..........^^^^^^^',
        '^....,,,,,,....^^^^^',
        '^....,,,,,,........^',
        '^....,,,,,,........^',
        '^..................^',
        '^..................^',
        '====================',
        'T..................T',
        'T..TT....TT........T',
        'T..TT....TT..,,,,..T',
        'T............,,,,..T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['huangren', 0], 'right' => ['diyang', 0]],
    'signs' => [
        [13, 6, [
            ['say', '众兽之山 —— 其上多㻬琈之玉，其下多檀楮，多黄金；其兽多犀兕'],
        ]],
    ],
    // The tall grass: 犀 and 兕.
    'wild' => ['rate' => 10, 'slots' => [[16, 'xi'], [16, 'si'], [16, 'xi'], [16, 'si'], [13, 'xi'], [13, 'si'], [13, 'xi']]],
];
