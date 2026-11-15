<?php
// 青丘之山, three hundred li east of 基山. The classic: on its south side much jade, on its north much 青䨼; the
// nine-tailed fox lives there, which eats people, and 灌灌; the 英水 rises on it and runs south into the marsh of
// 即翼, and in it are many 赤鱬.
declare(strict_types=1);

return [
    'id' => 'qingqiu',
    'name' => '青丘之山',
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^.,,,,^^..TT.,,,,^^',
        '^.,,,,,.^..T.,,,,,.^',
        'T.,,,,........,,,,.T',
        'T..,,..........,,..T',
        'T..................T',
        'T..................T',
        '====================',
        'T.......~~.........T',
        'T..^....~~~....^...T',
        'T.^^^...~~~...^^^..T',
        '^..^...~~~~~...^...^',
        '^^....~~~~~~~.....^^',
        '^^^^^~~~~~~~~~^^^^^^',
    ],
    'exits' => ['left' => ['jishan', 0], 'right' => ['jiwei', 0]],
    'signs' => [
        [9, 6, [
            ['say', '青丘之山 —— 阳多玉，阴多青䨼；英水南流'],
        ]],
    ],
    // The 英水: 赤鱬.
    'water' => ['rate' => 6, 'slots' => [[16, 'chiru'], [17, 'chiru'], [15, 'chiru']]],
    // The tall grass: 灌灌, and seldom the nine-tailed fox.
    'wild' => ['rate' => 10, 'slots' => [[16, 'guanguan'], [17, 'guanguan'], [18, 'guanguan'], [17, 'guanguan'], [19, 'guanguan'], [20, 'jiuweihu'], [22, 'jiuweihu']]],
];
