<?php
// Walking the grid: turning, stepping, what blocks, doors, talking, triggers.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\Data;
use Shanhai\World\{Controls, Dir, Pen, World};

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
same('带不走的异兽，可以寄在屋后的兽栏里。各个山系的渡者那里也通着它。契印用完了，拿贝来换；我这里还有些给异兽带着的草木。', $w->dialog, 'and tells of the pen, and of buying seals');
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
// docs/numbers.md 19: what the classic says grows on a mountain is found by its sign, once.
same(['牌子旁边长着迷榖。你采了一份。', ['seal' => 5, 'mihu' => 1]], [$w->dialog, $w->items], 'by the sign grows what the classic says, and it is taken');
$confirm($w);
$confirm($w);
check(str_contains((string)$w->dialog, '西海之滨'), 'read again');
$confirm($w);
same([false, 1], [$w->busy(), $w->items['mihu']], 'the sign has no more to give');

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
// docs/numbers.md 19: a rate of 10 is a byte under 25; with what wards wild beasts off held in the party, under 12.
$calm = new World(['field' => $field], 'field', 0, 0, Dir::Right, $book, $warded = \Shanhai\Battle\Rng::scripted([12]));
$calm->join('xingxing', 20);
$calm->party[0]->item = 'diwu';
for ($i = 0; $i < 5 * World::WALK_STEPS; $i++) {
    $calm->step(new Controls(Dir::Right));
}
same([5, null, 0], [$calm->player->x, $calm->battle, $warded->left()], 'with what wards them off held, a byte of 12 meets no wild beast');
$rng->more([25]);
$step();
same([5, null, 0], [$w->player->x, $w->battle, $rng->left()], 'the fifth asks for a roll: 25, not under the rate\'s 25, meets nothing');
$rng->more([24, 200, 30, 0]);
$step();
check($w->battle !== null && $rng->left() === 0, 'the next: 24 meets a beast; 200 is asked for again; 30 is the second of the seven; 0, it holds nothing');
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
$rng->more([0, 99, 0]);
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

// Water: a beast of the water element carries the player over it, and the water holds its own wild beasts.
$lake = \Shanhai\World\Map::fromArray(['id' => 'lake', 'name' => 'Lake', 'rows' => ['.~~~.', '.....'],
    'water' => ['rate' => 6, 'slots' => [[10, 'xuangui'], [12, 'chiru'], [14, 'jiyu']]], 'exits' => ['up' => ['sea', 0]]]);
