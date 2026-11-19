<?php
// 鹿蜀, of 杻阳之山, drawn from its entry's looks (carried below, which the tests hold to data/beasts.php): a horse;
// its head white; striped as a tiger, dark bands over a tawny hide (the text gives no coat but the stripes); its tail red.
// Seen from the side, facing left, as a beast stands in battle; facing right it is turned over (Sprites). Its lumps
// shaded as balls lit from the top left (Sprites::blob), the far legs darker than the near. 64 by 48 pixels.
declare(strict_types=1);

use Shanhai\Render\Sprites;

$rows = Sprites::blank(64, 48);
$line = fn(array &$rows, array $from, array $to, float $r, string $ramp) => Sprites::limb($rows, $from, $to, $r, $r, $ramp);
// the far legs, in shade, behind all
$line($rows, [24, 28], [22, 44], 1.6, 'aabb');
$line($rows, [44, 28], [46, 44], 1.6, 'aabb');
// the tail, red, sweeping back and down
$line($rows, [50, 19], [57, 24], 2.2, 'RSTU');
$line($rows, [57, 24], [58, 34], 2.4, 'RSTU');
// the body, the neck and the head
Sprites::blob($rows, 36, 23, 15, 8, 'abcd');
$line($rows, [24, 20], [16, 10], 4.2, 'abcd');
Sprites::blob($rows, 12, 9, 6, 4.5, 'pqrs');   // the head, white
Sprites::blob($rows, 7, 12, 4, 3, 'pqrs');     // the muzzle
// the tiger's stripes, on the hide alone: bands slanting down and back
foreach ($rows as $y => $row) {
    for ($x = 0; $x < 64; $x++) {
        if (str_contains('abcd', $row[$x]) && $x > 18 && ($x + (int)round($y * 0.5)) % 7 < 2) {
            $rows[$y][$x] = 'k';
        }
    }
}
// the near legs, over the body, and the hooves
$line($rows, [28, 28], [27, 44], 1.8, 'bccd');
$line($rows, [42, 28], [43, 44], 1.8, 'bccd');
foreach ([22, 27, 43, 46] as $x) {
    foreach ([45, 46] as $y) {
        for ($dx = -1; $dx <= 1; $dx++) {
            $rows[$y][$x + $dx] = 'h';
        }
    }
}
// the mane down the neck, the ear, the eye, the nostril
foreach ([[17, 5], [18, 6], [19, 8], [20, 10], [21, 12], [22, 14], [23, 16]] as [$x, $y]) {
    $rows[$y][$x] = 'm';
    $rows[$y][$x + 1] = 'm';
}
foreach ([[14, 2], [14, 3], [15, 3], [15, 4]] as [$x, $y]) {
    $rows[$y][$x] = 'q';
}
$rows[8][10] = 'e';
$rows[12][4] = 'e';

return [
    'looks' => '形如马，白首，身有虎纹，赤尾。',
    'size' => [64, 48],
    'colors' => [
        'a' => '#5e3c1e', // the hide in shade
        'b' => '#9a6a34',
        'c' => '#c88c48',
        'd' => '#e8b468', // the hide lit
        'k' => '#2a1c12', // the stripes
        'p' => '#8e8a92', // the white head in shade
        'q' => '#b8b4bc',
        'r' => '#dcd8e0',
        's' => '#fbfaf8', // lit
        'R' => '#5e1612', // the tail in shade
        'S' => '#9e2a20',
        'T' => '#d0442e',
        'U' => '#f07a52', // lit
        'm' => '#3a2a1e', // the mane
        'h' => '#2a2220', // hooves
        'e' => '#16100c', // the eye, the nostril
    ],
    'shades' => [],
    'outline' => '#1a120c',
    'views' => ['left' => [$rows]],
];
