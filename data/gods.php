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
    'xishan' => [
        'name' => '羭山之神', 'range' => 4,
        'looks' => '原文没有写它的形状。',
        'elements' => ['metal'],
        'why' => '西山经属金；原文没有写它的形状，不加副属性。',
        'stats' => ['hp' => 90, 'atk' => 105, 'def' => 90, 'spa' => 70, 'spd' => 80, 'spe' => 75],
        'exp' => 205, 'growth' => 'slow',
        'moves' => ['slumber', 'gore', 'bite', 'rebuke'],
        'from' => '凡西经之首，自钱来之山至于騩山，凡十九山，二千九百五十七里。华山，冢也。羭山，神也。',
    ],
    'xici' => [
        'name' => '西次二经之神', 'range' => 5,
        'looks' => '人面而牛身，四足而一臂，操杖以行，是为飞兽之神。',
        'elements' => ['metal', 'wood'],
        'why' => '西山经属金；飞兽之神，飞的在书里归木。',
        'stats' => ['hp' => 95, 'atk' => 100, 'def' => 95, 'spa' => 65, 'spd' => 80, 'spe' => 75],
        'exp' => 205, 'growth' => 'slow',
        'moves' => ['slumber', 'onehorn', 'southwind', 'fearless'],
        'from' => '凡西次二经之首，自钤山至于莱山，凡十七山，四千一百四十里。其十神者，皆人面而马身。其七神皆人面牛身，四足而一臂，操杖以行，是为飞兽之神。',
    ],
    'xicisan' => [
        'name' => '西次三经之神', 'range' => 6,
        'looks' => '羊身人面。',
        'elements' => ['metal', 'earth'],
        'why' => '西山经属金；人面，人在五虫里是倮虫，倮虫属土。',
        'stats' => ['hp' => 100, 'atk' => 95, 'def' => 110, 'spa' => 60, 'spd' => 80, 'spe' => 65],
        'exp' => 205, 'growth' => 'slow',
        'moves' => ['infantcry', 'drought', 'dust', 'tusk'],
        'from' => '凡西次三经之首，崇吾之山至于翼望之山，凡二十三山，六千七百四十四里。其神状皆羊身人面。',
    ],
    'xicisi' => [
        'name' => '西次四经之神', 'range' => 7,
        'looks' => '原文没有写它的形状。',
        'elements' => ['metal'],
        'why' => '西山经属金；原文没有写它的形状，不加副属性。',
        'stats' => ['hp' => 100, 'atk' => 100, 'def' => 90, 'spa' => 65, 'spd' => 75, 'spe' => 80],
        'exp' => 205, 'growth' => 'slow',
        'moves' => ['lowing', 'spur', 'fearless', 'tusk'],
        'from' => '凡西次四经，自阴山以下，至于崦嵫之山，凡十九山，三千六百八十里。其神祠礼，皆用一白鸡祈，糈以稻米，白菅为席。',
    ],
];
