<?php
// A battle as the player goes through it: the lines, the menu and its lists, fleeing, and a battle begun by a script.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\{Data, Text};
use Shanhai\Battle\{Battle, Fighter, Formulas, Rng, Screen};
use Shanhai\World\{Controls, Dir, Map, Pen, World};

$book = ['beasts' => Data::beasts(), 'learnsets' => Data::learnsets(), 'moves' => Data::moves(), 'elements' => Data::elements(), 'items' => Data::items(),
    'rest' => ['map' => 'hut', 'x' => 1, 'y' => 0, 'dir' => 'up', 'script' => [['say', 'awake']]]];
$fighter = fn(string $id, int $level) => new Fighter($id, $book['beasts'][$id], $level, $book['learnsets'][$id], $book['moves']);
$screen = function (array $mine, array $wild, Rng $rng, array &$bag = []) use ($book, $fighter): Screen {
    $party = array_map(fn(array|Fighter $x) => $x instanceof Fighter ? $x : $fighter(...$x), $mine);
    return new Screen(new Battle([$party, [$fighter(...$wild)]], $book['elements'], $book['moves'], $rng, [true, false]), $book['moves'], $rng, $book['items'], $bag);
};
$confirm = new Controls(confirm: true);
$cancel = new Controls(cancel: true);
$none = new Controls();
$down = new Controls(Dir::Down);
$up = new Controls(Dir::Up);

// The lines it opens with, then the menu.
$s = $screen([['lushu', 5], ['xuangui', 5]], ['xingxing', 4], Rng::seeded(7));
same(['text', '野生的狌狌出现了！'], [$s->phase, $s->line], 'it opens telling what appeared');
$s->step($none);
same('野生的狌狌出现了！', $s->line, 'a line stays until confirm');
$s->step($confirm);
same('去吧，鹿蜀！', $s->line, 'then the player\'s beast is sent');
$s->step($confirm);
same(['menu', null, ['战斗', '异兽', '背包', '逃跑'], 0], [$s->phase, $s->line, $s->items(), $s->cursor->at], 'then the menu, on its first entry');

// The cursor moves when a direction is pressed, once, and stays inside the list.
$s->step($down);
$s->step($down);
same(1, $s->cursor->at, 'holding a direction moves the cursor once');
$s->step($none);
$s->step($down);
$s->step($none);
$s->step($down);
$s->step($none);
$s->step($down);
same(3, $s->cursor->at, 'the cursor stops at the last entry');
$s->step($none);
$s->step($up);
$s->step($none);
$s->step($up);
same(1, $s->cursor->at, 'and goes back up');

// The party's list, and what cannot be sent.
$s->step($confirm);
same(['party', 0, 2], [$s->phase, $s->cursor->at, count($s->items())], 'the second entry opens the party\'s list');
check(str_starts_with($s->items()[0], '鹿蜀  Lv5  '), 'which tells each beast\'s name, level and hp: ' . $s->items()[0]);
$s->step($confirm);
same(['text', '鹿蜀已经在场上了。'], [$s->phase, $s->line], 'the beast that is out cannot be sent');
$s->step($confirm);
same('menu', $s->phase, 'and the menu is back');
same(0, $s->battle->turns, 'no turn was taken');

// Cancel leaves a list for the menu.
$s->step($confirm);
same('moves', $s->phase, 'the first entry opens the beast\'s moves');
same(count($s->battle->fighter(0)->moves), count($s->items()), 'all of them');
check(str_starts_with($s->items()[0], '冲撞  35/35'), 'each with its uses left: ' . $s->items()[0]);
$s->step($cancel);
same('menu', $s->phase, 'cancel goes back to the menu');
$s->step($cancel);
same('menu', $s->phase, 'where it does nothing');

// A turn: what is shown changes with the line that tells it, and ends as the battle stands.
$s->step($confirm);
$before = [$s->shown[0]['hp'], $s->shown[1]['hp']];
$s->step($confirm);
same(1, $s->battle->turns, 'choosing a move takes the turn');
same('text', $s->phase, 'and its lines are read');
$seen = [];
while ($s->phase === 'text' && !$s->done) {
    $seen[] = $s->line;
    $s->step($confirm);
}
check(in_array('鹿蜀使出了冲撞！', $seen, true), 'the player\'s move is told: ' . implode(' ', $seen));
check(count(array_filter($seen, fn(string $l) => str_starts_with($l, '野生的狌狌使出了'))) === 1, 'and the wild beast\'s, by its name');
same([$s->battle->fighter(0)->hp, $s->battle->fighter(1)->hp], [$s->shown[0]['hp'], $s->shown[1]['hp']], 'after the lines, what is shown is how the battle stands');
check($s->shown[1]['hp'] < $before[1], 'the wild beast was hurt');

// A switch takes the turn.
$s->step($none);
$s->step($down);
$s->step($confirm);
$s->step($none);
$s->step($down);
$s->step($confirm);
same([2, 1, '去吧，旋龟！'], [$s->battle->turns, $s->battle->active[0], $s->line], 'sending another beast takes the turn, and it is told first');
same(1, $s->shown[0]['which'], 'the line shows the beast sent');

// Fleeing (docs/numbers.md 11.4): the faster always gets away.
$s = $screen([['xingxing', 20]], ['lu', 5], Rng::scripted([]));
foreach ([$confirm, $confirm, $down, $none, $down, $none, $down, $confirm] as $c) {
    $s->step($c);
}
same(['fled', '顺利逃走了！', false], [$s->outcome, $s->line, $s->done], 'the faster beast flees with no roll');
$s->step($confirm);
check($s->done, 'the battle is done when its last line is read');
check(throws(fn() => $s->step($confirm)), 'and takes no more steps');

// The slower flees on a roll: 鯥 (speed 35) at level 5 from 狌狌 (speed 85) at level 20.
$slow = $fighter('lu', 5)->stats['spe'];
$fast = $fighter('xingxing', 20)->stats['spe'];
$limit = intdiv($slow * 32, intdiv($fast, 4));
check($limit < 225, "the limit of the first try leaves room to fail: $limit");
$rng = Rng::scripted([$limit + 1, 0]); // the flee fails; the wild beast uses its first move
$s = $screen([['lu', 5]], ['xingxing', 20], $rng);
$s->battle->fighter(1)->moves = [['id' => 'swift', 'pp' => 5]]; // 善走 alone, which asks for no roll
foreach ([$confirm, $confirm, $down, $none, $down, $none, $down, $confirm] as $c) {
    $s->step($c);
}
same([null, '逃不掉！', 1], [$s->outcome, $s->line, $s->battle->turns], 'a roll over the limit fails, and the turn is the wild beast\'s');
// docs/numbers.md 19: the slower, holding what is never lost, flees with no roll.
$sure = $fighter('lu', 5);
$sure->item = 'mihu';
$away = $screen([$sure], ['xingxing', 20], Rng::scripted([]));
foreach ([$confirm, $confirm, $down, $none, $down, $none, $down, $confirm] as $c) {
    $away->step($c);
}
same('fled', $away->outcome, 'one holding what is never lost flees with no roll');
$s->step($confirm);
same('野生的狌狌使出了善走！', $s->line, 'which uses a move');
$s->step($confirm);
same('野生的狌狌的速度大幅提高了！', $s->line, 'and a stat that rises two stages is told so');
same(0, $rng->left(), 'every roll was asked for');
$s->step($confirm);
$second = intdiv($slow * 32, intdiv($s->battle->fighter(1)->stat('spe'), 4)) + 30; // against the speed it has now, two stages up
$rng->more([$second]);
foreach ([$down, $none, $down, $none, $down, $confirm] as $c) {
    $s->step($c);
}
same('fled', $s->outcome, 'the second try adds 30 to the limit, and a roll that is not over it flees');

