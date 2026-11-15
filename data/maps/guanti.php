<?php
// 灌题之山, three hundred and twenty li north of 单张之山. The classic: on it many 樗 and 柘, below it flowing sand and
// whetstone; the beast 那父 lives there, an ox with a white tail; and the bird 竦斯, a hen pheasant with a human face;
// the 匠韩之水 rises on it and runs west into 泑泽, with lodestone in it.
declare(strict_types=1);

return [
    'id' => 'guanti',
    'name' => '灌题之山',
    'range' => 8,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T..--------........T',
        'T..--------........T',
        'T..--------........T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['danzhang', 0], 'right' => ['panhou', 0]],
    'signs' => [
        [9, 6, [
            ['say', '灌题之山 —— 其上多樗柘，其下多流沙，多砥'],
        ]],
    ],
    // The tall grass: 那父 and 竦斯.
    'wild' => ['rate' => 2, 'slots' => [[24, 'nafu'], [25, 'songsi'], [25, 'nafu'], [26, 'songsi'], [22, 'nafu'], [24, 'songsi'], [26, 'nafu']]],
];
