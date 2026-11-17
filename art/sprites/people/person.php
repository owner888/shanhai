<?php
// A person of the classic's world, drawn as HD-2D's pixel sprites (docs/art.md): a robe crossed at the breast,
// wide sleeves, a sash; hair bound in a knot with a pin. The robe is the color of who they are (Stage::LOOKS).
// Three ways it faces, each standing and in the two steps of a walk; the right is the left turned over (Sprites).
// 24 by 44 pixels, 32 to a tile. The outline is drawn around by Sprites, not written here.
declare(strict_types=1);

/** A row and itself turned over: who faces down or up is the same on both sides. */
$whole = fn(array $halves) => array_map(fn(string $h) => $h . strrev($h), $halves);
/** The rows of a step the other foot forward. */
$other = fn(array $rows) => array_map(strrev(...), $rows);

// The knot and its pin, the same from the front and the back.
$knot = [
    '............',
    '..........hh',
    '........gghH',
    '..........hh',
];
// The robe below the neck, front and back but for the collar, the hands and the sash.
$robe = fn(string $collar, string $hands) => [
    '......rrr' . $collar . $collar[0],
    '.....Lrrrr' . $collar,
    '....Lrrrrrr' . $collar[1],
    '...LLRrrrrr' . $collar[1],
    '...LrRrrrrrr',
    '...LrRrrrrrr',
    '...LrRbbbbbb',
    '...LrRbbbbbb',
    '...LrRrrrrrr',
    '...rrRrrrrrr',
    '...rrRrrrrrr',
    '...' . $hands . 'Rrrrrrr',
    '....' . $hands[1] . 'Rrrrrrr',
    '.....Rrrrrrr',
    '.....Rrrrrrr',
    '.....Rrrrrrr',
    '.....Rrrrrrr',
    '....RRrrrrrr',
    '....Rrrrrrrr',
    '....RRRRRRRR',
];
// Below the robe, front and back: the legs and the shoes, standing and with the left foot forward.
$legs = [
    [
        '........pp....pp........',
        '........pp....pp........',
        '........pp....pp........',
        '........pp....pp........',
        '.......fff....fff.......',
        '.......fff....fff.......',
    ],
    [
        '........pp....pp........',
        '........pp....pp........',
        '........pp....fff.......',
        '........pp....fff.......',
        '.......fff..............',
        '.......fff..............',
    ],
];

$down = [...$whole([
    ...$knot,
    '......hhhhhh',
    '.....hhhHHhh',
    '....hhhHhhhh',
    '....hhhhhhhh',
    '....hhhhhhhh',
    '....hhhhshhh',
    '....hhsssshs',
    '....hsssssss',
    '....hssseess',
    '....hssseess',
    '....hSksssss',
    '.....Ssssssm',
    '......SSssss',
    '..........Ss',
    ...$robe('cc', 'ss'),
])];
$up = [...$whole([
    ...$knot,
    '......hhhhhh',
    '.....hhhHHhh',
    '....hhhHhhhh',
    '....hhhhhhhh',
    '....hhhhhhhh',
    '....hhHhhhhh',
    '....hhhhhhhh',
    '....hhhhhhhh',
    '....hhhhhhhh',
    '....hhhhhhhh',
    '....hhhhhhhh',
    '.....hhhhhhh',
    '......hhhhhh',
    '..........SS',
    ...$robe('rr', 'rr'),
])];
$left = [
    '........................',
    '.............hh.........',
    '...........gghHgg.......',
    '.............hh.........',
    '.........hhhhhhh........',
    '........hhhhHHhhh.......',
    '.......hhhhHhhhhhh......',
    '......hhhhhhhhhhhhh.....',
    '......hhhhhhhhhhhhh.....',
    '.....hhhhhhhhhhhhhh.....',
    '.....hssshhhhhhhhhh.....',
    '.....sssssshhhhhhhh.....',
    '.....sesssssShhhhhh.....',
    '....ssesskssShhhhh......',
    '.....ssssssSShhhh.......',
    '......msssssShhh........',
    '.......SSsssshh.........',
    '..........sS............',
    '.........ccrrrr.........',
    '........ccrrrrrL........',
    '........crrRRRrL........',
    '........rrRrrrRr........',
    '........rrRrrrRr........',
    '........rrRrrrRr........',
    '........bbRrrrRb........',
    '........bbRrrrRb........',
    '........rrRrrrRr........',
    '........rrRrrrRr........',
    '........rrRRRRRr........',
    '........rrrssrrr........',
    '........rrrrrrrr........',
    '........rrrrrrrr........',
    '........rrrrrrrr........',
    '........rrrrrrrr........',
    '.......rrrrrrrrrr.......',
    '.......rrrrrrrrrr.......',
    '.......rrrrrrrrrr.......',
    '.......RRRRRRRRRR.......',
];
$side = [
    [
        '..........pppp..........',
        '..........pppp..........',
        '..........pppp..........',
        '..........pppp..........',
        '.........fffff..........',
        '.........fffff..........',
    ],
    [
        '.........pp..pp.........',
        '........pp....pp........',
        '........pp....pp........',
        '.......pp......pp.......',
        '......fff......fff......',
        '......fff.......ff......',
    ],
    [
        '..........pppp..........',
        '..........pppp..........',
        '..........ppp...........',
        '.........pppff..........',
        '.........fff............',
        '.........fff............',
    ],
];

return [
    'size' => [24, 44],
    'colors' => [
        'r' => null,      // the robe: given by the actor's look
        's' => '#f0c8a0', // skin
        'S' => '#d49a7a', // skin in shade
        'k' => '#eaa08a', // cheeks
        'm' => '#b8705c', // mouth
        'h' => '#2a2026', // hair
        'H' => '#4c3c48', // the light on the hair
        'g' => '#d8b040', // the hairpin
        'e' => '#1a1418', // eyes
        'c' => '#f2eee2', // the robe's edge
        'b' => '#7a4e2a', // the sash
        'p' => '#3b3a46', // trousers
        'f' => '#2e2420', // shoes
    ],
    // colors made from another: the robe in shade, and where the light falls on it
    'shades' => ['R' => ['r', 0.72], 'L' => ['r', 1.18]],
    'outline' => '#1c1418',
    'views' => [
        'down' => [[...$down, ...$legs[0]], [...$down, ...$legs[1]], [...$down, ...$other($legs[1])]],
        'up' => [[...$up, ...$legs[0]], [...$up, ...$legs[1]], [...$up, ...$other($legs[1])]],
        'left' => [[...$left, ...$side[0]], [...$left, ...$side[1]], [...$left, ...$side[2]]],
    ],
];
