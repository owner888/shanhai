<?php
// 扶猪之山, fifty li west. The classic: 礝 stone on it; the beast 䴦 lives there, a raccoon dog with a man's eyes; the 虢水
// rises on it and runs north into the 洛.
declare(strict_types=1);

return [
    'id' => 'fuzhushan',
    'name' => '扶猪之山',
    'range' => 18,
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
    'exits' => ['left' => ['luti', 0], 'right' => ['lishanzhong', 0]],
    'signs' => [
        [12, 6, [
            ['say', '扶猪之山 —— 其上多礝石；虢水出焉，而北流注于洛'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[31, 'yinmo'], [32, 'yinmo'], [32, 'yinmo'], [34, 'yinmo'], [33, 'yinmo'], [35, 'yinmo'], [35, 'yinmo']]],
];
