<?php
// A battle as the player goes through it: the lines, the menu and its lists, fleeing, and a battle begun by a script.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\{Data, Text};
use Shanhai\Battle\{Battle, Fighter, Rng, Screen};
use Shanhai\World\{Controls, Dir, Map, World};

$book = ['beasts' => Data::beasts(), 'learnsets' => Data::learnsets(), 'moves' => Data::moves(), 'elements' => Data::elements()];
$fighter = fn(string $id, int $level) => new Fighter($id, $book['beasts'][$id], $level, $book['learnsets'][$id], $book['moves']);
$screen = function (array $mine, array $wild, Rng $rng) use ($book, $fighter): Screen {
    $party = array_map(fn(array $x) => $fighter(...$x), $mine);
    return new Screen(new Battle([$party, [$fighter(...$wild)]], $book['elements'], $book['moves'], $rng), $book['moves'], $rng);
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
same(['menu', null, ['战斗', '异兽', '逃跑'], 0], [$s->phase, $s->line, $s->items(), $s->cursor], 'then the menu, on its first entry');

// The cursor moves when a direction is pressed, once, and stays inside the list.
$s->step($down);
$s->step($down);
same(1, $s->cursor, 'holding a direction moves the cursor once');
$s->step($none);
$s->step($down);
$s->step($none);
$s->step($down);
same(2, $s->cursor, 'the cursor stops at the last entry');
$s->step($none);
$s->step($up);
same(1, $s->cursor, 'and goes back up');

// The party's list, and what cannot be sent.
$s->step($confirm);
same(['party', 0, 2], [$s->phase, $s->cursor, count($s->items())], 'the second entry opens the party\'s list');
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
foreach ([$confirm, $confirm, $down, $none, $down, $confirm] as $c) {
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
$rng = Rng::scripted([$limit + 1, 0]); // the flee fails; the wild beast uses its first move, 善走, which asks for no roll
$s = $screen([['lu', 5]], ['xingxing', 20], $rng);
foreach ([$confirm, $confirm, $down, $none, $down, $confirm] as $c) {
    $s->step($c);
}
same([null, '逃不掉！', 1], [$s->outcome, $s->line, $s->battle->turns], 'a roll over the limit fails, and the turn is the wild beast\'s');
$s->step($confirm);
same('野生的狌狌使出了善走！', $s->line, 'which uses a move');
$s->step($confirm);
same('野生的狌狌的速度大幅提高了！', $s->line, 'and a stat that rises two stages is told so');
same(0, $rng->left(), 'every roll was asked for');
$s->step($confirm);
$second = intdiv($slow * 32, intdiv($s->battle->fighter(1)->stat('spe'), 4)) + 30; // against the speed it has now, two stages up
$rng->more([$second]);
foreach ([$down, $none, $down, $confirm] as $c) {
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
    $mine = [[$ids[$any(count($ids))], 30], [$ids[$any(count($ids))], 30]];
    $s = $screen($mine, [$ids[$any(count($ids))], 30], Rng::seeded($seed));
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
same(['fled', 'lost', 'won'], array_keys($outcomes), 'they end each way');
foreach (array_keys($lines) as $line) {
    check(count(Text::wrap($line, Text::columns())) <= Text::LINES, "the line fits the box: $line");
}
foreach (['睡着了！', '混乱了！', '麻痹了！', '中毒了！', '效果拔群！', '效果不太好……', '击中了要害！', '没有打中！', '倒下了！', '提高了！', '降低了！', '恢复了体力！', '醒了过来！'] as $part) {
    check(array_filter(array_keys($lines), fn(string $l) => str_contains($l, $part)) !== [], "among their lines: $part");
}

// A script's battle holds the controls until it is over; then the script goes on.
$map = Map::fromArray(['id' => 'field', 'name' => 'Field', 'rows' => ['___']]);
$w = new World(['field' => $map], 'field', 0, 0, Dir::Down, $book, Rng::seeded(3));
check(throws(fn() => $w->fight('xingxing', 4)), 'a battle with no beast in the party is refused');
$w->party[] = ['id' => 'lushu', 'level' => 30];
$w->run([['battle', 'xingxing', 3], ['say', 'after']]);
check($w->battle !== null && $w->busy(), 'the script began a battle, and waits');
same(null, $w->dialog, 'its next line does not show yet');
$w->step(new Controls(Dir::Right));
same([0, false], [$w->player->x, $w->player->moving], 'the player does not walk during a battle');
for ($steps = 0; $w->battle !== null && $steps < 2000; $steps++) {
    $w->step($steps % 2 === 0 ? $confirm : $none); // every list's first entry: the first move
}
same([null, 'won', 'after'], [$w->battle, $w->lastBattle, $w->dialog], 'when it is won the script goes on');
$w->step($confirm);
check(!$w->busy(), 'and ends');
check(throws(fn() => (new World(['field' => $map], 'field', 0, 0, Dir::Down))->fight('xingxing', 4)), 'a world made without the tables has no battles');
