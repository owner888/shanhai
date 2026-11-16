<?php
// 霍山, forty li north. The classic: 榖 trees; the beast 朏朏 lives there, a wildcat with a white tail and a mane, which
// takes sorrow from who keeps it.
declare(strict_types=1);

return [
    'id' => 'huoshan',
    'name' => '霍山',
    'range' => 15,
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
    'exits' => ['left' => ['niushou', 0], 'right' => ['hegu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '霍山 —— 其木多榖'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's fifteenth gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 麋、鹖、闾、麋 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[2, 'mi'], [2, 'he'], [3, 'lyu'], [3, 'mi'], [6, 'feifei'], [4, 'feifei'], [4, 'feifei']]],
];
