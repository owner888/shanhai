<?php
// 乾山, four hundred li north (`qianshan` is 钤山, of the west). The classic: no grass or trees; gold and jade on its south
// side, iron on its north, and no water; the beast 豲 lives there, an ox with three feet.
declare(strict_types=1);

return [
    'id' => 'qianshanbei',
    'name' => '乾山',
    'range' => 10,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^--:::::----:::::--^',
        '^--:::::----:::::--^',
        '^--:::::----:::::--^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^-------:::::------^',
        '^-------:::::^^----^',
        '^-------:::::^^----^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['raoshan', 0], 'right' => ['lunshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '乾山 —— 无草木，其阳有金玉，其阴有铁而无水'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the three areas before the reference's tenth gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 鹠、白蛇、橐驼、鹠 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[13, 'liu'], [13, 'baishe'], [15, 'tuotuo'], [12, 'liu'], [14, 'yuan'], [14, 'yuan'], [14, 'yuan']]],
];
