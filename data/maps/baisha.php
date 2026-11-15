<?php
// 白沙山, three hundred and fifty li north of 狐岐之山. The classic: three hundred li round, all sand; no grass, trees,
// birds or beasts; the 鲔水 comes out on it and goes under below it, with much white jade.
declare(strict_types=1);

return [
    'id' => 'baisha',
    'name' => '白沙山',
    'range' => 9,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^------------------^',
        '^-------~~~--------^',
        '^-------~~~--------^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['huqi', 0], 'right' => ['ershi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '白沙山 —— 广员三百里，尽沙也，无草木鸟兽'],
        ]],
    ],
];
