<?php
// 会稽之山, five hundred li east of 成山. The classic: four-sided; on it much gold and jade, below it much 砆石; the
// 勺水 rises on it and runs south into the 湨. It names no beast.
declare(strict_types=1);

return [
    'id' => 'kuaiji',
    'name' => '会稽之山',
    'range' => 2,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^.....^^^^^^^^.....^',
        '^..T..^^^^^^^^..T..^',
        '^.....^^^^^^^^.....^',
        '^.....^^^^^^^^.....^',
        '^..................^',
        '====================',
        '^..................^',
        '^........~~........^',
        '^.^^^....~~....^^^.^',
        '^.^^^....~~....^^^.^',
        '^........~~........^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['chengshan', 0], 'right' => ['yishan', 0]],
    'signs' => [
        [4, 6, [
            ['say', '会稽之山 —— 四方，上多金玉，下多砆石'],
        ]],
    ],
];
