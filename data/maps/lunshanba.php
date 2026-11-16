<?php
// 纶山, three hundred and fifty li northeast (`lunshan` is 伦山, of the north). The classic: 梓 and 楠, 桃枝, 柤, chestnut,
// orange and pomelo; its beasts are mostly 闾, 麈, 麢 and 㚟.
declare(strict_types=1);

return [
    'id' => 'lunshanba',
    'name' => '纶山',
    'range' => 22,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..............TT..T',
        'T..................T',
        '====================',
        'T..................T',
        'T.......,,,,,......T',
        'T.......,,,,,^^....T',
        'T.......,,,,,^^....T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['yizhu', 0], 'right' => ['lugui', 0]],
    'signs' => [
        [9, 6, [
            ['say', '纶山 —— 其木多梓楠，多桃枝，多柤栗橘櫾，其兽多闾麈麢㚟'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[40, 'lyu'], [41, 'zhulu'], [41, 'ling'], [43, 'chuotu'], [42, 'lyu'], [44, 'zhulu'], [44, 'ling']]],
];
