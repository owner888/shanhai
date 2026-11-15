<?php
// 竹山, fifty-two li west of 英山. The classic: on it many tall trees, on its north side much iron; the 竹水 rises on it
// and runs north into the 渭; the 丹水 rises on it and runs southeast into the 洛水, with much 水玉 and many 人鱼 in it;
// the beast 豪彘 lives there.
declare(strict_types=1);

return [
    'id' => 'zhushan',
    'name' => '竹山',
    'range' => 4,
    'rows' => [
        'TTTTTTT~~TTTTTTTTTTT',
        'T......~~......TT..T',
        'T.,,,,.~~..,,,,....T',
        'T.,,,,.~~..,,,,....T',
        'T.,,,,..~..,,,,....T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T...TT......~~.....T',
        'T...TT......~~~....T',
        'T............~~~~..T',
        'T..............~~~.T',
        'TTTTTTTTTTTTTTTT~~TT',
    ],
    'exits' => ['left' => ['fushan', 0], 'right' => ['yingshan', 0]],
    'signs' => [
        [12, 6, [
            ['say', '竹山 —— 其上多乔木，其阴多铁；竹水北流，丹水东南流'],
        ]],
    ],
    // The 丹水: 人鱼.
    'water' => ['rate' => 2, 'slots' => [[20, 'renyu'], [15, 'renyu'], [20, 'renyu']]],
    // The tall grass: 豪彘.
    'wild' => ['rate' => 6, 'slots' => [[14, 'haozhi'], [14, 'haozhi'], [16, 'haozhi'], [15, 'haozhi'], [12, 'haozhi'], [16, 'haozhi'], [16, 'haozhi']]],
];
