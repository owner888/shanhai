<?php
// 光山, a hundred and thirty li east. The classic: 碧 on it, much water below; the god 计蒙 lives there, with a man's body
// and a dragon's head, who wanders the deeps of the 漳, and comes and goes with whirlwind and storm. It names no beast.
declare(strict_types=1);

return [
    'id' => 'guangshan',
    'name' => '光山',
    'range' => 22,
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
    'exits' => ['left' => ['lugui', 0], 'right' => ['qishanba', 0]],
    'signs' => [
        [9, 6, [
            ['say', '光山 —— 其上多碧，其下多水；神计蒙处之，其状人身而龙首'],
        ]],
    ],
];
