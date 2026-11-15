<?php
// 猨翼之山, three hundred and eighty li east of 堂庭之山. The classic: in it many strange beasts, in its waters many
// strange fish; much white jade, many 蝮虫, many strange snakes, many strange trees; it cannot be climbed. So the
// map is its foot: the road between the cliff and the water.
declare(strict_types=1);

return [
    'id' => 'yuanyi',
    'name' => '猨翼之山 · 山脚',
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^T^^^^^^T^^^^^T^^^',
        '^^..,,^^^..,,^^..,^^',
        '^.,,,,.^.,,,,.^.,,,^',
        'T.,,,....,,,....,,.T',
        'T..................T',
        '====================',
        'T......T.....T.....T',
        'T~~~.......~~~~....T',
        '~~~~~~...~~~~~~~..TT',
        '~~~~~~~~~~~~~~~~~~~~',
        '~~~~~~~~~~~~~~~~~~~~',
        '~~~~~~~~~~~~~~~~~~~~',
    ],
    // West to 堂庭之山, east to 杻阳之山.
    'exits' => ['left' => ['tangting', 0], 'right' => ['niuyang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '猨翼之山 —— 多怪兽、怪蛇、怪木，不可以上'],
        ]],
    ],
    // The tall grass: 蝮虫, the one beast of this mountain the classic names.
    'wild' => ['rate' => 10, 'slots' => [[6, 'fuchong'], [7, 'fuchong'], [8, 'fuchong'], [7, 'fuchong'], [9, 'fuchong'], [8, 'fuchong'], [9, 'fuchong']]],
];
