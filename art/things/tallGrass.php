<?php
// A tile of tall grass: blades of two greens, light at their tips, of several heights, some bent, over the whole tile;
// where each stands is from a hash of its number, so the tile is the same each time.
declare(strict_types=1);

$boxes = [];
for ($i = 0; $i < 40; $i++) {
    $h = crc32("blade $i");
    [$x, $z, $tall, $color, $bent] = [$h % 30 - 15, ($h >> 5) % 30 - 15, 6 + ($h >> 10) % 11, ($h >> 14) & 1 ? 'a' : 'b', ($h >> 15) & 1];
    $boxes[] = ['body', $color, [$x, 0, $z], [$x, $tall - 4, $z]];
    $boxes[] = ['body', $color, [$x + $bent, $tall - 3, $z], [$x + $bent, $tall - 3, $z]];
    $boxes[] = ['body', 'c', [$x + $bent, $tall - 2, $z], [$x + $bent, $tall - 1, $z]];
}

return [
    'colors' => ['a' => '#3f8a4c', 'b' => '#5fae5e', 'c' => '#8fcb7e'],
    'parts' => ['body' => []],
    'boxes' => $boxes,
];
