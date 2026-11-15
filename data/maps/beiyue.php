<?php
// 北岳之山, two hundred li north of 狱法之山. The classic: many 枳, thorn and hard wood; the beast 诸怀 lives there, an
// ox with four horns, a human's eyes and a boar's ears, which eats people; the 诸怀之水 rises on it and runs west, and
// in it is the fish 鮨, a fish's body with a dog's head.
declare(strict_types=1);

return [
    'id' => 'beiyue',
    'name' => '北岳之山',
    'range' => 8,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T~~~~~~~...........T',
        'T~~~~~~~...........T',
        'T..................T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['yufa', 0], 'right' => ['hunxi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '北岳之山 —— 多枳棘刚木；诸怀之水出焉，而西流注于嚻水'],
        ]],
    ],
    // The 诸怀之水: 鮨鱼.
    'water' => ['rate' => 2, 'slots' => [[20, 'yiyu'], [15, 'yiyu'], [20, 'yiyu']]],
    // The tall grass: 诸怀.
    'wild' => ['rate' => 10, 'slots' => [[23, 'zhuhuai'], [22, 'zhuhuai'], [22, 'zhuhuai'], [24, 'zhuhuai'], [24, 'zhuhuai'], [26, 'zhuhuai'], [26, 'zhuhuai']]],
];
