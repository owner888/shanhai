<?php
// 仑者之山, three hundred and seventy li east of 令丘之山. The classic: on it much gold and jade, below it much 青雘; a
// tree grows there, 白䓘, whose sap is sweet: who eats of it is not hungry, and it takes weariness away. So the party
// is made whole under it.
declare(strict_types=1);

return [
    'id' => 'lunzhe',
    'name' => '仑者之山',
    'range' => 3,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^.........^^^^^^^',
        '^^..............^^^^',
        '^..................^',
        '^.........T........^',
        '^..................^',
        '====================',
        '^..................^',
        '^..................^',
        '^..TT..........TT..^',
        '^..TT..........TT..^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['lingqiu', 0], 'right' => ['yugao', 0]],
    'signs' => [
        [5, 6, [
            ['say', '仑者之山 —— 其上多金玉，其下多青雘'],
        ]],
        [11, 5, [
            ['say', '白䓘 —— 其味如饴，食者不饥，可以释劳'],
            ['rest'],
            ['say', '你和异兽们尝了树上的汁，都恢复了精神。'],
        ]],
    ],
];
