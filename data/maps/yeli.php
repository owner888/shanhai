<?php
// 谒戾之山, two hundred li north. The classic: pines and cypresses, gold and jade; the 沁水 rises on it and runs south into
// the 河; to its east is the forest 丹林. It names no beast.
declare(strict_types=1);

return [
    'id' => 'yeli',
    'name' => '谒戾之山',
    'range' => 10,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T..................T',
        '====================',
        'T........~~........T',
        'T........~~........T',
        'T...TT...~~........T',
        'T...TT...~~........T',
        'T........~~........T',
        'TTTTTTTTT~~TTTTTTTTT',
    ],
    'exits' => ['left' => ['xuanyuanbei', 0], 'right' => ['juru', 0]],
    'signs' => [
        [12, 6, [
            ['say', '谒戾之山 —— 其上多松柏，有金玉；沁水出焉，南流注于河'],
        ]],
    ],
];
