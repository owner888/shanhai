<?php
// 高前之山, fifty li southeast. The classic: a water on it, very cold and clear, the drink of 帝台, which keeps the heart from
// pain; gold on it, ochre below. It names no beast.
declare(strict_types=1);

return [
    'id' => 'gaoqian',
    'name' => '高前之山',
    'range' => 25,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^..................^',
        '^.......~~~........^',
        '^.......~~~........^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['jishansy', 0], 'right' => ['youxi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '高前之山 —— 其上有水焉，甚寒而清，帝台之浆也，饮之者不心痛'],
        ]],
    ],
];
