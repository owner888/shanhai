<?php
// 天帝之山, three hundred and fifty li west of 嶓冢之山. The classic: on it many 棕 and 枏, below it much 菅 and 蕙; the
// beast 谿边 lives there, and the bird 栎; the herb 杜衡 grows there.
declare(strict_types=1);

return [
    'id' => 'tiandi',
    'name' => '天帝之山',
    'range' => 4,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T.TT....TT.....TT..T',
        'T.TT....TT.....TT..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T....,,,,,.........T',
        'T....,,,,,.....T...T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['gaotu', 0], 'right' => ['bozhong', 0]],
    'signs' => [
        [10, 6, [
            ['say', '天帝之山 —— 上多棕枏，下多菅蕙'],
            ['ifFlag', 'found_duheng', 'had'],
            ['setFlag', 'found_duheng'],
            ['give', 'duheng', 1],
            ['say', '牌子旁边长着杜衡。你采了一份。'],
            ['label', 'had'],
        ]],
    ],
    // The tall grass: 谿边 and the bird 栎.
    'wild' => ['rate' => 10, 'slots' => [[13, 'li'], [14, 'xibian'], [15, 'li'], [16, 'xibian'], [15, 'li'], [15, 'xibian'], [15, 'li']]],
];
