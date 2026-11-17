<?php
// 风雨之山, a hundred and fifty li east; one of the range's 冢. The classic: white gold on it, 石涅 below; 棷, 椫 and poplar; the
// 宣余之水 rises on it and runs east into the 江, with many snakes in it; its beasts are mostly 闾, elk, 麈, leopards and
// tigers; its birds 白鷮.
declare(strict_types=1);

return [
    'id' => 'fengyu',
    'name' => '风雨之山',
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
    'exits' => ['left' => ['goumi', 0], 'right' => ['yushanjiu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '风雨之山 —— 其上多白金，其下多石涅；宣余之水出焉，东流注于江，其中多蛇'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[35, 'lyu'], [36, 'mi'], [36, 'zhulu'], [38, 'bao'], [37, 'hu'], [39, 'baijiao'], [39, 'lyu']]],
];
