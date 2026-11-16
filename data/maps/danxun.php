<?php
// 丹熏之山, two hundred li north of 虢山之尾. The classic: on it many 樗 and cypresses, its herbs mostly 韭 and 䪥, much
// 丹雘; the 熏水 rises on it and runs west into the 棠水; the beast 耳鼠 lives there, a rat with a hare's head, which
// flies by its tail.
declare(strict_types=1);

return [
    'id' => 'danxun',
    'name' => '丹熏之山',
    'range' => 8,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,.....TT....T',
        'T..,,,,,.....TT....T',
        'T..,,,,,...........T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T...........,,,,,..T',
        'T...........,,,,,..T',
        'T...........,,,,,..T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['guowei', 0], 'right' => ['shizhe', 0]],
    'signs' => [
        [9, 6, [
            ['say', '丹熏之山 —— 其上多樗柏，其草多韭䪥，多丹雘'],
        ]],
    ],
    // The tall grass: 耳鼠.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 橐驼、麢、蕃、橐驼 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 2, 'slots' => [[24, 'tuotuo'], [25, 'ling'], [25, 'fan'], [26, 'tuotuo'], [22, 'ershu'], [24, 'ershu'], [26, 'ershu']]],
];
