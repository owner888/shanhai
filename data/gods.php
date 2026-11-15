<?php
// The gods that keep the mountains: met in a trial, never bound (docs/rules.md). A god fights as a beast does, so an
// entry has what a beast's has (data/beasts.php): its elements, base stats, base experience, growth; and its moves,
// which it knows from the first.
//   name     as the classic has it, or, where it gives none, by its range
//   range    the range it keeps
//   looks    its shape, in the classic's words restated
//   why      why these elements
//   from     the classic's line
declare(strict_types=1);

return [
    'queshan' => [
        'name' => '鹊山之神', 'range' => 1,
        'looks' => '鸟身而龙首。',
        'elements' => ['fire', 'wood'],
        'why' => '南山经属火；鸟身，鸟在书里归木。',
        'stats' => ['hp' => 85, 'atk' => 80, 'def' => 80, 'spa' => 105, 'spd' => 90, 'spe' => 95],
        'exp' => 205, 'growth' => 'slow',
        'moves' => ['bite', 'flame', 'cassia', 'sleepless'],
        'from' => '凡鹊山之首，自招摇之山以至箕尾之山，凡十山，二千九百五十里。其神状皆鸟身而龙首。',
    ],
];
