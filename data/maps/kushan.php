<?php
// 苦山, twenty li east; one of the range's three 冢. The classic: the beast 山膏 lives there, a pig red as cinnabar fire,
// given to cursing; a tree called 黄棘, and a herb called 无条.
declare(strict_types=1);

return [
    'id' => 'kushan',
    'name' => '苦山',
    'range' => 21,
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
    'exits' => ['left' => ['guyao', 0], 'right' => ['dushanqi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '苦山 —— 其上有木焉，名曰黄棘，黄华而员叶'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[38, 'shangao'], [39, 'shangao'], [39, 'shangao'], [41, 'shangao'], [40, 'shangao'], [42, 'shangao'], [42, 'shangao']]],
];
