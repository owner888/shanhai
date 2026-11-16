<?php
// 少室之山, fifty li east; one of the range's three 冢. The classic: a hundred herbs and trees grown round like a granary; a
// tree called 帝休, which takes anger away; jade on it, iron below; the 休水 rises on it and runs north into the 洛, with
// many 䱱鱼 in it, shaped like a long-tailed monkey with long spurs.
declare(strict_types=1);

return [
    'id' => 'shaoshi',
    'name' => '少室之山',
    'range' => 21,
    'rows' => [
        'TTTTTTTTT~~TTTTTTTTT',
        'T........~~........T',
        'T........~~........T',
        'T........~~........T',
        'T........~~........T',
        'T........~~........T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T...TT.............T',
        'T...TT.............T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['banshi', 0], 'right' => ['taishi', 0]],
    'signs' => [
        [12, 6, [
            ['say', '少室之山 —— 百草木成囷；其上多玉，其下多铁；休水出焉，而北流注于洛'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'tiyu'], [15, 'tiyu'], [20, 'tiyu']]],
];
