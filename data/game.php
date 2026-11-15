<?php
// Where a new game starts, and where the player wakes after a battle is lost, with what is said then.
declare(strict_types=1);

return [
    'start' => ['map' => 'zhaoyao', 'x' => 9, 'y' => 8, 'dir' => 'down'],
    'rest' => ['map' => 'zhaoyao_hut', 'x' => 3, 'y' => 3, 'dir' => 'up', 'script' => [
        ['say', '你在巫祝的小屋里醒来。异兽们已经恢复了精神。'],
    ]],
];
