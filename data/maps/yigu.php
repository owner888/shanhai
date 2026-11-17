<?php
// 依轱之山, thirty li southeast. The classic: 杻, 橿 and 柤; the beast 獜 lives there, a dog with a tiger's claws and scales,
// good at leaping.
declare(strict_types=1);

return [
    'id' => 'yigu',
    'name' => '依轱之山',
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
    'exits' => ['left' => ['jinli', 0], 'right' => ['jigu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '依轱之山 —— 其上多杻橿，多柤'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 玄豹、闾、麈、玄豹 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[38, 'xuanbao'], [39, 'lyu'], [39, 'zhulu'], [41, 'xuanbao'], [40, 'linquan'], [42, 'linquan'], [42, 'linquan']]],
];
