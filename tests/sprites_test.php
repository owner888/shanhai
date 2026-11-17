<?php
// The pixel sprites of art/sprites/ (Render\Sprites) and how the Stage shows them: frames, the outline, the right
// turned over from the left, the step a walker shows; and the ground's grain and the shadow's dither. Needs no window.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\Render\{Sprites, Stage};
use Shanhai\World\{Actor, Dir, World};

/** A frame's pixel at x, y: [r, g, b, a]. */
$at = fn(string $rgba, int $w, int $x, int $y) => array_values(unpack('C4', substr($rgba, ($y * $w + $x) * 4, 4)));

// Every sprite reads, and every frame of it, every way, comes out its size, robed in every look.
foreach (glob(Sprites::DIR . '/*.php') as $file) {
    $name = basename($file, '.php');
    $s = Sprites::load($name);
    [$w, $h] = $s['size'];
    foreach ([...Sprites::VIEWS, 'right'] as $dir) {
        for ($f = 0; $f < Sprites::frames($name); $f++) {
            foreach (Stage::LOOKS as $look => $robe) {
                same($w * $h * 4, strlen(Sprites::rgba($name, $dir, $f, ['r' => $robe])), "$name facing $dir, frame $f, in $look's robe: its size");
            }
        }
    }
    check(Sprites::frames($name) === 3, "$name: a standing frame and a walk's two steps");
}

// The person: its robe is the look's, given when drawn; facing right is facing left turned over, pixel for pixel.
[$w, $h] = Sprites::load('person')['size'];
check(throws(fn() => Sprites::rgba('person', 'down', 0)), 'a person drawn with no robe throws');
$left = Sprites::rgba('person', 'left', 1, ['r' => '#d94040']);
$right = Sprites::rgba('person', 'right', 1, ['r' => '#d94040']);
$mirrored = true;
for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $mirrored = $mirrored && $at($left, $w, $x, $y) === $at($right, $w, $w - 1 - $x, $y);
    }
}
check($mirrored, 'the person facing right is the left turned over');
$robes = fn(string $rgba) => substr_count(bin2hex($rgba), 'd94040ff');
check($robes(Sprites::rgba('person', 'down', 0, ['r' => '#d94040'])) > 100, 'the robe is the look\'s color');
// the outline: the top of the knot has one over it, and the pixels at the corner, far from the figure, are empty
$down = Sprites::rgba('person', 'down', 0, ['r' => '#d94040']);
same(Sprites::load('person')['outline'], vsprintf('#%02x%02x%02x', array_slice($at($down, $w, 11, 0), 0, 3)), 'above the knot, the outline');
same([0, 0, 0, 0], $at($down, $w, 0, 0), 'a corner is empty');

// Sprites that are wrong throw where they are read.
$bad = [
    'a row too short' => fn(array $s) => ['views' => ['down' => [array_replace($s['views']['down'][0], [0 => '.'])]] + $s['views']] + $s,
    'a color not written' => fn(array $s) => ['views' => ['down' => [array_replace($s['views']['down'][0], [0 => str_repeat('Z', 24)]), ...array_slice($s['views']['down'], 1)]] + $s['views']] + $s,
    'a way with fewer frames' => fn(array $s) => ['views' => ['up' => [$s['views']['up'][0]]] + $s['views']] + $s,
    'no outline' => fn(array $s) => array_diff_key($s, ['outline' => 0]),
];
$check = new \ReflectionMethod(Sprites::class, 'check');
foreach ($bad as $what => $make) {
    check(throws(fn() => $check->invoke(null, $make(Sprites::load('person')), 'person')), "a sprite with $what throws");
}

// The frame a walker shows: standing still the standing one; the first half of a step a foot forward, the other
// foot on the next tile; the second half standing again.
$a = new Actor('a', 2, 2, Dir::Down, 'player');
same(0, Stage::step($a, 0.5), 'standing still, the standing frame');
[$a->moving, $a->toX, $a->toY, $a->progress] = [true, 2, 3, 0];
same(1, Stage::step($a, 0.0), 'stepping off an even tile: one foot forward');
$a->progress = intdiv(World::WALK_STEPS, 2);
same(0, Stage::step($a, 0.0), 'halfway: standing');
[$a->y, $a->toY, $a->progress] = [3, 4, 0];
same(2, Stage::step($a, 0.0), 'off the next tile: the other foot');

// The camera's slant: an actor stands taller by it, as much as an upright thing shows shorter.
$slant = atan2(Stage::CAMERA_HEIGHT, Stage::CAMERA_BACK);
check(abs(Stage::stretch() * cos($slant) - 1.0) < 1e-9, 'stood stretch() taller, an actor shows as tall as written');

// The grain: a tile's side of pixels, grays and opaque, the same each time; a tile's top turned one of eight ways.
$grain = Stage::grain();
same(Sprites::PER_TILE ** 2 * 4, strlen($grain), 'the grain is a tile\'s side of pixels each way');
same($grain, Stage::grain(), 'the same each time');
$opaque = true;
foreach (str_split($grain, 4) as $px) {
    $opaque = $opaque && $px[0] === $px[1] && $px[1] === $px[2] && $px[3] === "\xff";
}
check($opaque, 'every pixel of it gray and opaque');
$ways = [];
for ($i = 0; $i < 64; $i++) {
    $ways[json_encode(Stage::turned($i, 2 * $i))] = true;
}
same(8, count($ways), 'tiles take the grain eight ways');
same(Stage::turned(3, 4), Stage::turned(3, 4), 'a tile the same way each time');

// The shadow under an actor: a dither, no pixel beside another; some, none at the corners.
$shadow = Stage::shadow();
$pixels = array_map(fn(string $px) => $px[3] !== "\0", str_split($shadow, 4));
$sw = (int)sqrt(count($pixels) * 2);
$touching = 0;
foreach ($pixels as $i => $on) {
    $touching += $on && ($i % $sw) + 1 < $sw && $pixels[$i + 1] ? 1 : 0;
}
check(array_sum($pixels) > 10 && $touching === 0 && !$pixels[0], 'the shadow is a dithered oval');
