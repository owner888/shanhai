<?php
// 騩山, a hundred and ninety li west of 三危之山: another of the name (the first range's is `guishan4`). The classic: on
// it much jade and no stone; the spirit 耆童 lives there, whose voice is like bells and chimes; below it snakes lie in
// heaps.
declare(strict_types=1);

return [
    'id' => 'guishanxi',
    'name' => '騩山',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^.......^^^........^',
        '^.......^^^........^',
        '^..................^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^..,,,,,,...,,,,,..^',
        '^..,,,,,,...,,,,,..^',
        '^..,,,,,,...,,,,,..^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['tianshan', 0], 'right' => ['sanwei', 0]],
    'signs' => [
        [9, 6, [
            ['say', '騩山 —— 神耆童居之，其音常如钟磬；其下多积蛇'],
        ]],
    ],
    // The tall grass, below: 积蛇.
    'wild' => ['rate' => 10, 'slots' => [[16, 'jishe'], [16, 'jishe'], [16, 'jishe'], [16, 'jishe'], [13, 'jishe'], [13, 'jishe'], [13, 'jishe']]],
];
