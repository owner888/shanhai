<?php
// 高梁之山, three hundred li east. The classic: clay on it, whetstone below, 桃枝 and 钩端; a herb like 葵 with red flowers that
// makes a horse run. It names no beast.
declare(strict_types=1);

return [
    'id' => 'gaoliang',
    'name' => '高梁之山',
    'range' => 23,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T...^^^............T',
        'T...^^^............T',
        'T..................T',
        'T..............TT..T',
        'T..................T',
        '====================',
        'T..................T',
        'T..................T',
        'T...........^^^....T',
        'T...........^^^....T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['jushan', 0], 'right' => ['sheshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '高梁之山 —— 其上多垩，其下多砥砺，其木多桃枝钩端'],
        ]],
    ],
];
