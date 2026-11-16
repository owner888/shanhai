<?php
// 乐马之山, twenty li southeast. The classic: a beast lives there like a hedgehog, red as cinnabar fire, at whose sight a
// state has great plague. Its name is a rare character, written here 戾.
declare(strict_types=1);

return [
    'id' => 'lema',
    'name' => '乐马之山',
    'range' => 25,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^.......,,,,,......^',
        '^.......,,,,,^^....^',
        '^.......,,,,,^^....^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['bishansy', 0], 'right' => ['zhenshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '乐马之山 —— 有兽焉，其状如彙，赤如丹火，见则其国大疫'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[46, 'liwei'], [47, 'liwei'], [47, 'liwei'], [49, 'liwei'], [48, 'liwei'], [50, 'liwei'], [50, 'liwei']]],
];
