<?php
// 牡山, three hundred li west. The classic: patterned stone on it, bamboo below; its beasts are mostly 㸲牛 and 羬羊, its
// birds 赤鷩.
declare(strict_types=1);

return [
    'id' => 'mushan',
    'name' => '牡山',
    'range' => 18,
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
    'exits' => ['left' => ['xionger', 0], 'right' => ['huanju', 0]],
    'signs' => [
        [9, 6, [
            ['say', '牡山 —— 其上多文石，其下多竹箭、竹䉋；其兽多㸲牛、羬羊，鸟多赤鷩'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[31, 'zuoniu'], [32, 'chibi'], [32, 'zuoniu'], [34, 'chibi'], [33, 'qianyang'], [35, 'qianyang'], [35, 'qianyang']]],
];