// A pass does nothing, but poison still takes its part.
$b = new Battle([[$fighter('lushu', 50)], [$fighter('xuangui', 50)]], $book['elements'], $book['moves'], Rng::scripted([]));
$b->fighter(0)->status = 'poison';
$b->turn(['pass', 0], ['pass', 0]);
same([['hurt', 0, intdiv($b->fighter(0)->maxHp, 8), 'poison']], $b->log, 'a side that passes does nothing and is still poisoned');

// Battles played through with any choices: every event has its line, every line fits the box, and what is shown ends true.
$ids = array_keys($book['beasts']);
$lines = [];
$outcomes = [];
for ($seed = 1; $seed <= 150; $seed++) {
    $pick = Rng::seeded($seed * 7919);
    $any = fn(int $n) => $pick->byte() % $n;
    $mine = [[$ids[$any(count($ids))], 40], [$ids[$any(count($ids))], 40]];
    $bag = ['seal' => 2];
    $s = $screen($mine, [$ids[$any(count($ids))], 40], Rng::seeded($seed), $bag);
    for ($steps = 0; !$s->done && $steps < 4000; $steps++) {
        if ($s->phase === 'text') {
            $lines[$s->line] = true;
            $s->step($confirm);
        } elseif ($any(3) === 0) {
            $s->step($any(8) === 0 ? $cancel : $confirm);
        } else {
            $s->step($any(2) === 0 ? $down : $none);
        }
    }
    check($s->done, "battle $seed comes to an end");
    $outcomes[$s->outcome] = true;
}
ksort($outcomes);
same(['caught', 'fled', 'lost', 'won'], array_keys($outcomes), 'they end each way');
foreach (array_keys($lines) as $line) {
    check(count(Text::wrap($line, Text::columns())) <= Text::LINES, "the line fits the box: $line");
}
foreach (['睡着了！', '混乱了！', '麻痹了！', '中毒了！', '效果拔群！', '效果不太好……', '击中了要害！', '没有打中！', '倒下了！', '提高了！', '降低了！', '恢复了体力！', '醒了过来！'] as $part) {
    check(array_filter(array_keys($lines), fn(string $l) => str_contains($l, $part)) !== [], "among their lines: $part");
}

// When the player's beast falls, the next is chosen: the list cannot be left, and a fallen beast cannot be sent.
$s = $screen([['xingxing', 2], ['lushu', 30]], ['zhu', 50], Rng::seeded(5));
foreach ([$confirm, $confirm, $confirm, $confirm] as $c) { // the two lines; the menu's first entry; the first move
    $s->step($c);
}
while ($s->phase === 'text') {
    $last = $s->line;
    $s->step($confirm);
}
same(['狌狌倒下了！', 'party', true, null], [$last, $s->phase, $s->battle->awaits(0), $s->outcome], 'the beast falls, and the party\'s list asks for the next');
check(str_ends_with($s->items()[0], '  倒'), 'which marks the fallen: ' . $s->items()[0]);
$s->step($cancel);
same('party', $s->phase, 'the list cannot be left');
$s->step($confirm);
same('狌狌已经没有力气战斗了。', $s->line, 'the fallen beast cannot be sent');
$s->step($confirm);
same('party', $s->phase, 'and the list asks again');
$s->step($down);
$s->step($confirm);
same(['去吧，鹿蜀！', 1, 1], [$s->line, $s->shown[0]['which'], $s->battle->turns], 'the one chosen is sent, between turns');
$s->step($confirm);
same('menu', $s->phase, 'and the menu is back');

// A script's battle holds the controls until it is over; then the script goes on. The party keeps what the battle left it.
$maps = ['field' => Map::fromArray(['id' => 'field', 'name' => 'Field', 'rows' => ['___']]), 'hut' => Map::fromArray(['id' => 'hut', 'name' => 'Hut', 'rows' => ['___']])];
$w = new World($maps, 'field', 0, 0, Dir::Down, $book, Rng::seeded(3));
check(throws(fn() => $w->fight('xingxing', 4)), 'a battle with no beast in the party is refused');
$w->join('lushu', 30);
$w->run([['battle', 'xingxing', 28], ['say', 'after']]);
check($w->battle !== null && $w->busy(), 'the script began a battle, and waits');
same(null, $w->dialog, 'its next line does not show yet');
$w->step(new Controls(Dir::Right));
same([0, false], [$w->player->x, $w->player->moving], 'the player does not walk during a battle');
$w->step(new Controls(menu: true));
same(null, $w->menu, 'nor open the party\'s screen');
for ($steps = 0; $w->battle !== null && $steps < 2000; $steps++) {
    $w->step($steps % 2 === 0 ? $confirm : $none); // every list's first entry: the first move
}
same([null, 'won', 'after'], [$w->battle, $w->lastBattle, $w->dialog], 'when it is won the script goes on');
$mine = $w->party[0];
check($mine->hp > 0 && $mine->hp < $mine->maxHp, "the beast keeps the hp the battle left it: {$mine->hp} of {$mine->maxHp}");
check($mine->moves[0]['pp'] < $book['moves'][$mine->moves[0]['id']]['pp'], 'and the uses its move has left');
same(0, array_sum(array_map('abs', $mine->stages)), 'its stages end with the battle');
$w->step($confirm);
check(!$w->busy(), 'and the script ends');
$w->run([['rest']]);
same([$mine->maxHp, $book['moves'][$mine->moves[0]['id']]['pp']], [$mine->hp, $mine->moves[0]['pp']], 'rest makes the party whole');

// A battle lost: the script is dropped, the player wakes where the game rests them, the party whole.
$w->join('xingxing', 2);
[$w->party[0], $w->party[1]] = [$w->party[1], $w->party[0]];
$w->party[1]->hp = 1;
$w->run([['battle', 'zhu', 60], ['say', 'after']]);
same('去吧，狌狌！', (function () use ($w, $confirm) { $w->step($confirm); return $w->battle->line; })(), 'the first of the party begins the battle');
for ($steps = 0; $w->battle !== null && $steps < 4000; $steps++) {
    $w->step($steps % 2 === 0 ? $confirm : ($steps % 4 === 1 ? $down : $none));
}
same(['lost', 'field', [0, 0], Dir::Down, 'awake'], [$w->lastBattle, $w->map->id, [$w->player->x, $w->player->y], $w->player->dir, $w->dialog], 'lost: the player wakes where the party last rested, to what data says');
same([true, true], array_map(fn($f) => $f->hp === $f->maxHp, $w->party), 'the party whole');
$w->step($confirm);
same(['慌乱之中丢了1500贝。', 1500], [$w->dialog, $w->money], 'and half the money gone (docs/numbers.md 17)');
$w->step($confirm);
check(!$w->busy() && $w->dialog === null, 'the script that began the battle does not go on');
$w->party[0]->hp = $w->party[1]->hp = 0;
check(throws(fn() => $w->fight('xingxing', 4)), 'a battle with no beast that can fight is refused');
$w->party[1]->hp = 5;
$w->fight('xingxing', 4);
same(1, $w->battle->battle->active[0], 'a battle begins with the first beast that can fight');
check(throws(fn() => (new World($maps, 'field', 0, 0, Dir::Down))->join('xingxing', 4)), 'a world made without the tables has no beasts');

