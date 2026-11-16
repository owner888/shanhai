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
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 鹿、虎、麋、鹿 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[8, 'yelu'], [10, 'hu'], [12, 'mi'], [12, 'yelu'], [10, 'jingjing'], [14, 'jingjing'], [14, 'jingjing']]],
];
