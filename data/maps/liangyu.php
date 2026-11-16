<?php
// 良馀之山, ten li east. The classic: 榖 and 柞, no stone; the 馀水 rises on its north side and runs north into the 河; the 乳水 on
// its south and runs southeast into the 洛. It names no beast.
declare(strict_types=1);

return [
    'id' => 'liangyu',
    'name' => '良馀之山',
    'range' => 19,
    'rows' => [
        'TTTTTTTTT~~TTTTTTTTT',
        'T........~~........T',
        'T........~~........T',
        'T........~~........T',
        'T........~~........T',
        'T........~~........T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T...TT.............T',
        'T...TT.............T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['shishanwu', 0], 'right' => ['guwei', 0]],
    'signs' => [
        [12, 6, [
            ['say', '良馀之山 —— 其上多榖柞，无石；馀水出于其阴，而北流注于河'],
        ]],
    ],
];
