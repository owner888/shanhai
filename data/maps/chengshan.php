<?php
// 成山, five hundred li east of 浮玉之山. The classic: four-sided, with three terraces; on it much gold and jade,
// below it much 青雘; a river rises on it and runs south into the 虖勺, with much gold in it. It names no beast.
declare(strict_types=1);

return [
    'id' => 'chengshan',
    'name' => '成山',
    'range' => 2,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^....^^^^^^^^^^....^',
        '^....^^^^^^^^^^....^',
        '^....^^^^^^^^^^....^',
        '^....^^^^^^^^^^....^',
        '^....^^......^^....^',
        '^..................^',
        '====================',
        '^..................^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['fuyu', 0], 'right' => ['kuaiji', 0]],
    'signs' => [
        [4, 6, [
            ['say', '成山 —— 四方而三坛'],
        ]],
    ],
];
