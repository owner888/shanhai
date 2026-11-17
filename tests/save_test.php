<?php
// Saving and loading: what a beast and the world keep, the file, the slots the menu saves to, the game's own save, the title's list.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\{Data, Save};
use Shanhai\Battle\{Fighter, Formulas, Rng};
use Shanhai\World\{Controls, Dir, Map, Pen, Title, World};

$book = ['beasts' => Data::beasts(), 'learnsets' => Data::learnsets(), 'moves' => Data::moves(), 'elements' => Data::elements(), 'items' => Data::items()];
$dir = sys_get_temp_dir() . '/shanhai-save-test-' . getmypid();
$empty = function () use ($dir): void {
    is_dir($dir) || mkdir($dir);
    array_map('unlink', glob("$dir/*"));
};
$empty();
$file = Save::file($dir, '1');
$cancel = new Controls(cancel: true);
$confirm = new Controls(confirm: true);
$down = new Controls(Dir::Down);
$none = new Controls();

// A beast comes back as it was: grown, hurt, with a status, a move forgotten for another, uses spent.
$f = new Fighter('lushu', $book['beasts']['lushu'], 20, $book['learnsets']['lushu'], $book['moves'], ['atk' => 15, 'def' => 3, 'spe' => 9, 'spc' => 0]);
$f->gain(4000, ['hp' => 45, 'atk' => 50, 'def' => 35, 'spa' => 55, 'spe' => 85]);
$f->learn('flame', $book['moves'], 1);
$f->moves[0]['pp'] = 3;
[$f->hp, $f->status, $f->sleep] = [7, 'sleep', 4];
$back = Fighter::fromState(json_decode(json_encode($f->state()), true), $book['beasts'], $book['learnsets'], $book['moves']);
same($f->state(), $back->state(), 'what is kept of a beast comes back the same, through JSON');
same([$f->level, $f->maxHp, $f->stats], [$back->level, $back->maxHp, $back->stats], 'and so what is made from it: its level, its hp at most, its stats');
$broken = fn(array $change) => throws(fn() => Fighter::fromState(array_replace($f->state(), $change), $book['beasts'], $book['learnsets'], $book['moves']));
check($broken(['id' => 'nobody']), 'a beast there is none of is refused');
check($broken(['hp' => $f->maxHp + 1]), 'more hp than it can have is refused');
check($broken(['moves' => [['id' => 'nothing', 'pp' => 1]]]), 'a move there is none of is refused');
check($broken(['moves' => []]), 'a beast with no move is refused');

// The file: none is null; written, it reads the same; what is not a save of this format throws.
same(null, Save::read($file), 'no file, no saved game');
Save::write($file, ['a' => ['山海', 1]]);
same(['a' => ['山海', 1]], Save::read($file), 'what is written is read');
check(!is_file($file . '.part'), 'nothing is left beside it');
file_put_contents($file, '{"version":0,"state":{}}');
check(throws(fn() => Save::read($file)), 'a file of another format is refused');
file_put_contents($file, 'not json');
check(throws(fn() => Save::read($file)), 'a file that is not JSON is refused');
unlink($file);

