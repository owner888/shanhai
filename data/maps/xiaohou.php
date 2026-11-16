<?php
// 小侯之山, a hundred and eighty li east. The classic: the 明漳之水 rises on it and runs south into 黄泽; the bird 鸪习 lives there,
// a crow with white markings.
declare(strict_types=1);

return [
    'id' => 'xiaohou',
    'name' => '小侯之山',
    'range' => 10,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^^^^^^^^^~~^^^^^^^^^',
    ],
    'exits' => ['left' => ['pengpi', 0], 'right' => ['taitou', 0]],
    'signs' => [
        [12, 6, [
            ['say', '小侯之山 —— 明漳之水出焉，南流注于黄泽'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the three areas before the reference's tenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[13, 'guxi'], [13, 'guxi'], [15, 'guxi'], [12, 'guxi'], [14, 'guxi'], [14, 'guxi'], [14, 'guxi']]],
];