// The menu the menu button opens: the party's screen and the bag. In the party's, two picked change places.
$w = new World($maps, 'field', 0, 0, Dir::Down, $book, Rng::seeded(3));
$menu = new Controls(menu: true);
$w->step($menu);
same(['背包'], $w->menu->items($w->party, $w->items, $book['items']), 'with no beast the menu has only the bag');
$w->step($confirm);
same(['bag', ['（空）']], [$w->menu->phase, $w->menu->items($w->party, $w->items, $book['items'])], 'which, empty, says so');
$w->step($cancel);
$w->step($cancel);
same(null, $w->menu, 'cancel goes back, and from the first list closes the menu');
$w->join('lushu', 5);
$w->join('xuangui', 7);
$w->join('guanguan', 9);
$w->party[1]->status = 'poison';
$w->run([['give', 'seal', 3]]);
$w->step($menu);
same(['异兽', '背包', '详情'], $w->menu->items($w->party, $w->items, $book['items']), 'with a party, the party\'s screen comes first, and the look at one beast after the bag');
$w->step(new Controls(Dir::Right));
same([0, false], [$w->player->x, $w->player->moving], 'the player does not walk while the menu is open');
$w->step($none);
$w->step($down);
$w->step($confirm);
same(['契印  ×3'], $w->menu->items($w->party, $w->items, $book['items']), 'the bag: a line a kind of thing, with how many');
$w->step($cancel);
$w->step($confirm);
same('party', $w->menu->phase, 'the first entry is the party\'s screen');
same(['鹿蜀  Lv5  ' . $w->party[0]->hp . '/' . $w->party[0]->maxHp, '旋龟  Lv7  ' . $w->party[1]->hp . '/' . $w->party[1]->maxHp . '  毒'], array_slice($w->menu->items($w->party, $w->items, $book['items']), 0, 2), 'a line a beast: name, level, hp, status');
$w->step($confirm);
check(str_starts_with($w->menu->items($w->party, $w->items, $book['items'])[0], '◆ '), 'confirm picks a beast, which is marked');
// What the beasts hold: an entry of the menu once there is a thing to hold; a thing of the bag is given, and taken back.
$holds = Data::newWorld(1);
$holds->join('lushu', 5);
$holds->join('xuangui', 7);
$holds->run([['give', 'tongxiu', 1], ['give', 'seal', 2]]);
$holds->step($menu);
$list = fn() => $holds->menu->items($holds->party, $holds->items, $book['items']);
same(['异兽', '携带', '背包', '详情'], $list(), 'with a thing to hold, the menu has what the beasts hold');
foreach ([$down, $none, $confirm] as $c) {
    $holds->step($c);
}
same(['鹿蜀  Lv5  （空手）', '旋龟  Lv7  （空手）'], $list(), 'the party, each with what it holds');
foreach ([$down, $none, $confirm] as $c) {
    $holds->step($c);
}
same(['give', ['同修玉']], [$holds->menu->phase, $list()], 'confirm on one that holds nothing shows what of the bag can be held: not the seal');
$holds->step($confirm);
same(['tongxiu', ['seal' => 2], '旋龟  Lv7  持：同修玉'], [$holds->party[1]->item, $holds->items, $list()[1]], 'given, it leaves the bag and the beast holds it');
foreach ([$down, $none, $confirm] as $c) {
    $holds->step($c);
}
same([null, 1, 'hold'], [$holds->party[1]->item, $holds->items['tongxiu'], $holds->menu->phase], 'confirm on one that holds a thing takes it back to the bag');

$w->step($down);
$w->step($none);
$w->step($down);
same(2, $w->menu->at(), 'the cursor is the party\'s screen\'s');
$w->step($confirm);
same(['guanguan', 'xuangui', 'lushu'], array_map(fn($f) => $f->id, $w->party), 'confirm on another changes their places');
$w->step($confirm);
$w->step($cancel);
check($w->menu->phase === 'party' && $w->menu->party->picked === null, 'cancel lets go of the one picked');
$w->step($cancel);
same('top', $w->menu->phase, 'then goes back to the first list');
$w->step($confirm);
$w->step($menu);
same(null, $w->menu, 'the menu button closes the menu from anywhere');
for ($i = 0; $i < 3; $i++) {
    $w->join('xingxing', 2);
}
check(throws(fn() => $w->join('xingxing', 2)), 'a party holds six');

// A seal (docs/numbers.md 8). 狌狌 (catch rate 190) at level 4, whole: 17 hp; 3M = 51, 2H = 34; 190 × 17 ÷ 51 = 63.
$open = [$confirm, $confirm, $down, $none, $down, $confirm]; // the two lines; the menu's third entry
$bag = [];
$s = $screen([['lushu', 5]], ['xingxing', 4], Rng::scripted([]), $bag);
same(17, $s->battle->fighter(1)->maxHp, '狌狌 at level 4 has 17 hp');
foreach ($open as $c) {
    $s->step($c);
}
same(['text', '背包里什么也没有。'], [$s->phase, $s->line], 'an empty bag says so');
$bag = ['jade' => 1, 'seal' => 2];
$things = $book['items'] + ['jade' => ['name' => '玉']];
$rng = Rng::scripted([63]);
$s = new Screen(new Battle([[$fighter('lushu', 5)], [$fighter('xingxing', 4)]], $book['elements'], $book['moves'], $rng, [true, false]), $book['moves'], $rng, $things, $bag);
foreach ($open as $c) {
    $s->step($c);
}
same(['bag', ['玉  ×1', '契印  ×2  成功率 25%']], [$s->phase, $s->items()], 'the bag\'s list; beside the seal, how likely it is to bind: 64 bytes of 256');
$s->step($confirm);
same(['玉在这里用不上。', ['jade' => 1, 'seal' => 2], 0], [$s->line, $bag, $s->battle->turns], 'a thing of no use in battle is not used');
foreach ([$confirm, $none, $down, $none, $down, $confirm, $none, $down, $confirm] as $c) { // back to the menu; the bag; its second
    $s->step($c);
}
same(['使用了契印！', ['jade' => 1, 'seal' => 1]], [$s->line, $bag], 'a seal is used: one fewer in the bag');
$s->step($confirm);
same(['结契成功！狌狌成了你的同伴！', 'caught', 'xingxing', 0, 0], [$s->line, $s->outcome, $s->caught?->id, $s->battle->turns, $rng->left()], 'a byte of 63, not over the 63 it needs, binds it; no turn is taken');
$s->step($confirm);
check($s->done, 'and the battle is done');