// The world: saved from the menu, it comes back where it was, with its flags, bag and party.
$maps = ['field' => Map::fromArray(['id' => 'field', 'name' => 'Field', 'rows' => ['....', '....']]), 'hut' => Map::fromArray(['id' => 'hut', 'name' => 'Hut', 'rows' => ['___']])];
$w = new World($maps, 'field', 0, 0, Dir::Down, $book, Rng::seeded(1), $dir);
$w->join('xuangui', 9);
$w->join('xingxing', 4);
$w->party[0]->hp = 5;
$w->run([['setFlag', 'seen'], ['give', 'seal', 3], ['warp', 'field', 2, 1, 'left']]);
$w->step(new Controls(menu: true));
same(['异兽', '背包', '详情', '图鉴', '存档'], $w->menu->items($w->party, $w->items, $book['items'], $w->dex), 'a game with a file has saving in its menu, last');
foreach ([$down, $none, $down, $none, $down, $none, $down, $confirm] as $c) {
    $w->step($c);
}
same(['记录一  （空）', '记录二  （空）', '记录三  （空）'], $w->menu->items($w->party, $w->items, $book['items']), 'choosing it lists the three slots the player saves to, empty');
$w->step($confirm);
same([null, '已存入记录一。', true], [$w->menu, $w->dialog, is_file($file)], 'an empty one, chosen, is saved to at once; the menu closes and says so');
$w->step($confirm);
check(!$w->busy(), 'and the game goes on');
$w->step(new Controls(menu: true));
foreach ([$down, $none, $down, $none, $down, $none, $down, $confirm] as $c) {
    $w->step($c);
}
check(preg_match('/^记录一  Field  旋龟 Lv9  图鉴 2  \d\d-\d\d \d\d:\d\d$/u', $w->menu->items($w->party, $w->items, $book['items'])[0]) === 1,
    'a slot saved to shows where, who leads, how many kinds are bound and when: ' . $w->menu->items($w->party, $w->items, $book['items'])[0]);
$w->step($confirm);
same(['覆盖记录一原来的内容', '不覆盖'], $w->menu->items($w->party, $w->items, $book['items']), 'one with a save, chosen, asks whether to write over it');
$w->step($down);
$w->step($none);
$w->step($confirm);
same('slots', $w->menu->phase, 'not writing over it goes back to the slots');
$w->step($confirm);
$w->step($cancel);
same('slots', $w->menu->phase, 'and so does cancel');
$w->step($cancel);
$w->step($cancel);
check($w->menu === null && $w->dialog === null, 'nothing is saved by going back');
$state = $w->state();
same(['field', 2, 1, 'left', ['seen'], ['seal' => 3], ['xuangui', 'xingxing'], 5], [$state['map'], $state['x'], $state['y'], $state['dir'], $state['flags'], $state['items'], array_column($state['party'], 'id'), $state['party'][0]['hp']], 'what the world keeps');
same($state, Save::read($file), 'is what the file holds');

