<?php
// 小华之山, eighty li west of 太华之山. The classic: its trees are thorn and 杞; its beasts are mostly 㸲牛; on its north
// side much 磬石, on its south much 㻬琈 jade; its birds are mostly 赤鷩, which keep fire off; the herb 萆荔 grows on
// its stones.
declare(strict_types=1);

return [
    'id' => 'xiaohua',
    'name' => '小华之山',
    'range' => 4,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^..^^^....^^^..^^^',
        '^..,,,,,.TT.,,,,,..^',
        '^..,,,,,.TT.,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^..T....,,,,....T..^',
        '^.......,,,,.......^',
        '^..T....,,,,....T..^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['fuyu4', 0], 'right' => ['taihua', 0]],
    'signs' => [
        [9, 6, [
            ['say', '小华之山 —— 其木多荆杞，其兽多㸲牛，其鸟多赤鷩'],
        ]],
    ],
    // The tall grass: 㸲牛 and 赤鷩.
    'wild' => ['rate' => 10, 'slots' => [[4, 'zuoniu'], [4, 'chibi'], [5, 'zuoniu'], [5, 'chibi'], [5, 'zuoniu'], [6, 'chibi'], [6, 'zuoniu']]],
];
