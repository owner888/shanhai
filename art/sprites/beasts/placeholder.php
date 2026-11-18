<?php
// A beast that has no sprite yet (docs/art.md says how many do): a round creature of its first element's color
// (Arena::ELEMENTS, given when drawn as 'c'), a head with an eye, four short legs, a tail; facing left. 40 by 32 pixels.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(40, 32);
foreach ([[14, 24], [26, 24]] as [$x, $y]) { // the far legs
    for ($i = 0; $i < 6; $i++) { $rows[$y + $i][$x + 2] = 'a'; $rows[$y + $i][$x + 3] = 'a'; }
}
Sprites::blob($rows, 33, 14, 5, 3, 'abcd');   // the tail
Sprites::blob($rows, 21, 18, 13, 9, 'abcd');  // the body
Sprites::blob($rows, 9, 11, 7, 6, 'abcd');    // the head
foreach ([[11, 24], [23, 24]] as [$x, $y]) { // the near legs
    for ($i = 0; $i < 7; $i++) { $rows[$y + $i][$x] = 'b'; $rows[$y + $i][$x + 1] = 'b'; }
}
$rows[9][6] = 'e';
$rows[10][6] = 'e';

return [
    'size' => [40, 32],
    'colors' => ['c' => null, 'e' => '#16100c'],
    // the element's color in its shades, made from it
    'shades' => ['a' => ['c', 0.5], 'b' => ['c', 0.75], 'd' => ['c', 1.25]],
    'outline' => '#1a120c',
    'views' => ['left' => [$rows]],
];
