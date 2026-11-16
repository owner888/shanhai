<?php
// 梁渠之山, three hundred and fifty li north of 北嚻之山. The classic: no grass or trees, much gold and jade; the 修水
// rises on it and runs east into 雁门; its beasts are mostly 居暨, a hedgehog with red hair; the bird 嚻 lives there,
// four wings, one eye and a dog's tail.
declare(strict_types=1);

return [
    'id' => 'liangqu',
    'name' => '梁渠之山',
    'range' => 9,
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
        '^------------------^',
        '^-----------~~~~~~~^',
        '^-----------~~~~~~~^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['beixiao', 0], 'right' => ['guguan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '梁渠之山 —— 无草木，多金玉；修水出焉，而东流注于雁门'],
        ]],
    ],
    // The scree: 居暨 and the bird 嚻.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 白翟、白䳑、怪蛇、白翟 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[13, 'baidi'], [13, 'baiyou'], [14, 'guaishe'], [15, 'baidi'], [12, 'juji'], [15, 'xiaoque'], [15, 'juji']]],
];
