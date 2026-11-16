<?php
// 石者之山, two hundred and eighty li north of 丹熏之山. The classic: no grass or trees, much 瑶 and 碧; the 泚水 rises on
// it and runs west into the 河; the beast 孟极 lives there, a leopard with a patterned brow and a white body, which
// lies in wait.
declare(strict_types=1);

return [
    'id' => 'shizhe',
    'name' => '石者之山',
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
        '^-------:::::------^',
        '^-------:::::------^',
        '^-------:::::------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['danxun', 0], 'right' => ['bianchun', 0]],
    'signs' => [
        [9, 6, [
            ['say', '石者之山 —— 其上无草木，多瑶碧；泚水出焉，西流注于河'],
        ]],
    ],
    // The scree: 孟极.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 旄牛、尸鸠、橐驼、旄牛 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[23, 'maoniu'], [22, 'shijiu'], [22, 'tuotuo'], [24, 'maoniu'], [24, 'mengji'], [26, 'mengji'], [26, 'mengji']]],
];
