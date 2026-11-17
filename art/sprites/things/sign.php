<?php
// A sign: a board on a post, the board's grain and the lines of its writing. 24 by 30 pixels. Never turns.
declare(strict_types=1);

$rows = [
    '........................',
    '.wwwwwwwwwwwwwwwwwwwwww.',
    '.wbbbbbbbbbbbbbbbbbbbbW.',
    '.wbbkkkkkkbbkkkkkbbbbbW.',
    '.wbbbbbbbbbbbbbbbbbbbbW.',
    '.wbbbbgggbbbbbbbbbgggbW.',
    '.wbkkkkkbbkkkkkkkbbbbbW.',
    '.wbbbbbbbbbbbbbbbbbbbbW.',
    '.wbbbggbbbbbbbbggbbbbbW.',
    '.wbbbkkkkkkkbbkkkkbbbbW.',
    '.wbbbbbbbbbbbbbbbbbbbbW.',
    '.wbbbbbbbbbbbbbbbbbbbbW.',
    '.WWWWWWWWWWWWWWWWWWWWWW.',
    '..........pPP...........',
    '..........pPP...........',
    '..........pPP...........',
    '..........pPP...........',
    '..........pPP...........',
    '..........pPP...........',
    '..........pPP...........',
    '..........pPP...........',
    '..........pPP...........',
    '..........pPP...........',
    '..........pPP...........',
    '..........pPP...........',
    '..........pPP...........',
    '..........pPP...........',
    '.........ppPPP..........',
    '.........ppPPP..........',
    '.........ppPPP..........',
];

return [
    'size' => [24, 30],
    'colors' => [
        'w' => '#c89a62', // the board's edge, lit
        'W' => '#6e4c2c', // its edge in shade
        'b' => '#a87a4a', // the board
        'g' => '#8e643a', // its grain
        'k' => '#3a2a20', // the writing
        'p' => '#8a6444', // the post, lit
        'P' => '#5e4230', // the post in shade
    ],
    'shades' => [],
    'outline' => '#22180f',
    'views' => ['down' => [$rows]],
];
