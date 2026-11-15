<?php
// 彭毗之山, three hundred li east. The classic: no grass or trees, gold and jade, much water below; the 肥水 rises on it and
// runs south into the 床水, and in it are many of the snake 肥遗: met here on the scree by the water.
declare(strict_types=1);

return [
    'id' => 'pengpi',
    'name' => '彭毗之山',
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
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^^^^^^^^^~~^^^^^^^^^',
    ],
    'exits' => ['left' => ['chongwei', 0], 'right' => ['xiaohou', 0]],
    'signs' => [
        [12, 6, [
            ['say', '彭毗之山 —— 其上无草木，多金玉，其下多水；肥水出焉，而南流注于床水'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the three areas before the reference's tenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[17, 'feiyishe'], [19, 'feiyishe'], [16, 'feiyishe'], [17, 'feiyishe'], [16, 'feiyishe'], [18, 'feiyishe'], [18, 'feiyishe']]],
];
