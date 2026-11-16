<?php
// 复州之山, twenty li west. The classic: 檀 trees, gold on its south side; the bird 跂踵 lives there, an owl with one foot and
// a pig's tail, at whose sight a state has great plague.
declare(strict_types=1);

return [
    'id' => 'fuzhou',
    'name' => '复州之山',
    'range' => 24,
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
    'exits' => ['left' => ['yongshi', 0], 'right' => ['chushan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '复州之山 —— 其木多檀，其阳多黄金'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 闾、麋 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[44, 'quyu'], [45, 'lyu'], [45, 'mi'], [47, 'quyu'], [46, 'qizhongniao'], [48, 'qizhongniao'], [48, 'qizhongniao']]],
];
