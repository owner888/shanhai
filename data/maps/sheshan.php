<?php
// 蛇山, four hundred li east. The classic: gold on it, clay below; 栒 and 豫章, 嘉荣 and 少辛; the beast 迤狼 lives there, a fox
// with a white tail and long ears, at whose sight there is war in the state.
declare(strict_types=1);

return [
    'id' => 'sheshan',
    'name' => '蛇山',
    'range' => 23,
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
    'exits' => ['left' => ['gaoliang', 0], 'right' => ['lishanjiu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '蛇山 —— 其上多黄金，其下多垩，其木多栒，多豫章'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[42, 'yilang'], [43, 'yilang'], [43, 'yilang'], [45, 'yilang'], [44, 'yilang'], [46, 'yilang'], [46, 'yilang']]],
];