// A byte over fails. The seal seems to hold while bytes are under the limit, 191 for a catch that needs 63, three times at most.
foreach ([[[64, 191], 0, '啊，狌狌挣脱了！'], [[64, 190, 191], 1, '可惜，还以为结契了！'], [[64, 0, 0, 191], 2, '啧，就差一点！'], [[64, 0, 0, 0], 3, '太可惜了，只差最后一下！']] as [$rolls, $held, $line]) {
    $bag = ['seal' => 1];
    $rng = Rng::scripted([...$rolls, 0]); // then the wild beast's move: its first
    $s = $screen([['lushu', 30]], ['xingxing', 4], $rng, $bag);
    $wild = $s->battle->fighter(1);
    $wild->moves = [['id' => 'swift', 'pp' => 1]]; // a move that asks for no roll, at the first place
    foreach ($open as $c) {
        $s->step($c);
    }
    $s->step($confirm);
    $s->step($confirm);
    same([$line, [], null, 1, 0], [$s->line, $bag, $s->outcome, $s->battle->turns, $rng->left()], "it seems to hold $held times, then breaks: the last seal is gone, and the turn is the wild beast's");
}

// Bound, the wild beast joins the party as the battle left it; a full party cannot bind.
$w = new World($maps, 'field', 0, 0, Dir::Down, $book, Rng::scripted([136, 136, 0, 136, 136, 0])); // the given beast's individual values; the wild beast holds nothing, and its values; the seal binds
$w->join('lushu', 30);
$w->run([['give', 'seal', 1], ['battle', 'xingxing', 4], ['say', 'after']]);
$w->battle->battle->fighter(1)->hp = 7;
foreach ([...$open, $confirm, $confirm, $confirm] as $c) {
    $w->step($c);
}
same([null, 'caught', ['lushu', 'xingxing'], 7, [], 'after'], [$w->battle, $w->lastBattle, array_map(fn($f) => $f->id, $w->party), $w->party[1]->hp, $w->items, $w->dialog], 'it joins the party with the hp it had, the seal is spent, and the script goes on');
$w->step($confirm);
// With six in the party the beast bound goes to the pen's current box, first in it and whole, and the player is told
// when that fills the box; with the box full too no seal is used (docs/numbers.md 16).
$w = new World($maps, 'field', 0, 0, Dir::Down, $book, $rolls = Rng::scripted([...array_fill(0, 2 * World::PARTY, 136), 0, 136, 136, 0]));
for ($i = 0; $i < World::PARTY; $i++) {
    $w->join('lushu', 30);
}
$w->box = 2;
$w->boxes[2] = array_map(fn() => $fighter('xingxing', 2), range(2, Pen::BOX));
$w->run([['give', 'seal', 2], ['battle', 'xingxing', 4]]);
$w->battle->battle->fighter(1)->hp = 7;
foreach ([...$open, $confirm] as $c) {
    $w->step($c);
}
for ($lines = []; $w->battle !== null && count($lines) < 9; $w->step($confirm)) {
    $lines[] = $w->battle->line;
}
same(['使用了契印！', '结契成功！狌狌成了你的同伴！', '队伍满了，狌狌被送到了兽栏的第三栏。', '第三栏满了。到兽栏换一栏之前，没法再结契。'], $lines, 'with six in the party the beast bound is sent to the pen, and the box it fills is told of');
$kept = $w->boxes[2][0];
same([World::PARTY, Pen::BOX, 4, $kept->maxHp, ['seal' => 1]], [count($w->party), count($w->boxes[2]), $kept->level, $kept->hp, $w->items], 'it is first in the current box, whole, and the seal is spent');
$rolls->more([0, 136, 136]);
$w->run([['battle', 'xingxing', 4]]);
foreach ([...$open, $confirm] as $c) {
    $w->step($c);
}
same(['队伍和兽栏的第三栏都满了，没法再结契。', ['seal' => 1]], [$w->battle->line, $w->items], 'with the party and the box both full a seal is not used');

// Experience (docs/numbers.md 7.1, 7.3). 鹿蜀 at level 5 has 135; a 狌狌 of level 4 (base experience 81) gives 81 × 4 ÷ 7 = 46,
// which makes 181: level 6 begins at 179. Its hp at most goes from 23 to 26, and at level 6 it learns 歌谣.
$win = function (Screen $s) use ($confirm, $none): array {
    $lines = [];
    for ($i = 0; !$s->done && $s->phase !== 'forget' && $i < 4000; $i++) {
        if ($s->phase === 'text' && $i % 2 === 0) {
            $lines[] = [$s->line, $s->shown[0]['hp']];
        }
        $s->step($i % 2 === 0 ? $confirm : $none); // every list's first entry: the first move
    }
    return $lines;
};
// The levels moves are learned at are the test's own here, so that data/learnsets.php can change without it.
$grows = fn(int $level) => new Fighter('lushu', $book['beasts']['lushu'], $level, [[1, 'ram'], [1, 'spark'], [6, 'chant'], [11, 'redtail'], [16, 'stripes'], [21, 'flame'], [24, 'dust'], [27, 'drywind'], [30, 'swift'], [34, 'blaze'], [42, 'wildfire'], [46, 'sleepless'], [50, 'scorch']], $book['moves']);
$s = $screen([$grows(5)], ['xingxing', 4], Rng::seeded(11));
$f = $s->battle->parties[0][0];
same([135, 23, 2], [$f->exp, $f->maxHp, count($f->moves)], '鹿蜀 at level 5: 135 experience, 23 hp, two moves');
$lines = $win($s);
$texts = array_column($lines, 0);
same('won', $s->outcome, 'it wins');
same(['鹿蜀获得了46点经验！', '鹿蜀升到了6级！', '鹿蜀学会了歌谣！'], array_slice($texts, -3), 'the lines after the wild beast falls');
same([6, 181, 26], [$f->level, $f->exp, $f->maxHp], 'level 6, 181 experience, 26 hp at most');
same(['hp' => 45, 'atk' => 50, 'def' => 35, 'spe' => 85, 'spc' => 55], $f->statExp, 'each stat\'s experience grows by the fallen\'s base value, the special one by its special attack');
same(3, $lines[count($lines) - 2][1] - $lines[count($lines) - 3][1], 'the hp rises by as much as its most did, with the line that tells the level');
same([$f->hp, 'chant', 30], [$s->shown[0]['hp'], $f->moves[2]['id'], $f->moves[2]['pp']], 'what is shown ends true; the move is its third, with all its uses');
check($s->done, 'and the battle is done');

