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
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 兕、旄牛、尸鸠、兕 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 2, 'slots' => [[24, 'si'], [25, 'maoniu'], [25, 'shijiu'], [26, 'si'], [22, 'songsi'], [24, 'nafu'], [26, 'songsi']]],
];
