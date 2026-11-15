<?php
// 狂山, three hundred and eighty li north of 尔是之山. The classic: no grass or trees; snow in winter and summer; the
// 狂水 rises on it and runs west into the 浮水, with fine jade in it. It names no beast. The snow is not drawn.
declare(strict_types=1);

return [
    'id' => 'kuangshan',
    'name' => '狂山',
    'range' => 9,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^------------------^',
        '^------------^^^---^',
        '^------------^^^---^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^~~~~~~~-----------^',
        '^~~~~~~~-----------^',
        '^------------------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['ershi', 0], 'right' => ['zhuyu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '狂山 —— 无草木；是山也，冬夏有雪'],
        ]],
    ],
];
