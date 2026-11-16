<?php
// 历石之山, seventy li northeast. The classic: 荆 and 芑; gold on its south side, whetstone on its north; the beast 梁渠 lives
// there, a wildcat with a white head and a tiger's claws, at whose sight a state has great war.
declare(strict_types=1);

return [
    'id' => 'lishi',
    'name' => '历石之山',
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
    'exits' => ['left' => ['zhongjiu', 0], 'right' => ['qiushan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '历石之山 —— 其木多荆芑，其阳多黄金，其阴多砥石'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 鸩、麋、㸲牛、鸩 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[46, 'zhenniao'], [47, 'mi'], [47, 'zuoniu'], [49, 'zhenniao'], [48, 'liangqushou'], [50, 'liangqushou'], [50, 'liangqushou']]],
];