$sea = \Shanhai\World\Map::fromArray(['id' => 'sea', 'name' => 'Sea', 'rows' => ['~~~~~', '~~~~.'], 'exits' => ['down' => ['lake', 0]]]);
$rng = \Shanhai\Battle\Rng::scripted([]);
$w = new World(['lake' => $lake, 'sea' => $sea], 'lake', 0, 0, Dir::Right, $book + ['float' => 'mayFloat'], $rng, sys_get_temp_dir() . '/shanhai-afloat-test.json');
$press = new Controls(confirm: true);
$w->join('xingxing', 30);
$w->step($press);
same([false, false], [$w->busy(), $w->afloat], 'with no beast of water, facing water does nothing');
$w->join('xuangui', 30);
$w->party[1]->hp = 0;
$w->step($press);
check(!$w->busy(), 'nor with one that cannot fight');
$w->party[1]->hp = 9;
$w->step($press);
same(['还不能渡水：还有一场试炼没有通过。', null], [$w->dialog, $w->choices], 'with one, but before the trial that allows it, the player is told so');
$w->step($press);
check(!$w->busy() && !$w->afloat && throws(fn() => $w->float()), 'and stays ashore');
$w->run([['setFlag', 'mayFloat']]);
$w->step($press);
same(['水面平静。要乘着旋龟渡水吗？', ['渡水', '算了']], [$w->dialog, $w->choices], 'once it is allowed, the player is asked');
$w->step(new Controls(Dir::Down));
$w->step($press);
same([false, false, 0], [$w->busy(), $w->afloat, $w->player->x], 'declining stays ashore');
$w->step($press);
$w->step($press);
check($w->afloat && $w->player->moving, 'accepting, the player goes onto the water');
for ($i = 1; $i < World::WALK_STEPS; $i++) {
    $w->step(new Controls());
}
same([1, 0, true, 0], [$w->player->x, $w->player->y, $w->afloat, $rng->left()], 'and is afloat; the first steps on a map meet nothing');
$w->step(new Controls(menu: true));
foreach ([new Controls(Dir::Down), new Controls(), new Controls(Dir::Down), $press] as $c) {
    $w->step($c);
}
same('在水面上不能存档。', $w->dialog, 'the game is not saved on water');
$w->step($press);
// Holds a direction until the step it begins is done, or it is clear there is none: turning first takes its own updates.
$go = function (Dir $dir) use (&$w): void {
    [$map, $x, $y] = [$w->map->id, $w->player->x, $w->player->y];
    for ($i = 0; $i < 2 * World::WALK_STEPS && $w->battle === null; $i++) {
        $w->step(new Controls($dir));
        if (!$w->player->moving && [$map, $x, $y] !== [$w->map->id, $w->player->x, $w->player->y]) {
            return;
        }
    }
};
$go(Dir::Right);
$go(Dir::Right);
same([3, true], [$w->player->x, $w->afloat], 'afloat, the player goes on over water');
$go(Dir::Left);
$rng->more([15]);
$go(Dir::Right);
same([3, null, 0], [$w->player->x, $w->battle, $rng->left()], 'the fifth step asks for a roll: 15, not under the water\'s 15, meets nothing');
$rng->more([14, 60, 216, 0]);
$go(Dir::Left);
same(['chiru', 15], [$w->battle?->battle->fighter(1)->id, $w->battle?->battle->fighter(1)->level], 'the next: 14 meets a beast; 60 is the second of the three; 216 adds three levels to its 12');
// the faster beast flees with no roll: the two lines, the menu's last entry, the last line
foreach ([[null, true], [null, true], [Dir::Down, false], [null, false], [Dir::Down, false], [null, false], [Dir::Down, false], [null, true], [null, true]] as [$dir, $confirm]) {
    $w->step(new Controls($dir, $confirm));
}
same([null, true], [$w->battle, $w->afloat], 'after the battle the player is afloat still');
$w->step(new Controls());
$go(Dir::Up);
same(['sea', 2, 1, true], [$w->map->id, $w->player->x, $w->player->y, $w->afloat], 'afloat, the player crosses onto another map\'s water');
$go(Dir::Right);
$go(Dir::Right);
same([4, 1, false], [$w->player->x, $w->player->y, $w->afloat], 'stepping onto land, the player is ashore');
$go(Dir::Left);
same(4, $w->player->x, 'and water blocks the way again');
$w->enter('lake', 0, 0, Dir::Down);
$go(Dir::Up);
same(['lake', 0, 0], [$w->map->id, $w->player->x, $w->player->y], 'ashore, the player does not cross onto another map\'s water');
check(throws(fn() => $w->float()), 'nor go onto water that is not faced');

