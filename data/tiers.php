<?php
// The five tiers a beast's numbers are built in (docs/numbers.md 11). The bands are measured from the reference
// game's own distribution, not copied from any of its entries; a beast's six stats are ours, inside its tier's band.
//   total   [least, most] the six base stats add up to
//   catch   the catch rates a beast of the tier may have
//   exp     [least, most] its base experience
//   growth  the growth rates it may have (docs/numbers.md 7.2)
declare(strict_types=1);

return [
    1 => ['name' => '常兽', 'total' => [250, 329], 'catch' => [255, 190], 'exp' => [54, 115], 'growth' => ['fast', 'mediumFast']],
    2 => ['name' => '小异兽', 'total' => [330, 419], 'catch' => [120, 90, 75], 'exp' => [61, 183], 'growth' => ['mediumFast', 'mediumSlow']],
    3 => ['name' => '异兽', 'total' => [420, 499], 'catch' => [75, 60, 45], 'exp' => [100, 255], 'growth' => ['mediumFast', 'mediumSlow']],
    4 => ['name' => '凶兽、瑞兽', 'total' => [500, 559], 'catch' => [45], 'exp' => [80, 255], 'growth' => ['mediumSlow', 'slow']],
    5 => ['name' => '神兽', 'total' => [560, 680], 'catch' => [3], 'exp' => [64, 220], 'growth' => ['slow']],
];
