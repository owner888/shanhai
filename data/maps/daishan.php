<?php
// 带山, three hundred li north of 求如之山. The classic: on it much jade, below it much 青碧; the beast 䑏疏 lives there,
// a horse with one horn, and the bird 鵸䳜, five-coloured, male and female in one; the 彭水 rises on it and runs west,
// and in it is 儵鱼, three tails, six feet and four heads.
declare(strict_types=1);

return [
    'id' => 'daishan',
    'name' => '带山',
    'range' => 8,
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
        '^~~~~~~~...........^',
        '^~~~~~~~...........^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['qiuru', 0], 'right' => ['qiaoming', 0]],
    'signs' => [
        [9, 6, [
            ['say', '带山 —— 其上多玉，其下多青碧；彭水出焉，而西流注于芘湖之水'],
        ]],
    ],
    // The 彭水: 儵鱼.
    'water' => ['rate' => 2, 'slots' => [[20, 'shuyu'], [15, 'shuyu'], [20, 'shuyu']]],
    // The tall grass: 䑏疏 and 鵸䳜. The range's levels are those of the five areas before the reference's eighth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[23, 'huanshu'], [22, 'qiyu'], [22, 'huanshu'], [24, 'qiyu'], [24, 'huanshu'], [26, 'qiyu'], [26, 'huanshu']]],
];
