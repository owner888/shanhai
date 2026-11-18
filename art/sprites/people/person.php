<?php
// A person of the classic's world, drawn as HD-2D's pixel sprites (docs/art.md): a robe crossed at the breast,
// sleeves to the wrist, a sash, trousers below; hair bound in a small knot with a pin. Proportioned as HD-2D's
// people are, not as a doll: the head a quarter of the height, the legs showing below the robe. The robe is the
// color of who they are (Stage::LOOKS). Three ways it faces, each standing and in the two steps of a walk; the right
// is the left turned over (Sprites). 24 by 44 pixels, 32 to a tile. The outline and the shading across the body are
// drawn by Sprites, not written here.
declare(strict_types=1);

/** A row and itself turned over: who faces down or up is the same on both sides. */
$whole = fn(array $halves) => array_map(fn(string $h) => $h . strrev($h), $halves);
/** The rows of a step the other foot forward. */
$other = fn(array $rows) => array_map(strrev(...), $rows);

// The robe below the neck, front and back but for the collar and the hands.
$robe = fn(string $collar, string $hands) => [
    '.......rrr' . $collar,
    '......rrrrr' . $collar[1],
    '.....Lrrrrrr',
    '.....LrRrrrr',
    '.....LrRrrrr',
    '.....LrRrrrr',
    '.....LrRrrrr',
    '.....LrRbbbb',
    '.....LrRbbbb',
    '.....rrRrrrr',
    '.....rrRrrrr',
    '.....' . $hands . 'Rrrrr',
    '......' . $hands[1] . 'Rrrrr',
    '.......Rrrrr',
    '.......Rrrrr',
    '.......Rrrrr',
    '......RRrrrr',
    '......Rrrrrr',
    '......Rrrrrr',
    '.....RRrrrrr',
    '.....RRRRRRR',
];
// Below the robe, front and back: the legs and the shoes, standing and with the left foot forward.
$legs = [
    [
        '........ppp..ppp........',
        '........ppp..ppp........',
        '........ppp..ppp........',
        '........ppp..ppp........',
        '........ppp..ppp........',
        '........ppp..ppp........',
        '........ppp..ppp........',
        '.......ffff..ffff.......',
        '.......ffff..ffff.......',
    ],
    [
        '........ppp..ppp........',
        '........ppp..ppp........',
        '........ppp..ppp........',
        '........ppp..ppp........',
        '........ppp..ffff.......',
        '........ppp..ffff.......',
        '........ppp.............',
        '.......ffff.............',
        '.......ffff.............',
    ],
];

$down = $whole([
    '............',
    '..........hh',
    '.........ghh',
    '........hhhh',
    '.......hhHhh',
    '.......hhhhh',
    '.......hhhss',
    '.......hssss',
    '.......hsess',
    '.......hsess',
    '.......hSkss',
    '........SSsm',
    '..........SS',
    '..........ss',
    ...$robe('cc', 'ss'),
]);
$up = $whole([
    '............',
    '..........hh',
    '.........ghh',
    '........hhhh',
    '.......hhHhh',
    '.......hhhhh',
    '.......hhhhh',
    '.......hhHhh',
    '.......hhhhh',
    '.......hhhhh',
    '.......hhhhh',
    '........hhhh',
    '..........hh',
    '..........SS',
    ...$robe('rr', 'rr'),
]);
$left = [
    '........................',
    '............hh..........',
    '...........ghhg.........',
    '.........hhhhhh.........',
    '........hhhHhhhh........',
    '........hhhhhhhhh.......',
    '.......hhhhhhhhhh.......',
    '.......sshhhhhhhh.......',
    '......ssssshhhhhh.......',
    '......sesskShhhhh.......',
    '......sessssShhh........',
    '.......mssssShh.........',
    '........SSssh...........',
    '..........ss............',
    '.........ccrrrr.........',
    '........ccrrrrrr........',
    '........crrrrrrr........',
    '........rrRRRrrr........',
    '........rrRrrRrr........',
    '........rrRrrRrr........',
    '........rrRrrRrr........',
    '........bbRrrRbb........',
    '........bbRrrRbb........',
    '........rrRrrRrr........',
    '........rrRRRRrr........',
    '........rrrssrrr........',
    '........rrrrrrrr........',
    '........rrrrrrrr........',
    '........rrrrrrrr........',
    '........rrrrrrrr........',
    '........rrrrrrrr........',
    '........rrrrrrrr........',
    '.......rrrrrrrrrr.......',
    '.......rrrrrrrrrr.......',
    '.......RRRRRRRRRR.......',
];
$side = [
    [
        '..........ppp...........',
        '..........ppp...........',
        '..........ppp...........',
        '..........ppp...........',
        '..........ppp...........',
        '..........ppp...........',
        '..........ppp...........',
        '.........ffff...........',
        '.........ffff...........',
    ],
    [
        '..........pppp..........',
        '.........pp..pp.........',
        '........pp....pp........',
        '........pp....pp........',
        '.......pp......pp.......',
        '.......pp......pp.......',
        '......pp........pp......',
        '.....fff.........ff.....',
        '.....fff........fff.....',
    ],
    [
        '..........ppp...........',
        '..........pppp..........',
        '.........pp.pp..........',
        '.........pp..pp.........',
        '........pp...pp.........',
        '........pp...pp.........',
        '........pp....pp........',
        '.......fff....fff.......',
        '.......fff.....ff.......',
    ],
];

return [
    'size' => [24, 44],
    'colors' => [
        'r' => null,      // the robe: given by the actor's look
        's' => '#e8c0a0', // skin
        'S' => '#c8987c', // skin in shade
        'k' => '#e0a08c', // cheeks
        'm' => '#a8705c', // mouth
        'h' => '#2a2026', // hair
        'H' => '#4c3c48', // the light on the hair
        'g' => '#d0a848', // the hairpin
        'e' => '#1a1418', // eyes
        'c' => '#ece6d6', // the robe's edge
        'b' => '#6e4a2c', // the sash
        'p' => '#3a3842', // trousers
        'f' => '#2a221e', // shoes
    ],
    // colors made from another: the robe in shade, and where the light falls on it
    'shades' => ['R' => ['r', 0.72], 'L' => ['r', 1.15]],
    'outline' => '#1c1418',
    // drawn flat, shaded round by Sprites::round(): lit from the left, in shade on the right
    'round' => true,
    'views' => [
        'down' => [[...$down, ...$legs[0]], [...$down, ...$legs[1]], [...$down, ...$other($legs[1])]],
        'up' => [[...$up, ...$legs[0]], [...$up, ...$legs[1]], [...$up, ...$other($legs[1])]],
        'left' => [[...$left, ...$side[0]], [...$left, ...$side[1]], [...$left, ...$side[2]]],
    ],
];