$other = new World($maps, 'hut', 0, 0, Dir::Down, $book, Rng::seeded(2), $dir);
$changes = $other->mapChanges;
$other->load('1');
same($state, $other->state(), 'another world, loading it, is the same');
same($changes + 1, $other->mapChanges, 'and what draws it is told of the map');
check(throws(fn() => $other->restore(array_replace($state, ['map' => 'nowhere']))), 'a map there is none of is refused');
// Where the party last rested is kept, and is where a battle lost wakes the player; a place there is none of is refused.
same(null, $state['rest'], 'a game that has rested nowhere keeps no such place');
$other->run([['rest']]);
$p = $other->player;
same([$other->map->id, $p->x, $p->y, $p->dir->value], $other->state()['rest'], 'rest is kept as where it was done');
$again = new World($maps, 'hut', 0, 0, Dir::Down, $book, Rng::seeded(2));
$again->restore($other->state());
same($other->state()['rest'], $again->restAt, 'and comes back');
check(throws(fn() => $other->restore(array_replace($state, ['rest' => ['nowhere', 0, 0, 'up']]))), 'a place of rest on a map there is none of is refused');
// A file of the first format, which had no place of rest, is read as one that rested nowhere.
file_put_contents($file, json_encode(['version' => 1, 'state' => array_diff_key($state, ['rest' => 0, 'boxes' => 0, 'box' => 0, 'money' => 0, 'seen' => 0, 'caught' => 0, 'steps' => 0])]));
same($state, Save::read($file), 'a file of format 1 is read as having rested nowhere');
// The pen is kept: which box is the current one, and each box's beasts. A file of the second format, which had no pen, is read as one with nothing in it.
same([array_fill(0, Pen::BOXES, []), 0], [$state['boxes'], $state['box']], 'a game that has left nothing in the pen keeps fourteen empty boxes');
file_put_contents($file, json_encode(['version' => 2, 'state' => array_diff_key($state, ['boxes' => 0, 'box' => 0, 'money' => 0, 'seen' => 0, 'caught' => 0, 'steps' => 0])]));
same($state, Save::read($file), 'a file of format 2 is read as having nothing in the pen');
// What a beast holds is kept. A file of the fourth format, in which nothing was held, is read as beasts holding nothing.
file_put_contents($file, json_encode(['version' => 4, 'state' => array_replace(array_diff_key($state, ['steps' => 0]), ['party' => array_map(fn(array $f) => array_diff_key($f, ['item' => 0, 'bond' => 0, 'offered' => 0, 'rank' => 0]), $state['party'])])]));
same($state, Save::read($file), 'a file of format 4 is read as beasts holding nothing');
$holding = new World($maps, 'hut', 0, 0, Dir::Down, $book, Rng::seeded(2));
$holding->restore(array_replace($state, ['party' => array_map(fn(array $f) => array_replace($f, ['item' => 'tongxiu']), $state['party'])]));
same('tongxiu', $holding->party[0]->item, 'what a beast holds comes back');
check(throws(fn() => $holding->restore(array_replace($state, ['party' => array_map(fn(array $f) => array_replace($f, ['item' => 'seal']), $state['party'])]))), 'a beast holding what cannot be held is refused');
// The record of beasts is kept. A file of the fifth format, which had none, is read as having seen and bound the kinds it has with it.
same([['xingxing', 'xuangui'], ['xingxing', 'xuangui']], [$state['seen'], $state['caught']], 'a saved game keeps the kinds seen and bound, in order of their ids');
file_put_contents($file, json_encode(['version' => 5, 'state' => array_diff_key($state, ['seen' => 0, 'caught' => 0, 'steps' => 0])]));
same($state, Save::read($file), 'a file of format 5 is read as having seen and bound what it has with it');
check(throws(fn() => $holding->restore(array_replace($state, ['seen' => ['nothing']]))) && throws(fn() => $holding->restore(array_replace($state, ['caught' => ['lushu']]))), 'a kind there is none of, or one bound and not seen, is refused');
// A beast's bond is kept. A file of the sixth format, which had none, is read as beasts with the bond one begins with.
file_put_contents($file, json_encode(['version' => 6, 'state' => array_replace(array_diff_key($state, ['steps' => 0]), ['party' => array_map(fn(array $f) => array_diff_key($f, ['bond' => 0, 'offered' => 0, 'rank' => 0]), $state['party'])])]));
same([Formulas::BOND_BASE, false], [Save::read($file)['party'][0]['bond'], Save::read($file)['party'][0]['offered']], 'a file of format 6 is read as beasts with the bond one begins with');
$holding->restore(array_replace($state, ['party' => array_map(fn(array $f) => array_replace($f, ['bond' => 231, 'offered' => true]), $state['party'])]));
same([231, true], [$holding->party[0]->bond, $holding->party[0]->offered], 'a bond comes back');
same(0, Save::read($file)['steps'], 'and with no steps walked toward the next');
$holding->steps = 300;
same(300, $holding->state()['steps'], 'the steps walked toward the next are kept');
check(throws(fn() => $holding->restore(array_replace($state, ['steps' => Formulas::BOND_STEPS]))), 'steps that would already have counted are refused');
// A rite received is kept (docs/numbers.md 27). A file of the seventh format, which had none, is read as beasts that have received none.
file_put_contents($file, json_encode(['version' => 7, 'state' => array_replace($state, ['party' => array_map(fn(array $f) => array_diff_key($f, ['rank' => 0]), $state['party'])])]));
same(0, Save::read($file)['party'][0]['rank'], 'a file of format 7 is read as beasts that have received no rite');
$risen = Data::newWorld(1);
$risen->join('baiyuan', 30);
$risen->party[0]->rite(1);
$kept = $risen->state();
$again = Data::newWorld(1);
$again->restore($kept);
same([1, '白猿·璋', $risen->party[0]->stats], [$again->party[0]->rank, $again->party[0]->species['name'], $again->party[0]->stats], 'a rite comes back, with the name and the stats it gives');
check(throws(fn() => (function () use ($kept) { $w = Data::newWorld(1); $kept['party'][0]['rank'] = 1; $kept['party'][0]['id'] = 'lushu'; $kept['party'][0]['moves'] = [['id' => 'ram', 'pp' => 1]]; $w->restore($kept); })()), 'a rite on a kind that receives none is refused');
// The money is kept. A file of the third format, which had none, is read as having what a new game begins with.
same(Formulas::START_MONEY, $state['money'], 'a game that has spent and won nothing keeps what it began with');
file_put_contents($file, json_encode(['version' => 3, 'state' => array_diff_key($state, ['money' => 0, 'seen' => 0, 'caught' => 0, 'steps' => 0])]));
same($state, Save::read($file), 'a file of format 3 is read as having what a new game begins with');
$rich = new World($maps, 'hut', 0, 0, Dir::Down, $book, Rng::seeded(2));
$rich->restore(array_replace($state, ['money' => 123456]));
same(123456, $rich->money, 'the money comes back');
check(throws(fn() => $rich->restore(array_replace($state, ['money' => -1]))) && throws(fn() => $rich->restore(array_replace($state, ['money' => Formulas::MAX_MONEY + 1]))), 'money under nothing or over the most there can be is refused');
$penned = new World($maps, 'hut', 0, 0, Dir::Down, $book, Rng::seeded(2));
$penned->restore($state);
$penned->box = 3;
$penned->boxes[3][] = array_pop($penned->party);
$kept = json_decode(json_encode($penned->state()), true);
$again = new World($maps, 'hut', 0, 0, Dir::Down, $book, Rng::seeded(2));
$again->restore($kept);
same([3, ['xingxing'], ['xuangui'], $kept], [$again->box, array_map(fn(Fighter $f) => $f->id, $again->boxes[3]), array_map(fn(Fighter $f) => $f->id, $again->party), $again->state()], 'the pen comes back as it was, through JSON');
check(throws(fn() => $again->restore(array_replace($kept, ['box' => Pen::BOXES]))), 'a current box there is none of is refused');
check(throws(fn() => $again->restore(array_replace($kept, ['boxes' => array_slice($kept['boxes'], 1)]))), 'a pen of thirteen boxes is refused');
check(throws(fn() => $again->restore(array_replace($kept, ['boxes' => array_replace($kept['boxes'], [array_fill(0, Pen::BOX + 1, $kept['boxes'][3][0])])]))), 'a box of twenty-one is refused');
file_put_contents($file, json_encode(['version' => 99, 'state' => $state]));
check(throws(fn() => Save::read($file)), 'a file of a format not known is refused');
Save::write($file, $state);
check(throws(fn() => $other->restore(array_replace($state, ['items' => ['nothing' => 1]]))), 'a thing there is none of is refused');
check(throws(fn() => (new World($maps, 'hut', 0, 0, Dir::Down, $book))->save('1')), 'a world with no folder cannot save');
check(throws(fn() => $other->save('4')), 'nor can one save to a slot there is none of');
$w = new World($maps, 'hut', 0, 0, Dir::Down, $book);
$w->step(new Controls(menu: true));
same(['背包'], $w->menu->items($w->party, $w->items, $book['items']), 'nor has it saving in its menu');

