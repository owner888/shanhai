<?php
// 洞庭之山, a hundred and twenty li southeast, the hill the range is named for. The classic: gold on it, silver and iron
// below; 柤, pear, orange and 櫾; the two daughters of the 帝 live there and wander the deeps of the 江; the winds of the 澧
// and 沅 meet over the deep of the 潇湘, between the nine rivers, and they come and go in whirlwind and storm; many strange
// gods, like men with snakes on them and a snake in each hand; many strange birds.
declare(strict_types=1);

return [
    'id' => 'dongting',
    'name' => '洞庭之山',
    'range' => 26,
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
        'T...........~~~~~~~T',
        'T...TT......~~~~~~~T',
        'T...TT.............T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['fufu', 0], 'right' => ['baoshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '洞庭之山 —— 帝之二女居之，是常游于江渊；是多怪神，状如人而载蛇，左右手操蛇；多怪鸟'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[39, 'guainiao'], [40, 'guainiao'], [40, 'guainiao'], [42, 'guainiao'], [41, 'guainiao'], [43, 'guainiao'], [43, 'guainiao']]],
];
