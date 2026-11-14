<?php
// The five elements (五行), as docs/elements.md settles them.
//   overcomes  the element this one overcomes (相克): its moves do double against it
//   feeds      the element this one feeds (相生): its moves do half against it
//   category   'physical' (attack against defense) or 'special' (special attack against special defense): an element's, not a move's
//   region     the chapter of the classic whose land is this element's
declare(strict_types=1);

return [
    'wood'  => ['name' => '木', 'overcomes' => 'earth', 'feeds' => 'fire',  'category' => 'special',  'region' => '东山经'],
    'fire'  => ['name' => '火', 'overcomes' => 'metal', 'feeds' => 'earth', 'category' => 'special',  'region' => '南山经'],
    'earth' => ['name' => '土', 'overcomes' => 'water', 'feeds' => 'metal', 'category' => 'physical', 'region' => '中山经'],
    'metal' => ['name' => '金', 'overcomes' => 'wood',  'feeds' => 'water', 'category' => 'physical', 'region' => '西山经'],
    'water' => ['name' => '水', 'overcomes' => 'fire',  'feeds' => 'wood',  'category' => 'special',  'region' => '北山经'],
];