// When a save was made: as the file says; one written before it said, as the file system does.
Save::write($file, $state, 1700000000);
same(1700000000, Save::saved($file), 'a save says when it was made');
file_put_contents($file, json_encode(['version' => 8, 'state' => $state]));
touch($file, 1600000000);
same([1600000000, $state], [Save::saved($file), Save::read($file)], 'one of format 8, which did not, is when the file was written, its state as it was');
same(null, Save::saved(Save::file($dir, '3')), 'an empty slot was never saved');

// The game saves itself (Save::AUTO): onto another map, and before a god's trial; not elsewhere on the same map, nor
// when a battle lost wakes the player, so what was saved before it is kept.
$empty();
$auto = Save::file($dir, Save::AUTO);
$w = new World($maps, 'field', 0, 0, Dir::Down, $book, Rng::seeded(1), $dir);
check(!is_file($auto), 'a world begun saves nothing');
$w->join('xuangui', 9);
$w->run([['warp', 'field', 2, 1, 'left']]);
check(!is_file($auto), 'elsewhere on the same map, nothing');
$w->run([['warp', 'hut', 1, 0, 'down']]);
same('hut', Save::read($auto)['map'] ?? null, 'onto another map, the game saves itself there');
unlink($auto);
$g = Data::newWorld(1, $dir);
$g->run([['giveBeast', 'xuangui', 9]]);
$g->trial('守山神', [['xingxing', 3]]);
same('zhaoyao', Save::read($auto)['map'] ?? null, 'before a trial it saves itself too');
unlink($auto);

