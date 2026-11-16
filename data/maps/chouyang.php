<?php
// 丑阳之山, two hundred li east. The classic: 椆 and 椐; the bird 䳅鵌 lives there, a crow with red feet, which wards off fire.
declare(strict_types=1);

return [
    'id' => 'chouyang',
    'name' => '丑阳之山',
    'range' => 25,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..............TT..T',
        'T..................T',
        '====================',
        'T..................T',
        'T.......,,,,,......T',
        'T.......,,,,,^^....T',
        'T.......,,,,,^^....T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['qiushan', 0], 'right' => ['aoshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '丑阳之山 —— 其上多椆椐'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 㸲牛、鸜鹆、玄豹、㸲牛 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[46, 'zuoniu'], [47, 'quyu'], [47, 'xuanbao'], [49, 'zuoniu'], [48, 'zhitu'], [50, 'zhitu'], [50, 'zhitu']]],
];
