<?php
// The pixel sprites of art/sprites/ (Render\Sprites) and how the Stage shows them: frames, the outline, the right
// turned over from the left, the step a walker shows; the grounds' textures and how a tile takes one; the shadow's
// dither. Needs no window.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\Render\{Sprites, Stage};
use Shanhai\World\{Actor, Dir, Map, Tile, World};

/** A frame's pixel at x, y: [r, g, b, a]. */
$at = fn(string $rgba, int $w, int $x, int $y) => array_values(unpack('C4', substr($rgba, ($y * $w + $x) * 4, 4)));

// Every sprite reads, and every frame of it, every way, comes out its size, robed in every look; it is a person who
// turns and walks, or a thing or a ground that never turns.
$names = array_map(fn(string $f) => substr($f, strlen(Sprites::DIR) + 1, -4), glob(Sprites::DIR . '/*/*.php'));
$beastsData = require __DIR__ . '/../data/beasts.php';
foreach (['people/person', 'things/tree', 'ground/grass', 'beasts/lushu', 'beasts/placeholder'] as $name) {
    check(in_array($name, $names, true), "art/sprites has $name");
}
foreach ($names as $name) {
    $s = Sprites::load($name);
    $folder = explode('/', $name)[0];
    check(in_array($folder, ['people', 'things', 'ground', 'beasts'], true), "$name: in a folder of art/sprites'");
    same($folder === 'people', Sprites::turns($name), "$name: turns if a person");
    if ($folder === 'beasts') {
        same(['left'], array_keys($s['views']), "$name: a beast is seen from the side, facing left");
        if ($name !== 'beasts/placeholder') {
            same($beastsData[substr($name, 7)]['looks'] ?? null, $s['looks'] ?? null, "$name: a beast of data/beasts.php, drawn from the passage its entry has");
        }
    }
    [$w, $h] = $s['size'];
    foreach ([...Sprites::VIEWS, 'right'] as $dir) {
        for ($f = 0; $f < Sprites::frames($name); $f++) {
            foreach (Stage::LOOKS as $look => $robe) {
                same($w * $h * 4, strlen(Sprites::rgba($name, $dir, $f, ['r' => $robe, 'c' => '#808080'])), "$name facing $dir, frame $f, in $look's robe: its size");
            }
            foreach (\Shanhai\Render\Arena::ELEMENTS as $element => $color) {
                same($w * $h * 4, strlen(Sprites::rgba($name, $dir, $f, ['r' => $robe, 'c' => $color])), "$name facing $dir, frame $f, in $element's color: its size");
            }
        }
    }
    same($folder === 'people' ? 3 : 1, Sprites::frames($name), "$name: a person stands and steps with either foot; a thing is still");
    if ($folder === 'ground') {
        check($s['size'][0] === Sprites::PER_TILE && $s['size'][1] % Sprites::PER_TILE === 0, "$name: a tile wide, whole tiles high");
        same(null, $s['outline'], "$name: no outline");
        check(!str_contains(implode('', $s['views']['down'][0]), '.'), "$name: no pixel of it empty");
    }
}
foreach (Stage::THINGS as $kind => [$sprite]) {
    check(Sprites::has($sprite), "the stage's $kind is a sprite of art/sprites");
}

