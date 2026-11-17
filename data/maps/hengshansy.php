<?php
// 衡山, forty-five li east (`hengshan` is the 衡山 of the eighth). The classic: 青雘 and mulberry; its birds are mostly 鸜鹆.
declare(strict_types=1);

return [
    'id' => 'hengshansy',
    'name' => '衡山',
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
    'exits' => ['left' => ['xuanshan', 0], 'right' => ['fengshaner', 0]],
    'signs' => [
        [9, 6, [
            ['say', '衡山 —— 其上多青雘，多桑，其鸟多鸜鹆'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[38, 'quyu'], [39, 'quyu'], [39, 'quyu'], [41, 'quyu'], [40, 'quyu'], [42, 'quyu'], [42, 'quyu']]],
];
