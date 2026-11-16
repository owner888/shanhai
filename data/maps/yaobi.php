<?php
// 瑶碧之山, sixty li east. The classic: 梓 and 楠; 青雘 on its north side, white gold on its south; a bird lives there like a
// pheasant, which eats 蜚, called 鸩.
declare(strict_types=1);

return [
    'id' => 'yaobi',
    'name' => '瑶碧之山',
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
    'exits' => ['left' => ['pishan', 0], 'right' => ['gongli', 0]],
    'signs' => [
        [9, 6, [
            ['say', '瑶碧之山 —— 其木多梓楠，其阴多青雘，其阳多白金'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[46, 'zhenniao'], [47, 'zhenniao'], [47, 'zhenniao'], [49, 'zhenniao'], [48, 'zhenniao'], [50, 'zhenniao'], [50, 'zhenniao']]],
];
