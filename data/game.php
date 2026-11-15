<?php
// Where a new game starts; where the player wakes after a battle is lost, with what is said then; and what is asked
// at the start when a game is saved already; and what lets water be crossed.
declare(strict_types=1);

return [
    'start' => ['map' => 'zhaoyao', 'x' => 9, 'y' => 8, 'dir' => 'down'],
    // The flag that lets a beast of water carry the player over water: the one the fourth range's god sets when its
    // trial is passed, as the reference lets water be crossed after its fourth gym (docs/numbers.md 13).
    'float' => 'passedRange4',
    'resume' => [
        ['ask', '上次的旅程还留着记录。', [['继续旅程', 'continue'], ['重新开始', 'new']]],
        ['label', 'continue'],
        ['load'],
        ['label', 'new'],
    ],
    'rest' => ['map' => 'zhaoyao_hut', 'x' => 3, 'y' => 3, 'dir' => 'up', 'script' => [
        ['say', '你在巫祝的小屋里醒来。异兽们已经恢复了精神。'],
    ]],
];
