<?php
// 洹山, five hundred li north by water and three hundred of flowing sand from 湖灌之山. The classic: on it much gold
// and jade; three mulberries grow on it, with no branches, a hundred 仞 high; trees of every fruit grow on it; below
// it are many strange snakes. It is walked to here, not come to by water (docs/todo.md).
declare(strict_types=1);

return [
    'id' => 'huanshan',
    'name' => '洹山',
    'range' => 9,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..TT....TT....TT..T',
        'T..TT....TT....TT..T',
        'T..TT....TT....TT..T',
        'T..TT....TT....TT..T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T...,,,,,...,,,,,..T',
        'T...,,,,,...,,,,,..T',
        'T...,,,,,...,,,,,..T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['huguan', 0], 'right' => ['dunti', 0]],
    'signs' => [
        [12, 6, [
            ['say', '洹山 —— 其上多金玉；三桑生之，其树皆无枝，其高百仞'],
        ]],
    ],
    // The tall grass, below: 怪蛇.
    'wild' => ['rate' => 10, 'slots' => [[28, 'guaishe'], [28, 'guaishe'], [30, 'guaishe'], [30, 'guaishe'], [32, 'guaishe'], [30, 'guaishe'], [30, 'guaishe']]],
];
