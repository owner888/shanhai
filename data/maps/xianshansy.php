<?php
// 鲜山, thirty li east (`xianshanzhong` is the 鲜山 of the second). The classic: 楢, 杻 and 苴; gold on its south side, iron on
// its north; the beast 移即 lives there, a 膜 dog with a red beak, red eyes and a white tail, at whose sight a town has
// fire.
declare(strict_types=1);

return [
    'id' => 'xianshansy',
    'name' => '鲜山',
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
    'exits' => ['left' => ['yushanyu', 0], 'right' => ['gaoshansy', 0]],
    'signs' => [
        [9, 6, [
            ['say', '鲜山 —— 其木多楢杻苴，其阳多金，其阴多铁'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 麋、㸲牛、鸜鹆、麋 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[38, 'mi'], [39, 'zuoniu'], [39, 'quyu'], [41, 'mi'], [40, 'yiji'], [42, 'yiji'], [42, 'yiji']]],
];