// The person: its robe is the look's, given when drawn; facing right is facing left turned over, pixel for pixel.
[$w, $h] = Sprites::load('people/person')['size'];
check(throws(fn() => Sprites::rgba('people/person', 'down', 0)), 'a person drawn with no robe throws');
$left = Sprites::rgba('people/person', 'left', 1, ['r' => '#d94040']);
$right = Sprites::rgba('people/person', 'right', 1, ['r' => '#d94040']);
$mirrored = true;
for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $mirrored = $mirrored && $at($left, $w, $x, $y)[3] === $at($right, $w, $w - 1 - $x, $y)[3];
    }
}
check($mirrored, 'the person facing right is the left turned over (its shape; the light still comes from the left)');
$reds = fn(string $rgba) => count(array_filter(str_split($rgba, 4), fn(string $p) => ord($p[3]) && ord($p[0]) > 2 * ord($p[2])));
check($reds(Sprites::rgba('people/person', 'down', 0, ['r' => '#d94040'])) > 100 && $reds(Sprites::rgba('people/person', 'down', 0, ['r' => '#4d66b3'])) < 40, 'the robe is the look\'s color');
// the outline: the top of the knot has one over it, and the pixels at the corner, far from the figure, are empty
$down = Sprites::rgba('people/person', 'down', 0, ['r' => '#d94040']);
same(Sprites::load('people/person')['outline'], vsprintf('#%02x%02x%02x', array_slice($at($down, $w, 11, 0), 0, 3)), 'above the knot, the outline');
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
    check(throws(fn() => $check->invoke(null, $make(Sprites::load('people/person')), 'people/person')), "a sprite with $what throws");
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

// A sprite painted by code is the same each time.
same(Sprites::rgba('things/tree', 'down', 0), Sprites::rgba('things/tree', 'down', 0), 'a painted sprite is the same each time');

// A tile's top takes its texture turned over one of four ways, never turned round: its corners' u and v are each
// 0 and 1, the two of each side's ends alike in one of them.
$ways = [];
for ($i = 0; $i < 64; $i++) {
    $uv = Stage::turned($i, 2 * $i);
    $ways[json_encode($uv)] = true;
    check($uv[0][1] === $uv[1][1] && $uv[1][0] === $uv[2][0], "tile $i: turned over, not round");
}
same(4, count($ways), 'tiles take their texture four ways');
same(Stage::turned(3, 4), Stage::turned(3, 4), 'a tile the same way each time');

// The faces of the ground go to the texture of the tile they belong to: a top its tile's, a bank its tile's, not the water's.
$m = Map::fromArray(['id' => 'm', 'name' => 'M', 'rows' => ['.~', '..']]);
$by = Stage::byTexture($m, Stage::faces($m, Stage::ground(...)), Stage::GROUND);
$keys = array_keys($by);
sort($keys);
same(['grass', 'water'], $keys, 'grass and water');
same(1, count(array_filter($by['water'], fn(array $f) => $f[1] === [0.0, 1.0, 0.0])), 'one top of water');
check(count(array_filter($by['grass'], fn(array $f) => $f[1] !== [0.0, 1.0, 0.0] && min(array_column($f[0], 1)) === Stage::WATER_TOP)) === 2, 'its two banks are the grass\'s');

// The shadow under an actor: a dither, no pixel beside another; some, none at the corners.
$shadow = Stage::shadow();
$pixels = array_map(fn(string $px) => $px[3] !== "\0", str_split($shadow, 4));
$sw = (int)sqrt(count($pixels) * 2);
$touching = 0;
foreach ($pixels as $i => $on) {
    $touching += $on && ($i % $sw) + 1 < $sw && $pixels[$i + 1] ? 1 : 0;
}
check(array_sum($pixels) > 10 && $touching === 0 && !$pixels[0], 'the shadow is a dithered oval');

