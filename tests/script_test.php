<?php
// The script interpreter: its checks, flags and jumps, walking actors.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\Script\Vm;
use Shanhai\World\{Controls, Dir, Map, World};

check(throws(fn() => Vm::check([['dance']])), 'an unknown command is refused');
check(throws(fn() => Vm::check([['say']])), 'a missing argument is refused');
check(throws(fn() => Vm::check([['goto', 'nowhere']])), 'a jump to no label is refused');
check(throws(fn() => Vm::check([['face', 'player', 'north']])), 'a direction that is none is refused');
same(['a' => 1], Vm::check([['goto', 'a'], ['label', 'a']]), 'labels are found');

$map = Map::fromArray(['id' => 'room', 'name' => 'Room', 'rows' => ['_____', '_____', '__T__'],
    'npcs' => [['cat', 4, 0, 'left', 'elder', []]]]);
$w = new World(['room' => $map], 'room', 0, 0, Dir::Down);

$w->run([
    ['ifNotFlag', 'seen', 'first'],
    ['say', 'again'],
    ['end'],
    ['label', 'first'],
    ['setFlag', 'seen'],
    ['give', 'jade', 2],
    ['give', 'jade', 1],
    ['giveBeast', 'xingxing', 5],
    ['face', 'cat', 'down'],
    ['move', 'player', 'right', 2],
    ['say', 'done'],
]);
check(isset($w->flags['seen']), 'setFlag');
same(3, $w->items['jade'], 'give adds up');
same([['id' => 'xingxing', 'level' => 5]], $w->party, 'giveBeast');
same(Dir::Down, $w->npcs['cat']->dir, 'face turns an actor');
check($w->busy() && $w->dialog === null && $w->player->moving, 'move starts the walk and waits');
for ($i = 0; $i < 2 * World::WALK_STEPS; $i++) {
    $w->step(new Controls(Dir::Down, true));
}
same([2, 0], [$w->player->x, $w->player->y], 'two tiles, whatever the player holds');
check($w->dialog === null, 'the next command waits for the walk');
$w->step(new Controls());
same('done', $w->dialog, 'then the script goes on');
$w->step(new Controls(confirm: true));
check(!$w->busy(), 'and ends');

$w->run([['ifNotFlag', 'seen', 'first'], ['say', 'again'], ['end'], ['label', 'first'], ['say', 'first']]);
same('again', $w->dialog, 'ifNotFlag falls through once the flag is set');
$w->step(new Controls(confirm: true));

$w->run([['move', 'player', 'down', 2]]);
check(throws(function () use ($w) {
    for ($i = 0; $i < 2 * World::WALK_STEPS; $i++) {
        $w->step(new Controls());
    }
}), 'a scripted walk into a tree is an error, not a walk through it');
