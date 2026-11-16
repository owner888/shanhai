<?php
// 太华之山, sixty li west of 松果之山: the range's 冢, the mountain its rites are greatest at. The classic: cut sheer and
// square, five thousand 仞 high and ten li across; no bird or beast lives on it; there is a snake, 肥蟡, at whose
// sight there is great drought. So it is a wall of rock with the road at its foot, and 肥蟡 is not in any grass
// (docs/todo.md).
declare(strict_types=1);

return [
    'id' => 'taihua',
    'name' => '太华之山',
    'range' => 4,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
        '^--^^^^^^^^^^^^^^--^',
        '^--^^^^^^^^^^^^^^--^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^------------------^',
        '^---^^-------^^----^',
        '^---^^-------^^----^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['xiaohua', 0], 'right' => ['songguo', 0]],
    'signs' => [
        [9, 6, [
            ['say', '太华之山 —— 削成而四方，其高五千仞，其广十里，鸟兽莫居'],
        ]],
    ],
];
