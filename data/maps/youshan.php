<?php
// 泑山, two hundred and ninety li west of 天山. The classic: the spirit 蓐收 lives there; on it much 婴垣 jade, on its
// south side 瑾瑜 jade, on its north 青雄黄; the spirit 红光 has charge of it. It names no beast.
declare(strict_types=1);

return [
    'id' => 'youshan',
    'name' => '泑山',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^---^^^------------^',
        '^---^^^------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^------------------^',
        '^------------^^^---^',
        '^------------^^^---^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['yiwang', 0], 'right' => ['tianshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '泑山 —— 神蓐收居之；神红光之所司也'],
        ]],
    ],
];
