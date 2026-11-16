<?php
// 阳山, three hundred li west (`yangshan` is the 阳山 of the north). The classic: much stone, no grass or trees; the 阳水
// rises on it and runs north into the 伊水, and in it are many 化蛇, with a man's face, a jackal's body and a bird's wings,
// which go like snakes, and at whose sight a town has floods.
declare(strict_types=1);

return [
    'id' => 'yangshanzhong',
    'name' => '阳山',
    'range' => 16,
    'rows' => [
        '^^^^^^^^^~~^^^^^^^^^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['xianshanzhong', 0], 'right' => ['kunwu', 0]],
    'signs' => [
        [12, 6, [
            ['say', '阳山 —— 多石，无草木；阳水出焉，而北流注于伊水'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'huashe'], [15, 'huashe'], [20, 'huashe']]],
];
