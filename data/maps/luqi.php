<?php
// 卢其之山, three hundred li south. The classic: no grass or trees, much sand and stone; the 沙水 rises on it and runs south
// into the 涔水, and in it are many 鵹鹕, mandarin ducks with a man's feet, at whose sight a state has much earthwork: met
// here on the scree by the water, being of earth (docs/elements.md 6).
declare(strict_types=1);

return [
    'id' => 'luqi',
    'name' => '卢其之山',
    'range' => 12,
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
    'exits' => ['left' => ['gengshan', 0], 'right' => ['gushe', 0]],
    'signs' => [
        [12, 6, [
            ['say', '卢其之山 —— 无草木，多沙石；沙水出焉，南流注于涔水'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's twelfth gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 鹿、虎、麋、鹿 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[23, 'yelu'], [23, 'hu'], [25, 'mi'], [25, 'yelu'], [27, 'lihu'], [27, 'lihu'], [25, 'lihu']]],
];