// docs/numbers.md 18: with a beast holding what shares, the fallen's base values are halved: 81 ÷ 2 = 40, and 40 × 4 ÷ 7 = 22 to
// the one that fought and 22 to the one that holds it, though it did not fight; its base stats (45, 50, 35, 55, 85) the same way.
$held = $fighter('xuangui', 5);
$held->item = 'tongxiu';
$s = $screen([$grows(5), $held], ['xingxing', 4], Rng::seeded(11));
[$f, $before] = [$s->battle->parties[0][0], $held->exp];
$texts = array_column($win($s), 0);
check(in_array('鹿蜀获得了22点经验！', $texts, true) && in_array('旋龟获得了22点经验！', $texts, true), 'each takes half: the one that fought, and the one that holds it');
same([135 + 22, $before + 22], [$f->exp, $held->exp], 'twenty-two each');
same([['hp' => 22, 'atk' => 25, 'def' => 17, 'spe' => 42, 'spc' => 27], ['hp' => 22, 'atk' => 25, 'def' => 17, 'spe' => 42, 'spc' => 27]], [$f->statExp, $held->statExp], 'and half of each base stat, rounded down');
// The one that fought holding it itself takes both halves; one that has fallen holding it shares nothing.
$own = $grows(5);
$own->item = 'tongxiu';
$s = $screen([$own], ['xingxing', 4], Rng::seeded(11));
$win($s);
same(135 + 22 + 22, $own->exp, 'holding it and fighting, a beast takes both halves');
$held = $fighter('xuangui', 5);
[$held->item, $held->hp] = ['tongxiu', 0];
$s = $screen([$grows(5), $held], ['xingxing', 4], Rng::seeded(11));
$win($s);
same([135 + 46, 0], [$s->battle->parties[0][0]->exp, $held->statExp['hp']], 'held by one that has fallen, nothing is halved');

// A beast that knows four moves is asked which to forget.
$name = fn(string $id) => $book['moves'][$id]['name'];
foreach ([[1, 'forgets its second'], [null, 'does not learn it']] as [$slot, $what]) {
    $s = $screen([$grows(20)], ['xingxing', 3], Rng::seeded(11));
    $f = $s->battle->parties[0][0];
    same(['spark', 'chant', 'redtail', 'stripes'], array_column($f->moves, 'id'), 'at level 20 it knows the last four it came to');
    $f->exp = \Shanhai\Battle\Formulas::expAtLevel('mediumSlow', 21) - 1;
    $texts = array_column($win($s), 0);
    same(['forget', 21, false], [$s->phase, $f->level, $s->done], 'at level 21 it comes to a fifth, and the list asks');
    same('鹿蜀想学' . $name('flame') . '，可是已经会四个招式了。要忘掉哪一个？', end($texts), 'after the line that says so');
    same([$name('spark'), $name('chant'), $name('redtail'), $name('stripes'), '不学' . $name('flame')], $s->items(), 'its four moves, and not learning the new one');
    if ($slot === 1) {
        $s->step($down);
        $s->step($confirm);
        same('鹿蜀忘掉了' . $name('chant') . '，学会了' . $name('flame') . '！', $s->line, "it $what");
        same(['spark', 'flame', 'redtail', 'stripes'], array_column($f->moves, 'id'), 'the new move in its place');
        same($book['moves']['flame']['pp'], $f->moves[1]['pp'], 'with all its uses');
    } else {
        $s->step($cancel);
        same('鹿蜀没有学' . $name('flame') . '。', $s->line, "cancel: it $what");
        same(['spark', 'chant', 'redtail', 'stripes'], array_column($f->moves, 'id'), 'its moves as they were');
    }
    check(!$s->done, 'the battle waits for the line');
    $s->step($confirm);
    check($s->done, 'and is done');
}

// Those that fought share: the fallen's base experience and base stats are divided among them first. One that fell gains nothing.
foreach ([false, true] as $fell) {
    $s = $screen([['xingxing', 30], ['lushu', 30]], ['xingxing', 3], Rng::seeded(11));
    foreach ([$confirm, $confirm, $down, $confirm, $none, $down, $confirm] as $c) { // the two lines; the party's list; its second beast
        $s->step($c);
    }
    same(1, $s->battle->active[0], 'the second beast is sent');
    [$first, $second] = $s->battle->parties[0];
    $before = [$first->exp, $second->exp];
    if ($fell) {
        $first->hp = 0;
    }
    $win($s);
    same('won', $s->outcome, 'it wins');
    // two share: 81 ÷ 2 = 40, 40 × 3 ÷ 7 = 17, and of the speed's 85 each has 42; one alone: 81 × 3 ÷ 7 = 34, and 85
    same($fell ? [0, 34, 0, 85] : [17, 17, 42, 42], [$first->exp - $before[0], $second->exp - $before[1], $first->statExp['spe'], $second->statExp['spe']], $fell ? 'the one that fell gains nothing, the other all' : 'both that fought gain, each its share');
}

// Many levels at once: the level is the one the experience makes, and every move on the way is come to, in order.
$f = $grows(5);
[$from, $to] = $f->gain(100000, ['hp' => 0, 'atk' => 0, 'def' => 0, 'spa' => 0, 'spe' => 0]);
same([5, \Shanhai\Battle\Formulas::levelAt('mediumSlow', 100135)], [$from, $to], 'from level 5 to the level 100135 experience makes');
same(47, $to, 'which is level 47');
same(['chant', 'redtail', 'stripes', 'flame', 'dust', 'drywind', 'swift', 'blaze', 'wildfire', 'sleepless'], $f->learnable($from, $to), 'the moves of the levels passed, which it does not know');
check(throws(fn() => $f->learn('chant', $book['moves'], 3)), 'a move cannot take the place of one that is not there');
$f->gain(2000000000, ['hp' => 70000, 'atk' => 0, 'def' => 0, 'spa' => 0, 'spe' => 0]);
same([100, \Shanhai\Battle\Formulas::expAtLevel('mediumSlow', 100), 65535], [$f->level, $f->exp, $f->statExp['hp']], 'level 100, its experience and a stat\'s experience are the most there are');

// The lines about learning fit the box with the longest names there are.
$longest = fn(array $rows) => array_reduce(array_column($rows, 'name'), fn(string $a, string $b) => mb_strwidth($b) > mb_strwidth($a) ? $b : $a, '');
[$beastName, $moveName] = [$longest($book['beasts']), $longest($book['moves'])];
foreach ([$beastName . '想学' . $moveName . '，可是已经会四个招式了。要忘掉哪一个？', $beastName . '忘掉了' . $moveName . '，学会了' . $moveName . '！', '野生的' . $beastName . '的特防大幅提高了！'] as $line) {
    check(count(Text::wrap($line, Text::columns())) <= Text::LINES, "the line fits the box: $line");
}

