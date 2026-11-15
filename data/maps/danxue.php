<?php
// 丹穴之山, five hundred li east of 祷过之山. The classic: on it much gold and jade; the 丹水 rises on it and runs south
// into the 渤海; the bird 凤皇 lives there, which drinks and eats as it will, sings and dances by itself, and when it is
// seen the world is at peace. 凤皇 is not in the grass: see docs/todo.md.
declare(strict_types=1);

return [
    'id' => 'danxue',
    'name' => '丹穴之山',
    'range' => 3,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^....TT....^^^^^^',
        '^......TTTT.......^^',
        '^.......TT.........^',
        '^..................^',
        '^..................^',
        '====================',
        '^........~~........^',
        '^..T.....~~~....T..^',
        '^........~~~.......^',
        '^.......~~~~~......^',
        '^......~~~~~~~.....^',
        '^^^^^^^~~~~~~~^^^^^^',
    ],
    'exits' => ['left' => ['daoguo', 0], 'right' => ['fashuang', 0]],
    'signs' => [
        [11, 6, [
            ['say', '丹穴之山 —— 丹水出焉，南流注于渤海；有鸟焉，见则天下安宁'],
        ]],
    ],
];