// The pen (docs/numbers.md 16): a script opens it and waits; beasts are left in the current box and taken from it.
$w = Data::newWorld(1);
$w->join('lushu', 5);
$w->join('xuangui', 5);
$w->party[1]->hp = 0;
$press = new Controls(confirm: true);
$back = new Controls(cancel: true);
/** Moves the cursor $n down and confirms. */
$pick = function (int $n) use ($w, $press): void {
    for ($i = 0; $i < $n; $i++) {
        $w->step(new Controls(Dir::Down));
        $w->step(new Controls());
    }
    $w->step($press);
};
$ids = fn(array $beasts) => array_map(fn($f) => $f->id, $beasts);
$items = fn() => $w->pen->items($w->party, $w->boxes, $w->box);
$w->run([['pen'], ['say', 'closed']]);
check($w->pen !== null && $w->dialog === null, 'a script opens the pen, and waits');
same(['取出', '寄存', '放归', '换栏  第一栏 0/20'], $items(), 'its first list: taking, leaving, setting free, and the current box');
$pick(1);
same(['鹿蜀  Lv5  ' . $w->party[0]->hp . '/' . $w->party[0]->maxHp, '旋龟  Lv5  0/' . $w->party[1]->maxHp . '  倒'], $items(), 'leaving shows the party');
$pick(0);
same(['队伍里要留一只能战斗的。', 2], [$w->pen->note, count($w->party)], 'the only beast that can fight is not left');
$w->step($press);
$pick(1);
$kept = $w->boxes[0][0];
same(['把旋龟寄在了第一栏。', ['lushu'], ['xuangui'], $kept->maxHp], [$w->pen->note, $ids($w->party), $ids($w->boxes[0]), $kept->hp], 'another is, and in the box it is whole');
$w->step($press);
$pick(0);
same(['最后一只不能寄存。', 1], [$w->pen->note, count($w->party)], 'the last of the party is not left');
$w->step($press);
$w->step($back);
$pick(3);
same(Pen::BOXES, count($items()), 'changing box shows the fourteen');
$pick(2);
same([2, '换栏  第三栏 0/20'], [$w->box, $items()[3]], 'the one chosen is the current one');
$pick(0);
same(['（空）'], $items(), 'taking from an empty box shows it empty');
$w->step($press);
same([null, 1], [$w->pen->note, count($w->party)], 'and takes nothing');
$w->step($back);
$pick(3);
$pick(0);
$pick(0);
$pick(0);
same(['把旋龟带回了队伍。', ['lushu', 'xuangui'], []], [$w->pen->note, $ids($w->party), $ids($w->boxes[0])], 'a beast taken joins the party');
$w->step($press);
$w->step($back);
for ($i = 0; $i < 4; $i++) {
    $w->join('xingxing', 2);
}
$w->boxes[0] = [$w->party[5], $w->party[4]];
$w->party[4] = $w->party[5] = $w->party[0];
$pick(0);
$pick(0);
same(['队伍已经满了。', 2], [$w->pen->note, count($w->boxes[0])], 'none is taken into a full party');
$w->step($press);
$w->step($back);
$pick(2);
$pick(1);
same(['留下', '放归狌狌'], $items(), 'setting one free asks first');
$pick(0);
same([2, 'free'], [count($w->boxes[0]), $w->pen->phase], 'kept, it stays');
$pick(1);
$pick(1);
same(['狌狌回山里去了。', 1], [$w->pen->note, count($w->boxes[0])], 'set free, it is gone');
$w->step($press);
$w->step($back);
$w->boxes[0] = array_fill(0, Pen::BOX, $w->boxes[0][0]);
$pick(1);
$pick(1);
same(['第一栏满了。', World::PARTY], [$w->pen->note, count($w->party)], 'none is left in a full box');
$w->step($press);
$w->step($back);
$w->step($back);
same([null, null], [$w->pen, $w->dialog], 'cancel at its first list closes it');
$w->step(new Controls());
same('closed', $w->dialog, 'and the script goes on');
$twenty = array_map(strval(...), range(0, 19));
same([[['0', '1', '2', '3', '4', '5', '6', '7'], 0], [['6', '7', '8', '9', '10', '11', '12', '13'], 4], [['12', '13', '14', '15', '16', '17', '18', '19'], 7], [['a', 'b'], 1]],
    [Pen::window($twenty, 0), Pen::window($twenty, 10), Pen::window($twenty, 19), Pen::window(['a', 'b'], 1)], 'a long list shows eight lines about its cursor');

