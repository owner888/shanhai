<?php
// 奥山, three hundred li east. The classic: cypress, 杻 and 橿; 㻬琈 jade on its south side; the 奥水 rises on it and runs east
// into the 瀙. It names no beast.
declare(strict_types=1);

return [
    'id' => 'aoshan',
    'name' => '奥山',
    'range' => 25,
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
    'exits' => ['left' => ['chouyang', 0], 'right' => ['fushansy', 0]],
    'signs' => [
        [9, 6, [
            ['say', '奥山 —— 其上多柏杻橿，其阳多㻬琈之玉；奥水出焉，东流注于瀙水'],
        ]],
    ],
];
