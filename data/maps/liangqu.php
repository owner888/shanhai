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
    'wild' => ['rate' => 10, 'slots' => [[13, 'juji'], [13, 'xiaoque'], [14, 'juji'], [15, 'xiaoque'], [12, 'juji'], [15, 'xiaoque'], [15, 'juji']]],
];
