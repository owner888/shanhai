<?php
// Walking the grid: turning, stepping, what blocks, doors, talking, triggers.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\Data;
use Shanhai\World\{Controls, Dir, World};

/** Holds $dir until the player stands on $x,$y of map $map (which may be another), then lets go. */
$go = function (World $w, Dir $dir, int $x, int $y, ?string $map = null): bool {
    $map ??= $w->map->id;
    for ($i = 0; $i < 600; $i++) {
        $p = $w->player;
        if ($w->map->id === $map && !$p->moving && $p->x === $x && $p->y === $y) {
            $w->step(new Controls());
            return true;
        }
        $w->step(new Controls($dir));
    }
    return false;
};
$confirm = fn(World $w) => $w->step(new Controls(confirm: true));

$w = Data::newWorld(1);
same('zhaoyao', $w->map->id, 'a new game starts at the mountain\'s foot');
same([9, 8], [$w->player->x, $w->player->y], 'where data/game.php says');

// A tap turns; holding steps, one tile in WALK_STEPS updates.
$w->step(new Controls(Dir::Up));
same(Dir::Up, $w->player->dir, 'a tap turns the player');
check(!$w->player->moving, 'and does not step');
$w->step(new Controls());
for ($i = 0; $i < World::WALK_STEPS; $i++) {
    $w->step(new Controls(Dir::Up));
}
same([9, 7], [$w->player->x, $w->player->y], 'facing the way already, holding steps one tile in WALK_STEPS updates');
[$px, $py] = $w->player->place(0.0);
same([9.0, 7.0], [$px, $py], 'drawn on its tile when still');

// Halfway through a step the player is drawn between the two tiles.
$w->step(new Controls(Dir::Up));
for ($i = 0; $i < World::WALK_STEPS / 2 - 1; $i++) {
    $w->step(new Controls(Dir::Up));
}
same([9.0, 6.5], $w->player->place(0.0), 'halfway through a step, between the tiles');
check($go($w, Dir::Up, 9, 6), 'and arrives');

// The elder: walk beside him, face him, talk.
check($go($w, Dir::Left, 8, 6), 'walks left to below the elder');
$w->step(new Controls(Dir::Up));
$w->step(new Controls());
same([8, 6], [$w->player->x, $w->player->y], 'the elder blocks the tile he stands on');
$confirm($w);
check($w->dialog !== null && str_contains($w->dialog, '招摇之山'), 'confirm, facing him, starts his lines: ' . $w->dialog);
check($w->busy(), 'a script holds the controls');
$w->step(new Controls(Dir::Down));
same([8, 6], [$w->player->x, $w->player->y], 'the player cannot walk away from a line showing');
$confirm($w);
check($w->dialog !== null && str_contains($w->dialog, '狌狌'), 'confirm shows the next line');
$confirm($w);
$confirm($w);
check($w->dialog === null && !$w->busy(), 'the last confirm ends the talk');
check(isset($w->flags['metElder']), 'and the flag is set');
$confirm($w);
check(str_contains((string)$w->dialog, '巫祝'), 'he says something else the second time');
$confirm($w);

// The hut: its door warps in, the door inside warps out.
check($go($w, Dir::Left, 5, 6), 'walks to below the door');
check($go($w, Dir::Up, 3, 3, 'zhaoyao_hut'), 'the door leads into the hut');
same(2, $w->mapChanges, 'what draws the world is told of the new map');
check(isset($w->npcs['shaman']) && !isset($w->npcs['elder']), 'the hut\'s people, not the mountain\'s');
check($go($w, Dir::Up, 3, 2), 'walks up to the shaman\'s row');
$w->step(new Controls(Dir::Right));
$w->step(new Controls());
$confirm($w);
check($w->dialog !== null, 'the shaman talks');
same(Dir::Left, $w->npcs['shaman']->dir, 'turned to the player');
$confirm($w);
$confirm($w);
same(['你想与哪一只同行？', ['鹿蜀', '旋龟', '灌灌']], [$w->dialog, $w->choices], 'she asks which of the three');
$w->step(new Controls(cancel: true));
check($w->choices !== null, 'cancel does not answer');
$w->step(new Controls(Dir::Down));
$confirm($w);
check(str_starts_with($w->dialog, '旋龟，') && $w->choices === ['就是它', '再想想'], 'the second: she tells of it, and asks again');
$w->step(new Controls(Dir::Down));
$confirm($w);
same('你想与哪一只同行？', $w->dialog, 'thinking again goes back to the three');
$w->step(new Controls(Dir::Down));
$w->step(new Controls());
$w->step(new Controls(Dir::Down));
$confirm($w);
$confirm($w);
same([['guanguan', 5]], array_map(fn($f) => [$f->id, $f->level], $w->party), 'the third, taken, joins the player');
check($w->choices === null && $w->dialog !== null, 'and she goes on');
$confirm($w);
check(isset($w->flags['metShaman']) && !$w->busy(), 'through her lines');
same(['seal' => 5], $w->items, 'she gives seals');
check($go($w, Dir::Down, 5, 6, 'zhaoyao'), 'the door inside leads back out, below the door');
same(Dir::Down, $w->player->dir, 'facing away from it');