// Outdoors the land goes on past the map's edges (Stage::surround): the map in the middle as it is; beyond an edge
// with an exit what can be walked on goes on, and water; all else is open land.
$m = Map::fromArray(['id' => 'm', 'name' => 'M', 'rows' => ['=.^', '...', '~.T'], 'exits' => ['left' => ['n', 0]]]);
$land = Stage::surround($m);
$b = Stage::BEYOND;
same([3 + $b['left'] + $b['right'], 3 + $b['up'] + $b['down']], [$land->width, $land->height], 'the land: the map and BEYOND each way');
$at = fn(int $x, int $y) => Tile::name($land->tile($x + $b['left'], $y + $b['up']));
same(['path', 'grass', 'rock'], [$at(0, 0), $at(1, 0), $at(2, 0)], 'the map in the middle, as it is');
same(['path', 'grass', 'water'], [$at(-1, 0), $at(-5, 1), $at(-3, 2)], 'past the edge with an exit: the path and the grass go on, and the water');
$wild = [];
for ($y = -$b['up']; $y < 0; $y++) {
    for ($x = -$b['left']; $x < 3 + $b['right']; $x++) {
        $wild[$at($x, $y)] = ($wild[$at($x, $y)] ?? 0) + 1;
    }
}
check(isset($wild['tree'], $wild['rock'], $wild['tallGrass']) && $wild['grass'] > 4 * $wild['tree'], 'past an edge with none: open land, mostly grass, a tree, a rock, tall grass here and there');
check(!isset($wild['path']) && !isset($wild['water']), 'no path or water where the edge has none');
same('water', $at(1 - 1, 3), 'water at the edge goes on past it');

// What stands between the camera and the player is faded: a tree just before them, not one behind or far to the
// side; never the tall grass or a rock, which are low.
check(Stage::fades('tree', 0.0, 1.0) && Stage::fades('tree', 1.0, 2.0), 'a tree before the player fades');
check(!Stage::fades('tree', 0.0, -1.0) && !Stage::fades('tree', 3.0, 1.0) && !Stage::fades('tree', 0.0, 9.0), 'not one behind them, beside them or far before them');
check(!Stage::fades('tallGrass', 0.0, 0.3) && !Stage::fades('rock', 0.0, 1.0), 'tall grass and rocks never');

// A round figure is shaded across as a body: on a row of it, the left lighter than the right.
$light = Sprites::round(['.xxxxxxxxxxxxxxxx.']);
check($light[0][1] > $light[0][16] && $light[0][0] === 1.0, 'a round figure: lit on the left, in shade on the right; nothing where it is empty');
check(Sprites::load('people/person')['round'] ?? false, 'a person is drawn round');

// Beyond an exit the map beside it lies, as it is (World::neighbours), the land going on across the seam.
$here = Map::fromArray(['id' => 'h', 'name' => 'H', 'rows' => ['..', '..'], 'exits' => ['right' => ['n', 0]]]);
$next = Map::fromArray(['id' => 'n', 'name' => 'N', 'rows' => ['=~', '^T']]);
$land = Stage::surround($here, ['right' => [$next, 2, 0]]);
$at = fn(int $x, int $y) => Tile::name($land->tile($x + $b['left'], $y + $b['up']));
same(['path', 'water', 'rock', 'tree'], [$at(2, 0), $at(3, 0), $at(2, 1), $at(3, 1)], 'the map beside, as it is, where it lies');

// A beast in battle: its sprite, facing right turned over from facing left; one with none, the placeholder in its element's color.
[$w] = Sprites::size('beasts/lushu');
$l = Sprites::rgba('beasts/lushu', 'left', 0);
$r = Sprites::rgba('beasts/lushu', 'right', 0);
$pixel = fn(string $rgba, int $w, int $x, int $y) => array_values(unpack('C4', substr($rgba, ($y * $w + $x) * 4, 4)));
same($pixel($l, $w, 3, 12), $pixel($r, $w, $w - 4, 12), 'a beast facing right is the left turned over');
same(['beasts/lushu', []], \Shanhai\Render\Arena::sprite(new \Shanhai\Battle\Fighter('lushu', $beastsData['lushu'], 5, [], [])), '鹿蜀 is its own sprite');
$other = array_key_first(array_filter($beastsData, fn(array $b, string $id) => !Sprites::has("beasts/$id"), ARRAY_FILTER_USE_BOTH));
same(['beasts/placeholder', ['c' => \Shanhai\Render\Arena::ELEMENTS[$beastsData[$other]['elements'][0]]]], \Shanhai\Render\Arena::sprite(new \Shanhai\Battle\Fighter($other, $beastsData[$other], 5, [], [])), 'one with none, the placeholder in its element\'s color');
