<?php
// The gods that keep the mountains: met in a trial, never bound (docs/rules.md). A god fights as a beast does, so an
// entry has what a beast's has (data/beasts.php): its elements, base stats, base experience, growth; and its moves,
// which it knows from the first.
//   name     as the classic has it, or, where it gives none, by its range
//   range    the range it keeps
//   looks    its shape, in the classic's words restated
//   why      why these elements
//   from     the classic's line
// How strong a god and its trial are is measured from the reference's gym leaders (docs/numbers.md 14).
declare(strict_types=1);

return [
    'queshan' => [
        'name' => '鹊山之神', 'range' => 1,
        'looks' => '鸟身而龙首。',
        'elements' => ['fire', 'wood'],
        'why' => '南山经属火；鸟身，鸟在书里归木。',
        'stats' => ['hp' => 80, 'atk' => 75, 'def' => 75, 'spa' => 100, 'spd' => 85, 'spe' => 95],
        'exp' => 205, 'growth' => 'slow',
        'moves' => ['spark', 'breeze', 'chant'],
        'from' => '凡鹊山之首，自招摇之山以至箕尾之山，凡十山，二千九百五十里。其神状皆鸟身而龙首。',
    ],
    'nanci2' => [
        'name' => '南次二经之神', 'range' => 2,
        'looks' => '龙身而鸟首。',
        'elements' => ['fire', 'water'],
        'why' => '南山经属火；龙身，龙在书里行雨，归水。',
        'stats' => ['hp' => 85, 'atk' => 80, 'def' => 80, 'spa' => 100, 'spd' => 85, 'spe' => 90],
        'exp' => 205, 'growth' => 'slow',
        'moves' => ['spark', 'splash', 'infantcry'],
        'from' => '凡南次二经之首，自柜山至于漆吴之山，凡十七山，七千二百里。其神状皆龙身而鸟首。',
    ],
    'nanci3' => [
        'name' => '南次三经之神', 'range' => 3,
        'looks' => '龙身而人面。',
        'elements' => ['fire', 'earth'],
        'why' => '南山经属火；人面，人在五虫里是倮虫，倮虫属土。',
        'stats' => ['hp' => 95, 'atk' => 90, 'def' => 100, 'spa' => 75, 'spd' => 75, 'spe' => 75],
        'exp' => 205, 'growth' => 'slow',
        'moves' => ['burrow', 'bite', 'bewilder', 'revive'],
        'from' => '凡南次三经之首，自天虞之山以至南禺之山，凡一十四山，六千五百三十里。其神皆龙身而人面。',
    ],
];