// What blocks: water, trees, walls, the map's edge; the sign, which reads.
check(!$w->free(1, 6), 'water blocks');
check(!$w->free(5, 9), 'a tree blocks');
check(!$w->free(4, 5), 'a wall blocks');
check(!$w->free(-1, 6) && !$w->free(11, 15), 'the map\'s edge blocks');
check(!$w->free(12, 7), 'a sign blocks');
check($go($w, Dir::Right, 11, 6), 'walks right to the path');
check($go($w, Dir::Down, 11, 7), 'down the path');
$w->step(new Controls(Dir::Right));
$w->step(new Controls());
$confirm($w);
check(str_contains((string)$w->dialog, '西海之滨'), 'the sign reads');
$confirm($w);

// The trigger by the sea runs once.
check($go($w, Dir::Down, 11, 10), 'down the path');
for ($i = 0; $i < 600 && !$w->busy(); $i++) {
    $w->step(new Controls(Dir::Left));
}
same([3, 10], [$w->player->x, $w->player->y], 'stepping on the trigger\'s tile stops the player there');
check(str_contains((string)$w->dialog, '海风'), 'and runs its script');
$confirm($w);
check($go($w, Dir::Right, 4, 10), 'steps off');
check($go($w, Dir::Left, 3, 10) && !$w->busy(), 'it does not run a second time');

// Wild beasts in tall grass (docs/numbers.md 13), with the rolls scripted.
$book = ['beasts' => Data::beasts(), 'learnsets' => Data::learnsets(), 'moves' => Data::moves(), 'elements' => Data::elements(), 'items' => Data::items()];
$field = \Shanhai\World\Map::fromArray(['id' => 'field', 'name' => 'Field', 'rows' => [',,,,,,,,,,,,', '............'],
    'wild' => ['rate' => 10, 'slots' => [[2, 'xingxing'], [3, 'baiyuan'], [4, 'xingxing'], [3, 'xingxing'], [5, 'xingxing'], [4, 'xingxing'], [9, 'fuchong']]]]);
$rng = \Shanhai\Battle\Rng::scripted([]);
$w = new World(['field' => $field], 'field', 0, 0, Dir::Right, $book, $rng);
$step = function (Dir $dir = Dir::Right) use (&$w): void {
    for ($i = 0; $i < World::WALK_STEPS; $i++) {
        $w->step(new Controls($dir));
    }
};
$step();
$step();
same([2, null], [$w->player->x, $w->battle], 'with no beast in the party the grass holds none, and no roll is asked for');
$w->join('xingxing', 20);
$w->enter('field', 0, 0, Dir::Right);
for ($i = 0; $i < 4; $i++) {
    $step();
}
same([4, null], [$w->player->x, $w->battle], 'the first four steps on a map meet nothing, and ask for no roll');
$rng->more([25]);
$step();
same([5, null, 0], [$w->player->x, $w->battle, $rng->left()], 'the fifth asks for a roll: 25, not under the rate\'s 25, meets nothing');
$rng->more([24, 200, 30]);
$step();
check($w->battle !== null && $rng->left() === 0, 'the next: 24 meets a beast; 200 is asked for again; 30 is the second of the seven');
same(['baiyuan', 3], [$w->battle->battle->fighter(1)->id, $w->battle->battle->fighter(1)->level], 'which is the map\'s second, at its level');
same([6, false], [$w->player->x, $w->player->moving], 'the player stands on the tile stepped onto');
// the faster beast flees with no roll: the two lines, the menu's last entry, the last line
foreach ([[null, true], [null, true], [Dir::Down, false], [null, false], [Dir::Down, false], [null, false], [Dir::Down, false], [null, true], [null, true]] as [$dir, $confirm]) {
    $w->step(new Controls($dir, $confirm));
}
same([null, 'fled'], [$w->battle, $w->lastBattle], 'the battle is fled');
$w->step(new Controls());
for ($i = 0; $i < 4; $i++) {
    $step();
}
same([10, null], [$w->player->x, $w->battle], 'after a battle the next four steps meet nothing again');
$rng->more([0, 99]);
$step();
same(['fuchong', 9], [$w->battle?->battle->fighter(1)->id, $w->battle?->battle->fighter(1)->level], 'then the fifth can: 99 is the last of the seven');
foreach ([[null, true], [null, true], [Dir::Down, false], [null, false], [Dir::Down, false], [null, false], [Dir::Down, false], [null, true], [null, true]] as [$dir, $confirm]) {
    $w->step(new Controls($dir, $confirm));
}
$w->step(new Controls());
$w->enter('field', 0, 1, Dir::Right);
for ($i = 0; $i < 8; $i++) {
    $step();
}
same([8, null, 0], [$w->player->x, $w->battle, $rng->left()], 'steps outside the tall grass ask for no roll');
$w->party[0]->hp = 0;
$w->enter('field', 0, 0, Dir::Right);
for ($i = 0; $i < 8; $i++) {
    $step();
}
same([8, null], [$w->player->x, $w->battle], 'nor do steps in it with no beast that can fight');

