<?php
// 柄山, two hundred and fifty li west. The classic: jade on it, copper below; the 滔雕之水 rises on it and runs north into the
// 洛, where there are many 羬羊; a tree called 茇 that poisons fish.
declare(strict_types=1);

return [
    'id' => 'bingshan',
    'name' => '柄山',
    'range' => 18,
    'rows' => [
        'TTTTTTTTT~~TTTTTTTTT',
        'T........~~........T',
        'T..,,,,,.~~.,,,,,..T',
        'T..,,,,,.~~.,,,,,..T',
        'T..,,,,,.~~.,,,,,..T',
        'T........~~........T',
        'T..................T',
        '====================',
        'T..................T',
        'T..,,,,,...........T',
        'T..,TT,,...........T',
        'T..,TT,,...........T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['jiweizhong', 0], 'right' => ['baibian', 0]],
    'signs' => [
        [12, 6, [
            ['say', '柄山 —— 其上多玉，其下多铜；滔雕之水出焉，而北流注于洛'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[31, 'qianyang'], [32, 'qianyang'], [32, 'qianyang'], [34, 'qianyang'], [33, 'qianyang'], [35, 'qianyang'], [35, 'qianyang']]],
];