// A trial: against the beasts of the one who sets it, in their order. It cannot be fled, nor its beasts bound; each
// that falls gives its experience then, half as much again; the other side uses the move that would do most.
$w = new World($maps, 'field', 0, 0, Dir::Down, $book + ['gods' => Data::gods()], Rng::seeded(4));
$w->join('lushu', 60);
$w->join('xuangui', 5);
$w->run([['give', 'seal', 1], ['trial', '山神', [['xingxing', 3], ['queshan', 4]]], ['say', 'passed']]);
$s = $w->battle;
same(['山神', '山神要考校你！'], [$s->trial, $s->line], 'it opens telling who sets it');
$w->step($confirm);
same('山神派出了狌狌！', $s->line, 'and their first beast');
foreach ([$confirm, $confirm, $down, $none, $down, $none, $down, $confirm] as $c) { // the player's beast; the menu's last entry
    $w->step($c);
}
same(['试炼不能逃！', 0], [$s->line, $s->battle->turns], 'a trial cannot be fled, and trying takes no turn');
foreach ([$confirm, $none, $down, $none, $down, $confirm, $confirm] as $c) { // the menu's third entry; the bag's seal
    $w->step($c);
}
same(['试炼中的异兽不能结契。', ['seal' => 1], 0], [$s->line, $w->items, $s->battle->turns], 'nor its beasts bound: the seal is kept');
$before = $w->party[0]->exp;
$lines = [];
foreach ([$confirm, $confirm, $confirm] as $c) { // the menu's first entry; the first move
    $w->step($c);
}
while ($s->phase === 'text' && !$s->done) {
    $lines[] = $s->line;
    $w->step($confirm);
}
// 狌狌's base experience is 81: 81 × 3 ÷ 7 = 34, and half as much again, 51
check(in_array('狌狌倒下了！', $lines, true) && !in_array('野生的狌狌倒下了！', $lines, true), 'their beast is not called wild: ' . implode(' ', $lines));
same(['鹿蜀获得了51点经验！', '山神派出了鹊山之神！'], array_slice($lines, array_search('狌狌倒下了！', $lines, true) + 1, 2), 'when it falls its experience is given, half as much again, and their next comes out');
same([51, null, 1], [$w->party[0]->exp - $before, $s->outcome, $s->battle->active[1]], 'the battle goes on against the next');
same('queshan', $s->battle->fighter(1)->id, 'which is a god, fighting as a beast does');
same(Data::gods()['queshan']['moves'], array_column($s->battle->fighter(1)->moves, 'id'), 'with the moves its entry gives it');
// Their move: the one that would do most to the beast that is out.
$theirs = $s->battle->fighter(1);
$would = array_map(fn(int $i) => $s->battle->estimate($theirs, $w->party[0], $i), array_keys($theirs->moves));
$best = array_search(max($would), $would, true);
check(max($would) > 0 && count(array_keys($would, max($would), true)) === 1, 'one of the god\'s moves would do most to 鹿蜀: ' . implode(' ', array_map('intval', $would)));
$uses = array_column($theirs->moves, 'pp');
$w->step(new Controls());
foreach ([$down, $confirm, $none, $down, $confirm] as $c) { // the menu's second entry; the party's second beast: a turn the god moves in
    $w->step($c);
}
$used = array_keys(array_filter(array_column($theirs->moves, 'pp'), fn(int $pp, int $i) => $pp < $uses[$i], ARRAY_FILTER_USE_BOTH));
same([1, [$best]], [$s->battle->active[0], $used], 'and it is the one the god uses');
for ($i = 0; $w->battle !== null && $i < 4000; $i++) {
    if ($s->phase === 'party') {
        $w->step($i % 2 === 0 ? $confirm : $none); // 鹿蜀 again, when 旋龟 falls
    } else {
        $w->step($i % 2 === 0 ? $confirm : $none);
    }
    if ($w->battle !== null && $s->phase === 'text') {
        $lines[] = $s->line;
    }
}
check(in_array('通过了山神的试炼！', $lines, true), 'when their last falls the trial is passed');
// docs/numbers.md 17: a god pays 25 a level of its last beast, four times over: 4 × 25 × 4 = 400.
check(in_array('得到了400贝。', $lines, true) && $w->money === Formulas::START_MONEY + 400, 'and it pays: a hundred for each level of their last');
same([null, 'won', 'passed'], [$w->battle, $w->lastBattle, $w->dialog], 'and the script that set it goes on');

// A trial lost is a battle lost.
$w = new World($maps, 'field', 0, 0, Dir::Down, $book + ['gods' => Data::gods()], Rng::seeded(4));
$w->join('xingxing', 2);
$w->run([['trial', '山神', [['queshan', 50]]], ['say', 'passed']]);
for ($i = 0; $w->battle !== null && $i < 4000; $i++) {
    $w->step($i % 2 === 0 ? $confirm : $none);
}
same(['lost', 'hut', 'awake'], [$w->lastBattle, $w->map->id, $w->dialog], 'lost: the player wakes where the game rests them, and the script is dropped');
check(throws(fn() => \Shanhai\Script\Vm::check([['trial', '山神', []]])) && throws(fn() => \Shanhai\Script\Vm::check([['trial', '山神', [['xingxing']]]])), 'a trial with no beasts, or a beast with no level, is refused');

// One met on the road: seen from straight ahead within their sight, the player is challenged; the battle goes by a
// trial's rules and is told as a challenge; beaten, they watch no more.
$road = Map::fromArray(['id' => 'road', 'name' => 'road', 'rows' => ['.....', '=====', '.....'], 'npcs' => [['foe0', 2, 0, 'down', 'foe', [
    ['ifFlag', 'beat', 'after'], ['say', 'halt'], ['duel', '猎户', [['xingxing', 2]]], ['setFlag', 'beat'], ['say', 'well'], ['end'], ['label', 'after'], ['say', 'again'],
]]], 'watch' => [['foe0', 2, 'beat']]]);
$w = new World(['road' => $road, 'hut' => $maps['hut']], 'road', 0, 1, Dir::Right, $book, Rng::seeded(3));
$right = new Controls(Dir::Right);
$walk = function () use ($w, $right, $none): void {
    for ($i = 0; $i < World::WALK_STEPS + 2 && !$w->busy(); $i++) {
        $w->step($right);
    }
    $w->step($none);
};
$walk();
$walk();
same([[2, 1], false], [[$w->player->x, $w->player->y], $w->busy()], 'with no beast the player passes unseen');
$w->enter('road', 0, 1, Dir::Right);
$w->join('lushu', 30);
$walk();
check(!$w->busy(), 'beside their line of sight nothing happens');
$walk();
same([[2, 1], true, 'halt', Dir::Up], [[$w->player->x, $w->player->y], $w->busy(), $w->dialog, $w->player->dir], 'stepping into it, the player is stopped, turned to them, and hailed');
$w->step($confirm);
same(['猎户', true, '猎户向你挑战！'], [$w->battle->trial, $w->battle->duel, $w->battle->line], 'the battle is theirs, told as a challenge');
for ($i = 0; $w->battle !== null && $i < 400; $i++) {
    $w->step($i % 2 === 0 ? $confirm : $none);
}
same(['won', true, 'well'], [$w->lastBattle, isset($w->flags['beat']), $w->dialog], 'won, they are beaten and say so');
// docs/numbers.md 17: one met on the road pays 8 a level of their last beast, four times over: 4 × 8 × 2 = 64.
same(Formulas::START_MONEY + 64, $w->money, 'and they pay: thirty-two for each level of their last');
// docs/numbers.md 19: with what doubles the pay held by a beast that was out, twice as much.
$paid = new World($maps, 'field', 0, 0, Dir::Down, $book, Rng::seeded(5));
$paid->join('lushu', 30);
$paid->party[0]->item = 'jigu';
$paid->run([['duel', '猎户', [['xingxing', 2]]]]);
for ($i = 0; $paid->battle !== null && $i < 400; $i++) {
    $paid->step($i % 2 === 0 ? $confirm : $none);
}
same(['won', Formulas::START_MONEY + 128], [$paid->lastBattle, $paid->money], 'held by one that was out, what doubles the pay does');
$w->step($confirm);
$w->enter('road', 0, 1, Dir::Right);
$walk();
$walk();
same([[2, 1], false], [[$w->player->x, $w->player->y], $w->busy()], 'and see the player no more');

