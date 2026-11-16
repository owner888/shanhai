<?php
// 钩吾之山, three hundred and fifty li north of 敦头之山. The classic: on it much jade, below it much copper; the beast
// 狍鸮 lives there, a sheep's body with a human face, its eyes under its arms, a tiger's teeth and a human's nails,
// which eats people.
declare(strict_types=1);

return [
    'id' => 'gouwu',
    'name' => '钩吾之山',
    'range' => 9,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^.......,,,,,......^',
        '^.......,,,,,......^',
        '^.......,,,,,......^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['duntou', 0], 'right' => ['beixiao', 0]],
    'signs' => [
        [9, 6, [
            ['say', '钩吾之山 —— 其上多玉，其下多铜'],
        ]],
    ],
    // The tall grass: 狍鸮.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 马、闾、麋、马 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[28, 'ma'], [28, 'lyu'], [32, 'mi'], [30, 'ma'], [30, 'paoxiao'], [30, 'paoxiao'], [30, 'paoxiao']]],
];
