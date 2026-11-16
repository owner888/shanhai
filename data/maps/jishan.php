<?php
// 基山, three hundred li east of 亶爰之山. The classic: on its south side much jade, on its north many strange trees;
// 猼訑 and 尚付 live there. So trees to the north, rock to the south.
declare(strict_types=1);

return [
    'id' => 'jishan',
    'name' => '基山',
    'range' => 1,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'TTT,,,TTTT,,,,TTT,,T',
        'TT,,,,,TT,,,,,,T,,,T',
        'T.,,,,..T.,,,,...,,T',
        'T...T.........T....T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T...^......^^......T',
        'T..^^^....^^^^..,,.T',
        '^...^..,,..^^..,,,.^',
        '^^....,,,,....,,,.^^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['danyuan', 0], 'right' => ['qingqiu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '基山 —— 阳多玉，阴多怪木'],
        ]],
    ],
    // The tall grass: 猼訑 and 尚付, the two beasts the classic gives this mountain.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 白猿、蝮虫、白猿、蝮虫 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[3, 'baiyuan'], [4, 'fuchong'], [5, 'baiyuan'], [3, 'fuchong'], [6, 'boyi'], [5, 'changfu'], [5, 'boyi']]],
];
