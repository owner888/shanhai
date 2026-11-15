<?php
// 堂庭之山, three hundred li east of 招摇之山. The classic: many 棪 trees, many white apes, much crystal, much gold.
declare(strict_types=1);

return [
    'id' => 'tangting',
    'name' => '堂庭之山',
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T,,,,..TT....^^....T',
        'T,,,,...T.....^..,,T',
        'T,,,.........TT.,,,T',
        'T....TT......T..,,,T',
        'T....T...........,,T',
        'T..................T',
        '====================',
        'T......T...........T',
        'T..,,..TT....~~~...T',
        'T.,,,,..T...~~~~~..T',
        'T.,,,,......~~~~...T',
        'T..,,....T...~~....T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    // West to 招摇之山, whose road meets the edge a row higher; east to 猨翼之山.
    'exits' => ['left' => ['zhaoyao', -1], 'right' => ['yuanyi', 0]],
    'signs' => [
        [10, 6, [
            ['say', '堂庭之山 —— 多棪木，多白猿'],
        ]],
    ],
    'npcs' => [
        ['gatherer', 11, 9, 'right', 'elder', [
            ['say', '这山里多水玉，溪水里就捞得到，也多黄金。'],
            ['say', '白猿在棪树间荡来荡去，草深的地方常能撞见。'],
        ]],
    ],
    // The tall grass: 白猿, the one beast the classic gives this mountain.
    'wild' => ['rate' => 10, 'slots' => [[4, 'baiyuan'], [5, 'baiyuan'], [6, 'baiyuan'], [5, 'baiyuan'], [7, 'baiyuan'], [6, 'baiyuan'], [7, 'baiyuan']]],
];
