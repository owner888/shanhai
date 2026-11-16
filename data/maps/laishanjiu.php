<?php
// 崃山, a hundred and forty li northeast (`laishan` is 莱山, of the west). The classic: the 江 rises on it and runs east into
// the great 江; gold on its south side, elk and 麈 on its north; 檀 and 柘, 䪥 and leek.
declare(strict_types=1);

return [
    'id' => 'laishanjiu',
    'name' => '崃山',
    'range' => 23,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
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
    'exits' => ['left' => ['minshan', 0], 'right' => ['jushan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '崃山 —— 江水出焉，东流注大江；其阳多黄金，其阴多麋麈'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[42, 'mi'], [43, 'zhulu'], [43, 'mi'], [45, 'zhulu'], [44, 'mi'], [46, 'zhulu'], [46, 'mi']]],
];