// Buying (docs/numbers.md 17): a script opens it and waits; a thing is chosen, then how many; it is paid for.
$w = Data::newWorld(1);
$press = new Controls(confirm: true);
$back = new Controls(cancel: true);
$tap = function (Dir $dir, int $times = 1) use ($w): void {
    for ($i = 0; $i < $times; $i++) {
        $w->step(new Controls($dir));
        $w->step(new Controls());
    }
};
$things = Data::items();
$w->run([['shop', ['seal']], ['say', 'left']]);
check($w->shop !== null && $w->dialog === null, 'a script opens buying, and waits');
same([['契印  200贝', '离开'], 3000], [$w->shop->items($things), $w->money], 'the things sold with their prices, and leaving; a new game has 3000');
$w->step($press);
same(['契印 ×1  200贝'], $w->shop->items($things), 'one chosen, how many is asked, beginning at one');
$tap(Dir::Up, 2);
$tap(Dir::Right);
$tap(Dir::Down);
same(['契印 ×12  2400贝'], $w->shop->items($things), 'up and down by one, right and left by ten');
$tap(Dir::Left, 3);
same(1, $w->shop->count, 'not under one');
$tap(Dir::Right, 11);
same(99, $w->shop->count, 'nor over ninety-nine');
$w->step($press);
same(['贝不够。', 3000, []], [$w->shop->note, $w->money, $w->items], 'more than can be paid for is not sold');
$w->step($press);
$w->step($press);
$tap(Dir::Up, 4);
$w->step($press);
same(['买了5枚契印，花了1000贝。', 2000, ['seal' => 5]], [$w->shop->note, $w->money, $w->items], 'what can be is: paid for, and in the bag');
$w->step($press);
$w->items['seal'] = 98;
$w->step($press);
$tap(Dir::Up);
$w->step($press);
same(['背包里放不下这么多。', 2000, 98], [$w->shop->note, $w->money, $w->items['seal']], 'a bag holds ninety-nine of a thing at most');
$w->step($press);
$tap(Dir::Down);
$w->step($press);
same(null, $w->shop, 'leaving closes it');
$w->step(new Controls());
same('left', $w->dialog, 'and the script goes on');
check(throws(fn() => \Shanhai\Script\Vm::check([['shop', []]])) && throws(fn() => \Shanhai\Script\Vm::check([['shop', 'seal']])), 'buying with nothing sold is refused');

// What a wild beast holds (docs/numbers.md 20): nothing on a byte under 192; else, of what grows on its mountain, the thing
// that is found on a byte under 20, and the thing that is sold on any other. 土蝼 lives on 昆仑之丘, where 薲草 and 沙棠 grow.
$book = ['beasts' => Data::beasts(), 'learnsets' => Data::learnsets(), 'moves' => Data::moves(), 'elements' => Data::elements(), 'items' => Data::items()];
$holds = function (string $beast, array $rolls) use ($book): array {
    $w = new World(['field' => \Shanhai\World\Map::fromArray(['id' => 'field', 'name' => 'Field', 'rows' => ['..']])], 'field', 0, 0, Dir::Right, $book, $rng = \Shanhai\Battle\Rng::scripted($rolls));
    $w->join('xingxing', 20);
    $w->run([['battle', $beast, 5]]);
    return [$w->battle->battle->fighter(1)->item, $rng->left()];
};
same([[null, 0], ['pincao', 0], ['shatang', 0]], [$holds('tulou', [191]), $holds('tulou', [192, 20]), $holds('tulou', [192, 19])], 'three in four hold nothing; of the rest, most the thing sold, a few the thing found');
same([[null, 0], [null, 0]], [$holds('fuchong', [255, 0]), $holds('fuchong', [255, 255])], 'one of a mountain where nothing grows holds nothing, the rolls made all the same');
same(['mihu', 'yupei'], [$holds('xingxing', [192, 0])[0], $holds('xingxing', [192, 20])[0]], '狌狌 of 招摇之山: 迷榖, or 育沛');
// One of a trial's or a duel's beasts holds what its entry says.
$w = new World(['field' => \Shanhai\World\Map::fromArray(['id' => 'field', 'name' => 'Field', 'rows' => ['..']])], 'field', 0, 0, Dir::Right, $book, \Shanhai\Battle\Rng::seeded(1));
$w->join('xingxing', 20);
$w->run([['duel', '猎户', [['fuchong', 3], ['baiyuan', 4, 'baigao']]]]);
same([null, 'baigao'], array_map(fn($f) => $f->item, $w->battle->battle->parties[1]), 'one of a duel\'s beasts holds what its entry says');
check(throws(fn() => $w->duel('猎户', [['fuchong', 3, 'seal']])) && throws(fn() => \Shanhai\Script\Vm::check([['duel', '猎户', [['fuchong', 3, 4]]]])), 'a thing that cannot be held, or what is no thing, is refused');
