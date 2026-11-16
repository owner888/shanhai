<?php
// 穀山, seventy-two li east. The classic: 榖 on it, mulberry below; the 爽水 rises on it and runs northwest into the 穀水. It
// names no beast.
declare(strict_types=1);

return [
    'id' => 'gushan',
    'name' => '穀山',
    'range' => 20,
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
    'exits' => ['left' => ['mishanliu', 0], 'right' => ['baishi', 0]],
    'signs' => [
        [12, 6, [
            ['say', '穀山 —— 其上多榖，其下多桑'],
        ]],
    ],
];