// Cues: what a line tells of, for what draws and sounds the battle. A hit comes with the line of the move that made it.
$s = $screen([['lushu', 30]], ['xingxing', 30], Rng::seeded(21));
$s->battle->fighter(0)->moves = $s->battle->fighter(1)->moves = [['id' => 'ram', 'pp' => 5]];
same([null, 1], [$s->cue, $s->lines], 'a line that tells of nothing to show has no cue; the lines are counted');
foreach ([$confirm, $confirm, $confirm, $confirm] as $c) { // the two lines; the menu's first entry; the first move
    $s->step($c);
}
$uses = [];
$read = 0;
while ($s->phase === 'text') {
    $read++;
    if (($s->cue['what'] ?? null) === 'use') {
        $uses[] = $s->cue + ['line' => $s->line];
    }
    $s->step($confirm);
}
same([0, 1], [min(array_column($uses, 'side')), max(array_column($uses, 'side'))], 'each side\'s move has its cue, saying whose it is');
foreach ($uses as $u) {
    check(str_contains($u['line'], '使出了') && in_array($u['hit'], [1 - $u['side'], null], true) && is_bool($u['critical']), 'it comes with the line of the move, and says whom it struck, if any, and whether critically: ' . $u['line']);
}
check(array_filter(array_column($uses, 'hit'), 'is_int') !== [], 'a move that hits says so');
same([null, 2 + $read], [$s->cue, $s->lines], 'with the menu back there is no cue, and every line was counted');
$rng = Rng::scripted([0]);
$bag = ['seal' => 1];
$s = $screen([['lushu', 5]], ['xingxing', 4], $rng, $bag);
foreach ([$confirm, $confirm, $down, $none, $down, $confirm, $confirm] as $c) {
    $s->step($c);
}
same(['使用了契印！', ['what' => 'seal']], [$s->line, $s->cue], 'a seal thrown has its cue');
$s->step($confirm);
same(['what' => 'caught'], $s->cue, 'and so has the beast bound');

// Better seals (docs/numbers.md 22): 狌狌's 190 half as much again is 255: whole, 255 × 17 ÷ 51 = 85, and 86 bytes of 256 bind it.
$bag = ['seal' => 1, 'seal2' => 1, 'seal3' => 1];
$s = $screen([['lushu', 5]], ['xingxing', 4], Rng::scripted([85]), $bag);
foreach ($open as $c) {
    $s->step($c);
}
same(['契印  ×1  成功率 25%', '玉契印  ×1  成功率 33%', '金契印  ×1  成功率 33%'], $s->items(), 'each seal shows its own chance');
foreach ([$down, $none, $confirm] as $c) {
    $s->step($c);
}
foreach ([$confirm, $confirm] as $c) {
    $s->step($c);
}
same(['caught', ['seal' => 1, 'seal3' => 1]], [$s->outcome, $bag], 'a byte of 85, which the plain seal\'s 63 would not hold, binds with the better one');

// Medicines in a battle (docs/numbers.md 21): one chosen, then the beast; used, it is gone and the turn is the other side's.
$hurt = $fighter('lushu', 30);
$hurt->hp = 10;
$fallen = $fighter('xuangui', 5);
$fallen->hp = 0;
$bag = ['zhuyu' => 2, 'jibai' => 1];
$rng = Rng::scripted([0]); // the wild beast's choice of move
$s = $screen([$hurt, $fallen], ['xingxing', 2], $rng, $bag);
$s->battle->fighter(1)->moves = [['id' => 'swift', 'pp' => 5]]; // 善走 alone, which asks for no roll
foreach ($open as $c) {
    $s->step($c);
}
$s->step($confirm);
same(['dose', 2], [$s->phase, count($s->items())], 'a medicine opens the party to choose a beast');
$s->step($confirm);
same(['给鹿蜀用了祝余。', 30, ['zhuyu' => 1, 'jibai' => 1], 1], [$s->line, $hurt->hp, $bag, $s->battle->turns], 'used, it restores, is gone from the bag, and the turn is spent');
while ($s->phase === 'text') {
    $s->step($confirm);
}
foreach ([$down, $none, $down, $none, $confirm, $down, $none, $confirm, $confirm] as $c) { // the bag; its second; the first beast
    $s->step($c);
}
same(['用了也没有效果。', ['zhuyu' => 1, 'jibai' => 1], 1], [$s->line, $bag, $s->battle->turns], 'what would do nothing is not used, and no turn is spent');
$rng->more([0]);
foreach ([$confirm, $down, $none, $down, $none, $confirm, $down, $none, $confirm, $down, $none, $confirm] as $c) { // the bag; its second; the second beast
    $s->step($c);
}
same(['给旋龟用了葪柏。', intdiv($fallen->maxHp, 2), ['zhuyu' => 1]], [$s->line, $fallen->hp, $bag], 'one that has fallen is stood up with half its hp');

// Medicines from the menu's bag, out of a battle.
$m = Data::newWorld(1);
$m->join('lushu', 30);
$m->party[0]->hp = 10;
$m->run([['give', 'seal', 1], ['give', 'bili', 1]]);
$m->step($menu);
foreach ([$down, $none, $confirm, $confirm] as $c) { // the bag; its first, the seal
    $m->step($c);
}
same('bag', $m->menu->phase, 'a seal is only looked at there');
foreach ([$down, $none, $confirm] as $c) {
    $m->step($c);
}
same(['use', ['鹿蜀  Lv30  10/' . $m->party[0]->maxHp]], [$m->menu->phase, $m->menu->items($m->party, $m->items, $book['items'])], 'a medicine opens the party');
$m->step($confirm);
same(['给鹿蜀用了萆荔。', 60, ['seal' => 1], 'bag'], [$m->menu->note, $m->party[0]->hp, $m->items, $m->menu->phase], 'used, it restores and is gone; with no more of it, back to the bag');
$m->step($confirm);
same(null, $m->menu->note, 'confirm goes on');
$m->run([]);

// The seals that go by a condition (docs/numbers.md 22). 鹿蜀 at level 20 against 狌狌 at level 4, under a quarter of it: eight
// times 190 is 255, and whole that is 85. The seal that cannot fail asks for no roll.
$bag = ['sealLevel' => 1, 'sealWater' => 1, 'sealSure' => 1];
$s = $screen([['lushu', 20]], ['xingxing', 4], Rng::scripted([]), $bag);
foreach ($open as $c) {
    $s->step($c);
}
same(['威契印  ×1  成功率 33%', '水契印  ×1  成功率 25%', '天契印  ×1  成功率 100%'], $s->items(), 'the seal by level counts the levels; the seal of the water is a plain one on land; the last cannot fail');
foreach ([$down, $none, $down, $none, $confirm, $confirm, $confirm] as $c) {
    $s->step($c);
}
same(['caught', ['sealLevel' => 1, 'sealWater' => 1]], [$s->outcome, $bag], 'which binds with no roll');
$bag = ['sealWater' => 1];
$rng = Rng::scripted([]);
$s = new Screen(new Battle([[$fighter('lushu', 5)], [$fighter('xingxing', 4)]], $book['elements'], $book['moves'], $rng, [true, false]), $book['moves'], $rng, $book['items'], $bag, null, false, null, 0, 0, true);
foreach ($open as $c) {
    $s->step($c);
}
same(['水契印  ×1  成功率 33%'], $s->items(), 'in a battle begun on the water the rate is three times as much: 190 × 3 is 255 at most, and whole that is 85, not the 63 of the land');

