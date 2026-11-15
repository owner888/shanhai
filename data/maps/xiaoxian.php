<?php
// 小咸之山, two hundred and thirty li north of 潘侯之山. The classic: no grass or trees; snow in winter and summer. It
// names no beast. The snow is not drawn (docs/todo.md).
declare(strict_types=1);

return [
    'id' => 'xiaoxian',
    'name' => '小咸之山',
    'range' => 8,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^---^^^------------^',
        '^---^^^------^^^---^',
        '^------------^^^---^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^------------------^',
        '^-----------^^^----^',
        '^-----------^^^----^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['panhou', 0], 'right' => ['daxian', 0]],
    'signs' => [
        [9, 6, [
            ['say', '小咸之山 —— 无草木，冬夏有雪'],
        ]],
    ],
];
