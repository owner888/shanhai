<?php
// 景山, three hundred li south (the first of two of the name in this range). The classic: grass, yams and 秦椒; ochre on its
// north side, jade on its south; the bird 酸与 lives there, a snake's shape with four wings and six eyes, at whose sight a
// town is afraid.
declare(strict_types=1);

return [
    'id' => 'jingshanyi',
    'name' => '景山',
    'range' => 10,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^.......,,,,,......^',
        '^.......,,,,,^^....^',
        '^.......,,,,,^^....^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['jiaoshan', 0], 'right' => ['mengmen', 0]],
    'signs' => [
        [9, 6, [
            ['say', '景山 —— 其上多草、藷藇，其草多秦椒；其阴多赭，其阳多玉'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the three areas before the reference's tenth gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 橐驼、鹠、白蛇、橐驼 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[17, 'tuotuo'], [17, 'liu'], [18, 'baishe'], [18, 'tuotuo'], [18, 'suanyu'], [16, 'suanyu'], [16, 'suanyu']]],
];
