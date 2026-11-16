<?php
// 騩山, ten li east (`guishan` is the 騩山 of the west). The classic: fine jujubes on it, 㻬琈 jade on its north side; the
// 正回之水 rises on it and runs north into the 河, with many 飞鱼 in it, shaped like a piglet with red markings.
declare(strict_types=1);

return [
    'id' => 'guishanzhong',
    'name' => '騩山',
    'range' => 17,
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
    'exits' => ['left' => ['qingyao', 0], 'right' => ['yisu', 0]],
    'signs' => [
        [12, 6, [
            ['say', '騩山 —— 其上有美枣，其阴有㻬琈之玉；正回之水出焉，而北流注于河'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'feiyutun'], [15, 'feiyutun'], [20, 'feiyutun']]],
];
