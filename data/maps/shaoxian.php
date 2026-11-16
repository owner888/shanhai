<?php
// 少咸之山, two hundred li north of 敦薨之山. The classic: no grass or trees, much 青碧; the beast 窫窳 lives there, an
// ox with a red body, a human face and a horse's feet, which eats people; the 敦水 rises on it and runs east, and in
// it is the fish 䰽䰽, which kills who eats it.
declare(strict_types=1);

return [
    'id' => 'shaoxian',
    'name' => '少咸之山',
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
        '^-----------~~~~~~~^',
        '^-----------~~~~~~~^',
        '^------------------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['dunhong', 0], 'right' => ['yufa', 0]],
    'signs' => [
        [9, 6, [
            ['say', '少咸之山 —— 无草木，多青碧；敦水出焉，东流注于雁门之水'],
        ]],
    ],
    // The 敦水: 䰽䰽之鱼.
    'water' => ['rate' => 2, 'slots' => [[20, 'beibei'], [15, 'beibei'], [20, 'beibei']]],
    // The scree: 窫窳.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 兕、旄牛、尸鸠、兕 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 2, 'slots' => [[23, 'si'], [24, 'maoniu'], [24, 'shijiu'], [25, 'si'], [26, 'yayu'], [22, 'yayu'], [24, 'yayu']]],
];
