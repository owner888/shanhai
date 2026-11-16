<?php
// 栒状之山, three hundred li south. The classic: gold and jade on it, 青碧 stone below; the beast 从从 lives there, a dog with
// six feet; the bird 䖪鼠, a fowl with a rat's fur, at whose sight a town has drought; the 𣲵水 rises on it and runs north
// into the 湖水, and in it is the 箴鱼, whose beak is a needle.
declare(strict_types=1);

return [
    'id' => 'xunzhuang',
    'name' => '栒状之山',
    'range' => 11,
    'rows' => [
        '^^^^^^^^^~~^^^^^^^^^',
        '^........~~........^',
        '^..,,,,,.~~.,,,,,..^',
        '^..,,,,,.~~.,,,,,..^',
        '^..,,,,,.~~.,,,,,..^',
        '^........~~........^',
        '^..................^',
        '====================',
        '^..................^',
        '^..,,,,,...........^',
        '^..,,,,,...........^',
        '^..,,,,,...........^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['leishan', 0], 'right' => ['boqi', 0]],
    'signs' => [
        [12, 6, [
            ['say', '栒状之山 —— 其上多金玉，其下多青碧石；𣲵水出焉，而北流注于湖水'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'zhenyu'], [15, 'zhenyu'], [20, 'zhenyu']]],
    // Its wild ground. The range's levels are those of the three areas before the reference's eleventh gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[26, 'congcong'], [27, 'zishu'], [28, 'congcong'], [29, 'zishu'], [29, 'congcong'], [30, 'zishu'], [30, 'congcong']]],
];
