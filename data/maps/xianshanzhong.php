<?php
// 鲜山, three hundred li west (`xianshan` is 咸山, of the north). The classic: gold and jade, no grass or trees; the 鲜水
// rises on it and runs north into the 伊水, and in it are many 鸣蛇, snakes with four wings, which sound like chimes, and at
// whose sight a town has drought: met here on the scree by the water, being of fire (docs/elements.md 6).
declare(strict_types=1);

return [
    'id' => 'xianshanzhong',
    'name' => '鲜山',
    'range' => 16,
    'rows' => [
        '^^^^^^^^^~~^^^^^^^^^',
        '^--------~~--------^',
        '^--:::::-~~-:::::--^',
        '^--:::::-~~-:::::--^',
        '^--:::::-~~-:::::--^',
        '^--------~~--------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^--:::::-----------^',
        '^--:::::-----------^',
        '^--:::::-----------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['haoshanzhong', 0], 'right' => ['yangshanzhong', 0]],
    'signs' => [
        [12, 6, [
            ['say', '鲜山 —— 多金玉，无草木；鲜水出焉，而北流注于伊水'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's sixteenth gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 闾、麋、鹖、闾 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[39, 'lyu'], [40, 'mi'], [40, 'he'], [42, 'lyu'], [41, 'mingshe'], [43, 'mingshe'], [43, 'mingshe']]],
];
