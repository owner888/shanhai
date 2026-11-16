<?php
// 浮戏之山, thirty li east. The classic: a tree called 亢木; the 汜水 rises on it and runs north into the 河; east of it a valley
// called 蛇谷, with much 少辛. It names no beast.
declare(strict_types=1);

return [
    'id' => 'fuxi',
    'name' => '浮戏之山',
    'range' => 21,
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
    'exits' => ['left' => ['yingliang', 0], 'right' => ['shaoxing', 0]],
    'signs' => [
        [12, 6, [
            ['say', '浮戏之山 —— 汜水出焉，而北流注于河；其东有谷，因名曰蛇谷'],
        ]],
    ],
];
