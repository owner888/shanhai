<?php
// 廆山, thirty li east. The classic: 㻬琈 jade on its north side; west of it a valley called 雚谷, with willow and 楮, where
// the bird 鸰䳩 lives, a pheasant with a long tail, red as cinnabar fire, with a green beak.
declare(strict_types=1);

return [
    'id' => 'guishanliu',
    'name' => '廆山',
    'range' => 20,
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
    'exits' => ['left' => ['zhanzhu', 0], 'right' => ['gaodi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '廆山 —— 其阴多㻬琈之玉；其西有谷焉，名曰雚谷，其木多柳楮'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 㸲牛、赤鷩、㸲牛、赤鷩 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[32, 'zuoniu'], [33, 'chibi'], [33, 'zuoniu'], [35, 'chibi'], [34, 'lingyao'], [36, 'lingyao'], [36, 'lingyao']]],
];
