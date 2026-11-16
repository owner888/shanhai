<?php
// 暴山, a hundred and eighty li southeast. The classic: palm, 楠, 荆, 芑 and four bamboos; gold and jade on it, patterned
// stone and iron below; its beasts are mostly elk, deer, 麂 and 就. The third is a rare character in the classic, written
// here by its common form; the fourth the commentators read as 鹫, a great bird of prey.
declare(strict_types=1);

return [
    'id' => 'baoshan',
    'name' => '暴山',
    'range' => 26,
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
    'exits' => ['left' => ['dongting', 0], 'right' => ['jigong', 0]],
    'signs' => [
        [9, 6, [
            ['say', '暴山 —— 其上多黄金、玉，其下多文石、铁，其兽多麋鹿麂，就'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[48, 'mi'], [49, 'yelu'], [49, 'jilu'], [51, 'jiuniao'], [50, 'mi'], [52, 'yelu'], [52, 'jilu']]],
];
