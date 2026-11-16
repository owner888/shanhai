<?php
// 北嚻之山, three hundred li north of 钩吾之山. The classic: the beast 独峪 lives there, with a horse's tail and a boar's
// mane; and the bird 般鹛, a crow with a human face, which flies by night and hides by day; the 涔水 rises on it and runs
// east into 邛泽.
declare(strict_types=1);

return [
    'id' => 'beixiao',
    'name' => '北嚻之山',
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
        '^...........~~~~~~~^',
        '^...........~~~~~~~^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['gouwu', 0], 'right' => ['liangqu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '北嚻之山 —— 涔水出焉，而东流注于邛泽'],
        ]],
    ],
    // The tall grass: 独峪 and 般鹛.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 怪蛇、马、闾、怪蛇 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 6, 'slots' => [[34, 'guaishe'], [32, 'ma'], [33, 'lyu'], [34, 'guaishe'], [35, 'banmao'], [35, 'duyu'], [35, 'banmao']]],
];
