<?php
// 升山, twenty li northeast; the range's 冢, the hill its rites are greatest at. The classic: 榖, 柞 and thorn, yams and 蕙;
// the 黄酸之水 rises on it and runs north into the 河, with much 璇玉 in it. It names no beast.
declare(strict_types=1);

return [
    'id' => 'shengshan',
    'name' => '升山',
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
    'exits' => ['left' => ['guwei', 0], 'right' => ['yangxu', 0]],
    'signs' => [
        [12, 6, [
            ['say', '升山 —— 其木多榖柞棘；黄酸之水出焉，而北流注于河'],
        ]],
    ],
];
