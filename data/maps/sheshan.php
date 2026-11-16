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
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 㚟、夔牛、犀、㚟 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[42, 'chuotu'], [43, 'kuiniu'], [43, 'xi'], [45, 'chuotu'], [44, 'yilang'], [46, 'yilang'], [46, 'yilang']]],
];
