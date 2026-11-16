<?php
// 首山, three hundred li east; a 䰠, a hill that has a god. The classic: 榖 and 柞 on its north side, 㻬琈 jade and 槐 on its
// south; a valley on its north side called 机谷, with many 䲦鸟 in it, owls with three eyes and ears, which call like deer.
declare(strict_types=1);

return [
    'id' => 'shoushan',
    'name' => '首山',
    'range' => 19,
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
    'exits' => ['left' => ['goulin', 0], 'right' => ['xianzhu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '首山 —— 其阴多榖柞，其阳多㻬琈之玉，木多槐；其阴有谷，曰机谷'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[33, 'dainiao'], [34, 'dainiao'], [34, 'dainiao'], [36, 'dainiao'], [35, 'dainiao'], [37, 'dainiao'], [37, 'dainiao']]],
];
