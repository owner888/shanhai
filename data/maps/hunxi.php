<?php
// 浑夕之山, a hundred and eighty li north of 北岳之山. The classic: no grass or trees, much copper and jade; the 嚻水
// rises on it and runs northwest into the sea; a snake lives there with one head and two bodies, 肥遗, at whose sight
// there is great drought.
declare(strict_types=1);

return [
    'id' => 'hunxi',
    'name' => '浑夕之山',
    'range' => 8,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^--:::::-----------^',
        '^--:::::-----^^^---^',
        '^--:::::-----^^^---^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^-----------:::::--^',
        '^-----------:::::--^',
        '^-----------:::::--^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['beiyue', 0], 'right' => ['beidan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '浑夕之山 —— 无草木，多铜玉；嚻水出焉，而西北流注于海'],
        ]],
    ],
    // The scree: the snake 肥遗.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 尸鸠、橐驼、麢、尸鸠 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 2, 'slots' => [[21, 'shijiu'], [22, 'tuotuo'], [22, 'ling'], [23, 'shijiu'], [24, 'feiyishe'], [22, 'feiyishe'], [22, 'feiyishe']]],
];