// The look at one beast, and the record of beasts (docs/numbers.md 23, 24).
$d = Data::newWorld(1);
$d->join('lushu', 5);
$f = $d->party[0];
$d->step($menu);
$list = fn() => $d->menu->items($d->party, $d->items, $book['items'], $d->dex, $book['beasts']);
same(['异兽', '背包', '详情', '图鉴'], $list(), 'with a kind seen, the menu has the record');
foreach ([$down, $none, $down, $none, $confirm, $confirm] as $c) {
    $d->step($c);
}
same(sprintf('鹿蜀  Lv5  %s  体力 %d/%d', $book['elements'][$f->species['elements'][0]]['name'], $f->hp, $f->maxHp), $d->menu->note, 'the look at a beast: what it is and how it stands');
$shown = [];
for ($i = 0; $i < 6 && $d->menu->note !== null; $i++) {
    $shown[] = $d->menu->note;
    $d->step($confirm);
}
same(5, count($shown), 'five screenfuls');
check(str_starts_with($shown[1], '攻击 ' . $f->stats['atk'] . '  防御 ') && str_contains($shown[2], '到下一级还差 ') && str_ends_with($shown[2], '空手') && str_starts_with($shown[3], '招式：'), 'its stats; its experience and what it holds; its moves');
same('天分：' . Formulas::talent($f->state()['dvs']) . '  和你：还生分', $shown[4], 'and the words for its individual values and for its bond, which begins at 70');
same(['万里挑一', '出众', '出众', '寻常', '寻常', '平平'], array_map(fn(int $each) => Formulas::talent(['atk' => $each, 'def' => $each, 'spe' => $each, 'spc' => $each]), [12, 11, 10, 9, 6, 5]), 'the words go by what the four add up to: 48, 38 and 23 and over');
foreach ([$cancel, $down, $none, $down, $none, $down, $none, $confirm] as $c) { // back to the first list; the record, its fourth
    $d->step($c);
}
same(['dex', count($book['beasts'])], [$d->menu->phase, count($list())], 'the record lists every kind there is');
$at = array_search('lushu', array_keys($book['beasts']), true);
same([sprintf('%03d  鹿蜀  已结契', $at + 1), '001  ？？？'], [$list()[$at], $list()[0]], 'one bound is named and marked; one not seen is not named');
$d->step(new Controls(Dir::Right));
same(8, $d->menu->at(), 'right turns a page');
$d->step($none);
$d->step(new Controls(Dir::Left));
$d->step($confirm);
same('还没有见过。', $d->menu->note, 'of a kind not seen nothing is told');
$dex = new \Shanhai\World\Dex();
$dex->see('xingxing');
same(1, count($dex->entry($book['beasts'], $book['elements'], 0)), 'of a kind only seen: its name, what it is and where it lives');
check(str_contains($dex->entry($book['beasts'], $book['elements'], 0)[0], '出没：招摇之山') && !str_contains($dex->entry($book['beasts'], $book['elements'], 0)[0], '属性'), 'and not its elements');
$dex->bind('xingxing');
$told = $dex->entry($book['beasts'], $book['elements'], 0);
check(count($told) >= 2 && str_contains($told[0], '属性：') && $told[1] === $book['beasts']['xingxing']['looks'], 'of one bound: its elements and how the classic says it looks');
// Kinds are seen in a battle, the other side's all of them, and bound when one joins.
$d->run([]);
$b = Data::newWorld(1);
$b->join('lushu', 30);
$b->run([['duel', '猎户', [['xingxing', 2], ['baiyuan', 2]]]]);
same([['baiyuan', 'lushu', 'xingxing'], ['lushu']], [(function (array $ids) { sort($ids); return $ids; })(array_keys($b->dex->seen)), array_keys($b->dex->caught)], 'those of a battle are seen; the one that joined is bound');

// The bond in a battle (docs/numbers.md 25): a level brings a beast closer; close enough, it comes to the move of the bond, once;
// one that falls is less close. 鹿蜀 at level 5 with 134 experience needs one more for level 6.
$close = $grows(5);
$close->bond = 218;
$s = $screen([$close], ['xingxing', 4], Rng::seeded(11));
$texts = array_column($win($s), 0);
same([true, true, true], [$close->bond >= 220, $close->offered, in_array(Screen::BOND_MOVE, array_column($close->moves, 'id'), true) || in_array('鹿蜀想学同心，可是已经会四个招式了。要忘掉哪一个？', $texts, true)], 'at 220 it comes to the move of the bond');
$again = $grows(5);
[$again->bond, $again->offered] = [230, true];
$s = $screen([$again], ['xingxing', 4], Rng::seeded(11));
$win($s);
check(!in_array(Screen::BOND_MOVE, array_column($again->moves, 'id'), true), 'put to it once already, it is not put again');
$weak = $fighter('xingxing', 2);
$weak->hp = 1;
$s = $screen([$weak, $fighter('lushu', 30)], ['lushu', 40], Rng::seeded(3));
foreach ([$confirm, $confirm, $confirm, $confirm] as $c) { // the two lines; the menu's first; its first move
    $s->step($c);
}
same(Formulas::BOND_BASE - 5, $weak->bond, 'one that falls to a beast thirty levels or more over it is five less close');
// The seal of kinship binds as a plain one, and what it binds is close from the first.
$kin = new World($maps, 'field', 0, 0, Dir::Down, $book, Rng::scripted([136, 136, 0, 136, 136, 0]));
$kin->join('lushu', 30);
$kin->run([['give', 'sealKin', 1], ['battle', 'xingxing', 4]]);
foreach ([...$open, $confirm, $confirm, $confirm, $confirm] as $c) {
    $kin->step($c);
}
same([2, Formulas::BOND_SEAL], [count($kin->party), $kin->party[1]->bond ?? null], 'bound with the seal of kinship, a beast begins at a bond of 200');
// Walking: every 512 steps each of the party is one closer; those that go into a god's trial able to fight, three.
$walk = Data::newWorld(1);
$walk->join('lushu', 5);
$walk->steps = Formulas::BOND_STEPS - 1;
for ($i = 0; $i < 4 * World::WALK_STEPS && $walk->steps !== 0; $i++) {
    $walk->step(new Controls($i < 2 * World::WALK_STEPS ? Dir::Down : Dir::Up));
}
same([0, Formulas::BOND_BASE + 1], [$walk->steps, $walk->party[0]->bond], 'the step that makes 512 brings each of the party one closer');
$walk->run([['trial', '山神', [['xingxing', 2]]]]);
same(Formulas::BOND_BASE + 1 + 3, $walk->party[0]->bond, 'going into a god\'s trial, three');
