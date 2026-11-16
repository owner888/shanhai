<?php
// 踇隅之山, nine hundred li south by water. The classic: grass and trees, gold and jade, ochre; the beast 精精 lives there, an
// ox with a horse's tail.
declare(strict_types=1);

return [
    'id' => 'muyu',
    'name' => '踇隅之山',
    'range' => 13,
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
    'exits' => ['left' => ['qizhong', 0], 'right' => ['wugao', 0]],
    'signs' => [
        [9, 6, [
            ['say', '踇隅之山 —— 其上多草木，多金玉，多赭'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's thirteenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[8, 'jingjing'], [10, 'jingjing'], [12, 'jingjing'], [12, 'jingjing'], [10, 'jingjing'], [14, 'jingjing'], [14, 'jingjing']]],
];