// Maps joined at their edges: walking off one enters the next, at its opposite edge.
$a = \Shanhai\World\Map::fromArray(['id' => 'a', 'name' => 'A', 'rows' => ['T..', '...', '..T'], 'exits' => ['right' => ['b', 1], 'down' => ['c', -1]]]);
$b = \Shanhai\World\Map::fromArray(['id' => 'b', 'name' => 'B', 'rows' => ['...', 'T..', '...', '...'], 'exits' => ['left' => ['a', -1]]]);
$c = \Shanhai\World\Map::fromArray(['id' => 'c', 'name' => 'C', 'rows' => ['..'], 'exits' => ['up' => ['a', 1]]]);
$w = new World(['a' => $a, 'b' => $b, 'c' => $c], 'a', 2, 1, Dir::Right);
$walk = function (Dir $dir) use (&$w): void {
    for ($i = 0; $i < World::WALK_STEPS; $i++) {
        $w->step(new Controls($dir));
    }
};
$changes = $w->mapChanges;
$w->step(new Controls(Dir::Right));
same(['b', 0, 2, Dir::Right, $changes + 1], [$w->map->id, $w->player->x, $w->player->y, $w->player->dir, $w->mapChanges], 'off the east edge at row 1: the next map\'s west edge, a row lower as the exit says');
$w->step(new Controls(Dir::Left));
$w->step(new Controls());
for ($i = 0; $i <= World::TURN_STEPS; $i++) {
    $w->step(new Controls(Dir::Left));
}
same(['a', 2, 1], [$w->map->id, $w->player->x, $w->player->y], 'and back, to where the player left');
$w->enter('a', 2, 0, Dir::Right);
$walk(Dir::Right);
same(['a', 2, 0], [$w->map->id, $w->player->x, $w->player->y], 'where the other map\'s tile cannot be walked on, the edge is an edge');
$w->enter('a', 1, 0, Dir::Up);
$walk(Dir::Up);
same(['a', 1, 0], [$w->map->id, $w->player->x, $w->player->y], 'and so it is where the map has no exit');
$w->enter('a', 1, 2, Dir::Down);
$w->step(new Controls(Dir::Down));
same(['c', 0, 0], [$w->map->id, $w->player->x, $w->player->y], 'off the south edge: the next map\'s north edge, a tile to the west as the exit says');

// The road east from 招摇之山 leads to 堂庭之山, and on to the foot of 猨翼之山.
$w = Data::newWorld(1);
$w->enter('zhaoyao', 19, 6, Dir::Right);
$w->step(new Controls(Dir::Right));
same(['tangting', 0, 7], [$w->map->id, $w->player->x, $w->player->y], 'east of 招摇之山 is 堂庭之山');
$w->enter('tangting', 19, 7, Dir::Right);
$w->step(new Controls(Dir::Right));
same(['yuanyi', 0, 7], [$w->map->id, $w->player->x, $w->player->y], 'and east of it 猨翼之山');