// The one save kept before there were slots (save.json) becomes the first slot's.
$empty();
Save::write("$dir/save.json", $state);
Data::newWorld(1, $dir);
same([false, $state], [is_file("$dir/save.json"), Save::read($file)], 'the old save is the first slot\'s');

// At the start, with a game saved, the title's list: go on with the latest, load one, or begin anew.
$empty();
$w = Data::newWorld(1, $dir);
check(!$w->busy() && $w->title === null, 'with no game saved there is no title, and nothing is asked');
$w->run([['giveBeast', 'xuangui', 9], ['warp', 'zhaoyao_hut', 3, 3, 'up']]);
Save::write(Save::file($dir, '2'), $w->state(), 1700000100);
$w->run([['warp', 'zhaoyao', 9, 8, 'down']]);
Save::write(Save::file($dir, Save::AUTO), $w->state(), 1700000000);
$w = Data::newWorld(1, $dir);
check($w->busy() && $w->title !== null, 'a new world with a saved game begins at the title');
same(['继续', '读取记录', '新游戏'], $w->title->items(), 'which offers to go on, to load, or to begin anew');
$w->step($confirm);
same([false, 'zhaoyao_hut', ['xuangui']], [$w->busy(), $w->map->id, array_map(fn(Fighter $f) => $f->id, $w->party)], 'going on loads the save made last, the second here');
$w = Data::newWorld(1, $dir);
$w->step($down);
$w->step($none);
$w->step($confirm);
same(4, count($w->title->items()), 'loading lists the game\'s save and the three of the player\'s');
check(str_starts_with($w->title->items()[0], '自动记录  招摇之山') && $w->title->items()[1] === '记录一  （空）', 'each with where, or that it is empty: ' . $w->title->items()[0]);
$w->step($down);
$w->step($none);
$w->step($confirm);
same('这里没有记录。', $w->title->note, 'an empty one cannot be loaded');
$w->step($confirm);
$w->step($down);
$w->step($confirm);
same([false, 'zhaoyao_hut', ['xuangui']], [$w->busy(), $w->map->id, array_map(fn(Fighter $f) => $f->id, $w->party)], 'one chosen is loaded');
$w = Data::newWorld(1, $dir);
foreach ([$down, $none, $down, $confirm] as $c) {
    $w->step($c);
}
same(['开始新的旅程（记录都还留着）', '返回'], $w->title->items(), 'beginning anew is asked once more');
$w->step($confirm);
same([false, 'zhaoyao', []], [$w->busy(), $w->map->id, $w->party], 'and begins a new game');
check(is_file(Save::file($dir, '2')) && is_file(Save::file($dir, Save::AUTO)), 'the saves are kept until they are saved over');
$empty();
rmdir($dir);
