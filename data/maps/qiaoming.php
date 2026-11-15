<?php
// 谯明之山, four hundred li north of 带山. The classic: the 谯水 rises on it and runs west into the 河, and in it is the
// fish 何罗, one head on ten bodies; the beast 孟槐 lives there, with red bristles; no grass or trees, much 青雄黄.
declare(strict_types=1);

return [
    'id' => 'qiaoming',
    'name' => '谯明之山',
    'range' => 8,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^--:::::----:::::--^',
        '^--:::::----:::::--^',
        '^--:::::----:::::--^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^~~~~~~~~----------^',
        '^~~~~~~~~----------^',
        '^------------------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['daishan', 0], 'right' => ['zhuoguang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '谯明之山 —— 谯水出焉，西流注于河；是山也，无草木，多青雄黄'],
        ]],
    ],
    // The 谯水: 何罗之鱼.
    'water' => ['rate' => 2, 'slots' => [[20, 'heluo'], [15, 'heluo'], [20, 'heluo']]],
    // The scree: 孟槐.
    'wild' => ['rate' => 2, 'slots' => [[21, 'menghuai'], [22, 'menghuai'], [22, 'menghuai'], [23, 'menghuai'], [24, 'menghuai'], [22, 'menghuai'], [22, 'menghuai']]],
];
