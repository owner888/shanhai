<?php
// 潘侯之山, two hundred li north of 灌题之山. The classic: on it many pines and cypresses, below it 榛 and 楛; on its
// south side much jade, on its north much iron; the beast 旄牛 lives there, an ox with hair at its four joints.
declare(strict_types=1);

return [
    'id' => 'panhou',
    'name' => '潘侯之山',
    'range' => 8,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,...........T',
        'T..,,,,,.....TT....T',
        'T..,,,,,.....TT....T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T..........,,,,,...T',
        'T..........,,,,,...T',
        'T..........,,,,,...T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['guanti', 0], 'right' => ['xiaoxian', 0]],
    'signs' => [
        [9, 6, [
            ['say', '潘侯之山 —— 其上多松柏，其下多榛楛，其阳多玉，其阴多铁'],
        ]],
    ],
    // The tall grass: 旄牛.
    'wild' => ['rate' => 10, 'slots' => [[23, 'maoniu'], [22, 'maoniu'], [22, 'maoniu'], [24, 'maoniu'], [24, 'maoniu'], [26, 'maoniu'], [26, 'maoniu']]],
];
