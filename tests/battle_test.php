<?php
// The battle, turn by turn, with the rolls scripted: each number here is worked out by hand from docs/numbers.md.
// The beasts are a test's own, all base stats 100 at level 50: 160 hp and 113 in each stat, unless one says otherwise.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\Battle\{Battle, Fighter, Rng};
use Shanhai\Data;

$elements = Data::elements();
$moves = [
    'wet' => ['element' => 'water', 'power' => 80, 'accuracy' => 100, 'pp' => 15],
    'hot' => ['element' => 'fire', 'power' => 80, 'accuracy' => 100, 'pp' => 15],
    'tap' => ['element' => null, 'power' => 40, 'accuracy' => 100, 'pp' => 2],
    'wild' => ['element' => null, 'power' => 40, 'accuracy' => 75, 'pp' => 10],
    'keen' => ['element' => null, 'power' => 40, 'accuracy' => 100, 'pp' => 10, 'effect' => ['crit', 100]],
    'jolt' => ['element' => null, 'power' => 40, 'accuracy' => 100, 'pp' => 10, 'effect' => ['flinch', 30]],
    'sip' => ['element' => null, 'power' => 40, 'accuracy' => 100, 'pp' => 10, 'effect' => ['drain', 100]],
    'nap' => ['element' => null, 'power' => 0, 'accuracy' => 75, 'pp' => 10, 'effect' => ['sleep', 100]],
    'numb' => ['element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 10, 'effect' => ['paralyze', 100]],
    'bane' => ['element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 10, 'effect' => ['poison', 100]],
    'daze' => ['element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 10, 'effect' => ['confuse', 100]],
    'brace' => ['element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 10, 'effect' => ['up:def:2', 100]],
    'mend' => ['element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 10, 'effect' => ['heal', 100]],
    'rain' => ['element' => 'water', 'power' => 0, 'accuracy' => 100, 'pp' => 5, 'effect' => ['rain', 100]],
    'sand' => ['element' => 'wood', 'power' => 0, 'accuracy' => 100, 'pp' => 5, 'effect' => ['sandstorm', 100]],
];
/** A beast of $element knowing $known, all base stats 100 but its speed. */
$beast = function (string $element, array $known, int $speed = 100) use ($moves): Fighter {
    $species = ['elements' => [$element], 'growth' => 'mediumFast', 'stats' => ['hp' => 100, 'atk' => 100, 'def' => 100, 'spa' => 100, 'spd' => 100, 'spe' => $speed]];
    return new Fighter($element, $species, 50, array_map(fn(string $m) => [1, $m], $known), $moves);
};
$fight = fn(array $a, array $b, array $rolls) => [$rng = Rng::scripted($rolls), new Battle([$a, $b], $elements, $moves, $rng)];
$NO = 200; // a byte that is no critical hit, no full paralysis, no hitting itself
$MOST = 255; // the byte whose roll of the random spread is 255: damage as worked out

$w = $beast('water', ['wet']);
same([160, 113, 63], [$w->maxHp, $w->stats['spa'], $beast('fire', ['hot'], 50)->stats['spe']], 'the test\'s beasts: 160 hp, 113 a stat, 63 the slow one\'s speed');

// The faster moves first; a move of the user's element, against the element it overcomes, and back.
$f = $beast('fire', ['hot'], 50);
[$rng, $b] = $fight([$w], [$f], [$NO, $MOST, $NO, 179]);
$b->turn(['move', 0], ['move', 0]);
same(['use', 0, 'wet'], $b->log[0], 'the faster moves first');
same(50, $f->hp, 'water on fire: 37, half as much again 55, doubled 110');
same(114, $w->hp, 'fire on water, even: 55, at the least of the spread 46');
same(0, $rng->left(), 'four rolls: a critical and a spread each');
same(14, $w->moves[0]['pp'], 'a use is spent');

// A critical hit, and the last beast falling ends it.
$f = $beast('fire', ['hot'], 50);
[$rng, $b] = $fight([$beast('water', ['wet'])], [$f], [16, $MOST]);
$b->turn(['move', 0], ['move', 0]);
same(0, $f->hp, 'a critical hit: 72, 108, doubled 216');
same(0, $b->winner, 'and the side with none left has lost');
check(in_array(['faint', 1], $b->log, true) && $rng->left() === 0, 'the fallen does not move');
check(throws(fn() => $b->turn(['move', 0], ['move', 0])), 'a battle that is over takes no turn');

// A miss: the damage is rolled first, then whether it hits.
$f = $beast('fire', ['hot'], 50);
[$rng, $b] = $fight([$beast('water', ['wild'])], [$f], [$NO, $MOST, 191, $NO, $MOST]);
$b->turn(['move', 0], ['move', 0]);
check(in_array(['miss', 0], $b->log, true) && $f->hp === 160, '75% misses on a byte of 191 or more');

// Speed ties are a coin.
[$rng, $b] = $fight([$beast('water', ['tap'])], [$beast('fire', ['tap'])], [128, $NO, $MOST, $NO, $MOST]);
$b->turn(['move', 0], ['move', 0]);
same(['use', 1, 'tap'], $b->log[0], 'on a tie, a byte of 128 or more lets side 1 go first');

// Sleep: 2 to 7 on the counter, a turn off each time, awake and moving on the turn it reaches nothing.
$f = $beast('fire', ['tap'], 50);
[$rng, $b] = $fight([$beast('water', ['nap', 'brace'])], [$f], [100, 0, 7, 3]);
$b->turn(['move', 0], ['move', 0]);
same(['sleep', 3], [$f->status, $f->sleep], 'asleep: bytes whose low bits are 0 or 7 are taken again; 3 makes the counter 4, and its own turn takes one');
$b->turn(['move', 1], ['move', 0]);
$b->turn(['move', 1], ['move', 0]);
same(1, $f->sleep, 'two more turns asleep');
$rng->more([$NO, $MOST]);
$b->turn(['move', 1], ['move', 0]);
check($f->status === null && in_array(['woke', 1], $b->log, true) && in_array(['use', 1, 'tap'], $b->log, true), 'it wakes and moves on the same turn');
same(3, count(array_filter($b->log, fn(array $e) => $e === ['cant', 1, 'sleep'])), 'three turns lost in all');

// Paralysis: a quarter of the speed, and a turn lost on a byte under 63.
$fast = $beast('fire', ['tap']);
[$rng, $b] = $fight([$beast('water', ['numb'], 50)], [$fast], [$NO, $MOST, 62, $NO, $MOST]);
$b->turn(['move', 0], ['move', 0]);
same(['paralyze', 28], [$fast->status, $fast->stat('spe')], 'paralyzed after it moved: its speed a quarter, 113÷4');
$b->turn(['move', 0], ['move', 0]);
same(['use', 0, 'numb'], $b->log[4] ?? null, 'the slower beast is now the faster');
check(in_array(['cant', 1, 'paralyze'], $b->log, true), 'and a byte of 62 loses the paralyzed its turn');
check(in_array(['fail', 0], $b->log, true), 'a beast with a status takes no second one');

// Poison: an eighth of the hp after its turn.
$f = $beast('fire', ['brace'], 50);
[$rng, $b] = $fight([$beast('water', ['bane', 'brace'])], [$f], []);
$b->turn(['move', 0], ['move', 0]);
$b->turn(['move', 1], ['move', 0]);
same(120, $f->hp, 'poison takes 160÷8 after each of its turns');

// Confusion: 2 to 5 on the counter; a byte under 128 turns its blow on itself.
$f = $beast('fire', ['tap'], 50);
[$rng, $b] = $fight([$beast('water', ['daze', 'brace'])], [$f], [1, 127]);
$b->turn(['move', 0], ['move', 0]);
same(141, $f->hp, 'confused for 3: its turn takes one, then it hits itself: 22×40×113÷113 is 880, ÷50 is 17, +2; this blow has no spread');
same(2, $f->confused, 'two left');
$rng->more([128, 200, 255]);
$b->turn(['move', 1], ['move', 0]);
check(in_array(['use', 1, 'tap'], $b->log, true), 'a byte of 128 lets it move');
$rng->more([200, 255]);
$b->turn(['move', 1], ['move', 0]);
check($f->confused === 0 && in_array(['clear', 1], $b->log, true), 'and at nothing it is over, with no roll');

// A flinch stops the one yet to move, this turn only.
$f = $beast('fire', ['tap'], 50);
[$rng, $b] = $fight([$beast('water', ['jolt'])], [$f], [$NO, $MOST, 75, $NO, $MOST, 76, $NO, $MOST]);
$b->turn(['move', 0], ['move', 0]);
check(in_array(['cant', 1, 'flinch'], $b->log, true), 'a 30% effect happens on a byte under 76');
$b->turn(['move', 0], ['move', 0]);
same(1, count(array_filter($b->log, fn(array $e) => $e === ['cant', 1, 'flinch'])), 'and not on 76; the flinch does not last');

// Stages, and what a critical hit makes of them.
$f = $beast('fire', ['brace'], 50);
$u = $beast('water', ['keen']);
[$rng, $b] = $fight([$u], [$f], [$NO, $MOST]);
$b->turn(['move', 0], ['move', 0]);
same(141, $f->hp, 'no element, even: 17+2 is 19');
same(2, $f->stages['def'], 'defense up two after it was hit');
$rng->more([$NO, $MOST]);
$b->turn(['move', 0], ['move', 0]);
same(141 - 10, $f->hp, 'against defense doubled, 226: 22×40×113÷226 is 440, ÷50 is 8, +2');
same(4, $f->stages['def'], 'and up two more');
$rng->more([63, 255]);
$b->turn(['move', 0], ['move', 0]);
same(131 - 36, $f->hp, 'a move that is likelier to be critical is so under 64, and takes the defense without its stages: 17×2+2');

// Draining and healing.
$f = $beast('fire', ['brace'], 50);
$u = $beast('water', ['sip', 'mend']);
$u->hp = 100;
[$rng, $b] = $fight([$u], [$f], [$NO, $MOST]);
$b->turn(['move', 0], ['move', 0]);
same(109, $u->hp, 'half of the 19 done comes back');
$b->turn(['move', 1], ['move', 0]);
same(160, $u->hp, 'healing gives half the hp, to the full at most');
$b->turn(['move', 1], ['move', 0]);
check(in_array(['fail', 0], $b->log, true), 'and fails when there is nothing to heal');

// Rain: a move of water does half as much again, before the rest; it lasts five turns.
$f = $beast('fire', ['brace'], 50);
[$rng, $b] = $fight([$beast('water', ['rain', 'wet'])], [$f], [$NO, $MOST]);
$b->turn(['move', 0], ['move', 0]);
same(['rain', 4], [$b->weather, $b->weatherLeft], 'rain, with four turns left after this one');
$b->turn(['move', 1], ['move', 0]);
same(0, $f->hp, 'in rain: 37×15÷10 is 55, half as much again 82, doubled 164');

// A sandstorm takes an eighth from those it does not spare, on four of its five turns.
$f = $beast('fire', ['brace', 'sand'], 50);
$spared = $beast('earth', ['brace']);
[$rng, $b] = $fight([$spared], [$f], []);
$b->turn(['move', 0], ['move', 1]);
for ($i = 0; $i < 4; $i++) {
    $b->turn(['move', 0], ['move', 0]);
}
same([160, 80, null], [$spared->hp, $f->hp, $b->weather], 'earth is spared; fire loses 20 four times; then it is over');

// Switching comes first and leaves the stages behind; a fallen beast is replaced; uses run out.
$one = $beast('water', ['tap']);
$two = $beast('wood', ['tap']);
$f = $beast('fire', ['tap', 'brace'], 50);
[$rng, $b] = $fight([$one, $two], [$f], [$NO, $MOST]);
$one->stages['atk'] = 3;
$b->turn(['switch', 1], ['move', 0]);
same([['switch', 0, 1], 1, 0], [$b->log[0], $b->active[0], $one->stages['atk']], 'the switch is first, and the stages stay behind');
same(141, $two->hp, 'the one sent in takes the blow');
check(throws(fn() => $b->turn(['switch', 1], ['move', 1])), 'a beast cannot be switched to itself');
$rng->more([200, 255, 200, 255]);
$b->turn(['move', 0], ['move', 0]);
same([0, 1], [$f->moves[0]['pp'], count($b->usable(1))], 'its two uses of the move are spent; one move is left');
check(throws(fn() => $b->turn(['move', 0], ['move', 0])), 'a move with no uses left is refused while another has some');
$two->hp = 6;
$f->moves[1]['pp'] = 0;
$rng->more([200, 255, 200, 255]);
$b->turn(['move', 0], ['move', 0]);
// With no move left to use a beast struggles (docs/numbers.md 11.6): a blow of power 50 and no element, a quarter of which comes back on it.
check(in_array(['struggle', 0], $b->log, true), 'a beast with no move left struggles');
$dealt = array_values(array_filter($b->log, fn(array $e) => $e[0] === 'hit' && $e[1] === 1));
same(['hurt', 0, intdiv(end($dealt)[2], 4), 'recoil'], array_values(array_filter($b->log, fn(array $e) => ($e[3] ?? null) === 'recoil'))[0] ?? null, 'and a quarter of what it dealt comes back on it');
same([0, 0, null], [$two->hp, $b->active[0], $b->winner], 'the one it struck down is replaced by the next that can fight, and the battle goes on');

// A whole battle of the game's own beasts, from a seed: it ends, and the same seed gives the same battle.
$play = function (int $seed) use ($elements): array {
    $beasts = Data::beasts();
    $learn = Data::learnsets();
    $all = Data::moves();
    $make = fn(string $id) => new Fighter($id, $beasts[$id], 30, $learn[$id], $all);
    $b = new Battle([[$make('lushu'), $make('lili')], [$make('xuangui'), $make('zhu')]], $elements, $all, Rng::seeded($seed));
    while ($b->winner === null && $b->turns < 300) {
        $pick = fn(int $side) => ['move', $b->usable($side)[$b->turns % max(1, count($b->usable($side)))] ?? 0];
        $b->turn($pick(0), $pick(1));
    }
    return [$b->winner, $b->turns, count($b->log)];
};
$sim = new \Shanhai\Battle\Simulator(Data::beasts(), Data::learnsets(), Data::moves(), $elements);
same($sim->battle(['lushu', 'lili', 'zhu'], ['xuangui', 'huahuai', 'zhi'], 40, 3), $sim->battle(['lushu', 'lili', 'zhu'], ['xuangui', 'huahuai', 'zhi'], 40, 3), 'a battle of parties, with switching, replays from its seed');
check(in_array($sim->battle(['lushu'], ['xuangui'], 40, 1), [0, 1, -1], true), 'and ends');
$kept = $sim->kept('huahuai', 50);
same(4, count($kept), 'a beast keeps four moves');
same(['slumber'], array_values(array_filter($kept, fn(string $m) => Data::moves()[$m]['power'] === 0)), 'one of them does no damage: sleep, before the later ones it learned');
same(['sleepless'], array_values(array_filter($sim->kept('lushu', 50), fn(string $m) => Data::moves()[$m]['power'] === 0)), 'a beast with no such effect keeps the last it learned');
check(in_array('landslide', $kept, true), 'and its strongest among the rest');
check(throws(fn() => new Fighter('lushu', Data::beasts()['lushu'], 5, Data::learnsets()['lushu'], Data::moves(), keeps: ['scorch'])), 'a beast keeps no move it has not learned yet');
same($sim->battle(['lushu', 'lili'], ['xuangui', 'zhu'], 40, 5, true), $sim->battle(['lushu', 'lili'], ['xuangui', 'zhu'], 40, 5, true), 'with both sides thinking ahead, too');
$mk = fn(string $id) => new Fighter($id, Data::beasts()[$id], 40, Data::learnsets()[$id], Data::moves());
$b = new Battle([[$mk('lushu')], [$mk('xuangui')]], $elements, Data::moves(), Rng::seeded(1));
$before = [$b->fighter(0)->hp, $b->fighter(1)->hp, $b->turns];
$b->copy(Rng::seeded(2))->turn(['move', 0], ['move', 0]);
same($before, [$b->fighter(0)->hp, $b->fighter(1)->hp, $b->turns], 'a copy played on leaves the battle as it was');
$first = $play(7);
check($first[0] !== null, 'a battle of the game\'s beasts ends: ' . json_encode($first));
same($first, $play(7), 'the same seed, the same battle');

// What the beasts hold (docs/numbers.md 19). The things are the test's own; the faster beast is side 0's.
// 轻击 (power 40, no element) between two of the test's beasts: (50 × 2 ÷ 5 + 2) × 40 × 113 ÷ 113 ÷ 50 = 17, and 2: 19.
$things = ['bow' => ['hold' => 'boost', 'by' => 'none'], 'lens' => ['hold' => 'crit'], 'dust' => ['hold' => 'evade', 'by' => 20], 'band' => ['hold' => 'endure', 'by' => 30],
    'rock' => ['hold' => 'flinch', 'by' => 30], 'claw' => ['hold' => 'first', 'by' => 60], 'fruit' => ['hold' => 'heal', 'by' => 10], 'herb' => ['hold' => 'cure', 'by' => 'poison'],
    'balm' => ['hold' => 'cure', 'by' => 'any'], 'crumbs' => ['hold' => 'leftovers', 'by' => 16], 'dew' => ['hold' => 'pp', 'by' => 5]];
$with = function (Fighter $f, string $item): Fighter {
    $f->item = $item;
    return $f;
};
$held = fn(array $a, array $b, array $rolls) => [$rng = Rng::scripted($rolls), new Battle([$a, $b], $elements, $moves, $rng, [false, false], $things)];
$tap = ['move', 0];
$kinds = fn(Battle $b) => array_map(fn(array $e) => $e[0] . $e[1], $b->log);

[$rng, $b] = $held([$with($beast('water', ['tap']), 'bow')], [$beast('fire', ['tap'], 50)], [$NO, $MOST, $NO, $MOST]);
$b->turn($tap, $tap);
same([160 - 20, 160 - 19], [$b->fighter(1)->hp, $b->fighter(0)->hp], 'what strengthens the moves of no element adds a tenth before the 2: 17 × 110 ÷ 100 = 18, and 2');
[$rng, $b] = $held([$with($beast('water', ['wet']), 'bow')], [$beast('wood', ['tap'], 50)], [$NO, $MOST, $NO, $MOST]);
[$rng2, $plain] = $fight([$beast('water', ['wet'])], [$beast('wood', ['tap'], 50)], [$NO, $MOST, $NO, $MOST]);
$b->turn($tap, $tap);
$plain->turn($tap, $tap);
same($plain->fighter(1)->hp, $b->fighter(1)->hp, 'and nothing to a move of another element');

// 4: the first two levels of a critical hit are a byte under 17 and under 32.
[$rng, $b] = $held([$with($beast('water', ['tap']), 'lens')], [$beast('fire', ['tap'], 50)], [20, $MOST, 20, $MOST]);
$b->turn($tap, $tap);
$hits = array_values(array_filter($b->log, fn(array $e) => $e[0] === 'hit'));
same([true, false], [$hits[0][3], $hits[1][3]], 'what sharpens the eye raises the critical level by one: a byte of 20 strikes true for its holder alone');

// 6: a move of 100 needs 255, which no byte misses; less 20, 235.
[$rng, $b] = $held([$beast('water', ['tap'])], [$with($beast('fire', ['tap'], 50), 'dust')], [$NO, $MOST, 235, $NO, $MOST]);
$b->turn($tap, $tap);
same([160, true], [$b->fighter(1)->hp, in_array(['miss', 0], $b->log, true)], 'what makes its holder hard to hit takes 20 from what the move needs: a byte of 235 misses');
[$rng, $b] = $held([$beast('water', ['tap'])], [$with($beast('fire', ['tap'], 50), 'dust')], [$NO, $MOST, 234, $NO, $MOST]);
$b->turn($tap, $tap);
same(160 - 19, $b->fighter(1)->hp, 'and one of 234 hits');

$low = $with($beast('fire', ['tap'], 50), 'band');
$low->hp = 5;
[$rng, $b] = $held([$beast('water', ['tap'])], [$low], [$NO, $MOST, 29, $NO, $MOST]);
$b->turn($tap, $tap);
same([1, 'band', true], [$low->hp, $low->item, in_array(['held', 1, 'band'], $b->log, true)], 'what lets its holder bear a blow leaves it 1 hp on a byte under 30, and is kept');
$low = $with($beast('fire', ['tap'], 50), 'band');
$low->hp = 5;
[$rng, $b] = $held([$beast('water', ['tap'])], [$low], [$NO, $MOST, 30]);
$b->turn($tap, $tap);
same([0, 0], [$low->hp, $rng->left()], 'and on one of 30 does not');

[$rng, $b] = $held([$with($beast('water', ['tap']), 'rock')], [$beast('fire', ['tap'], 50)], [$NO, $MOST, 29]);
$b->turn($tap, $tap);
same([true, 160, 0], [in_array(['cant', 1, 'flinch'], $b->log, true), $b->fighter(0)->hp, $rng->left()], 'a blow from one holding what cows makes the other, yet to act, flinch on a byte under 30');
[$rng, $b] = $held([$with($beast('water', ['tap']), 'rock')], [$beast('fire', ['tap'], 50)], [$NO, $MOST, 30, $NO, $MOST]);
$b->turn($tap, $tap);
same(160 - 19, $b->fighter(0)->hp, 'and on one of 30 does not');

[$rng, $b] = $held([$beast('water', ['tap'])], [$with($beast('fire', ['tap'], 50), 'claw')], [59, $NO, $MOST, $NO, $MOST]);
$b->turn($tap, $tap);
same(['held1', 'use1'], array_slice($kinds($b), 0, 2), 'the slower, holding what lets it go first, does on a byte under 60');
[$rng, $b] = $held([$beast('water', ['tap'])], [$with($beast('fire', ['tap'], 50), 'claw')], [60, $NO, $MOST, $NO, $MOST]);
$b->turn($tap, $tap);
same('use0', $kinds($b)[0], 'and on one of 60 the faster goes first as ever');
[$rng, $b] = $held([$with($beast('water', ['tap'], 50), 'claw')], [$with($beast('fire', ['tap']), 'claw')], [60, 59, $NO, $MOST, $NO, $MOST]);
$b->turn($tap, $tap);
same(['held0', 'use0'], array_slice($kinds($b), 0, 2), 'both holding it, the other side\'s is rolled first, then the player\'s');

$hungry = $with($beast('fire', ['tap'], 50), 'fruit');
$hungry->hp = 98;
[$rng, $b] = $held([$beast('water', ['tap'])], [$hungry], [$NO, $MOST, $NO, $MOST]);
$b->turn($tap, $tap);
same([98 - 19 + 10, null], [$hungry->hp, $hungry->item], 'what restores hp is eaten once its holder is under half: 79 of 160, and 10 more; it is used up');
$hungry = $with($beast('fire', ['tap'], 50), 'fruit');
$hungry->hp = 99;
[$rng, $b] = $held([$beast('water', ['tap'])], [$hungry], [$NO, $MOST, $NO, $MOST]);
$b->turn($tap, $tap);
same([80, 'fruit'], [$hungry->hp, $hungry->item], 'at half it is not');

[$rng, $b] = $held([$beast('water', ['bane'])], [$with($beast('fire', ['tap'], 50), 'herb')], [$NO, $MOST]);
$b->turn($tap, $tap);
same([null, null, true], [$b->fighter(1)->status, $b->fighter(1)->item, in_array(['cured', 1, 'poison'], $b->log, true)], 'what cures a status does so as soon as its holder has it, and is used up');
[$rng, $b] = $held([$beast('water', ['numb'])], [$with($beast('fire', ['tap'], 50), 'herb')], [$NO, $NO, $MOST]);
$b->turn($tap, $tap);
same(['paralyze', 'herb'], [$b->fighter(1)->status, $b->fighter(1)->item], 'not another status');
[$rng, $b] = $held([$beast('water', ['daze'])], [$with($beast('fire', ['tap'], 50), 'balm')], [0, $NO, $MOST]);
$b->turn($tap, $tap);
same([0, null, 160 - 19], [$b->fighter(1)->confused, $b->fighter(1)->item, $b->fighter(0)->hp], 'what cures any clears confusion too, and its holder acts unconfused');

$worn = $with($beast('water', ['tap']), 'crumbs');
$worn->hp = 100;
[$rng, $b] = $held([$worn], [$beast('fire', ['tap'], 50)], []);
$b->turn(['pass', 0], ['pass', 0]);
same([110, 'crumbs'], [$worn->hp, $worn->item], 'what restores a little each turn gives a sixteenth of the hp at most at the turn\'s end, and is kept');
$b->fighter(0)->hp = 160;
$b->turn(['pass', 0], ['pass', 0]);
same(false, in_array('held0', array_slice($kinds($b), 2), true), 'not to one that is whole');

$dry = $with($beast('water', ['tap']), 'dew');
$dry->moves[0]['pp'] = 0;
[$rng, $b] = $held([$dry], [$beast('fire', ['tap'], 50)], []);
$b->turn(['pass', 0], ['pass', 0]);
same([2, null], [$dry->moves[0]['pp'], $dry->item], 'what restores a move\'s uses gives 5, or all the move has, to one that has none, and is used up');
check($b->copy(Rng::scripted([]))->fighter(0) !== $dry, 'a copy of the battle keeps what is held apart');

// Medicines (docs/numbers.md 21): what a thing used on a beast does, and when it does nothing and is not used.
$use = fn(array $thing, Fighter $f) => \Shanhai\Battle\Medicine::apply($thing, $f);
$f = $beast('water', ['tap']);
$f->hp = 100;
same([true, 120, true, 160, false], [$use(['use' => 'heal', 'by' => 20], $f), $f->hp, $use(['use' => 'heal', 'by' => 200], $f), $f->hp, $use(['use' => 'heal', 'by' => 20], $f)], 'hp is restored by the number, to all there is at most; one that is whole takes none');
$f->hp = 7;
same([true, 160], [$use(['use' => 'heal', 'by' => 'all'], $f), $f->hp], 'or all of it');
$f->hp = 0;
same([false, false, false, 0], [$use(['use' => 'heal', 'by' => 20], $f), $use(['use' => 'cure', 'by' => 'any'], $f), $use(['use' => 'restore'], $f), $f->hp], 'one that has fallen is not healed, cured or restored');
same([true, 80, false], [$use(['use' => 'revive'], $f), $f->hp, $use(['use' => 'revive'], $f)], 'it is stood up with half its hp; one that stands is not');
$f->status = 'poison';
same([false, true, null, false], [$use(['use' => 'cure', 'by' => 'burn'], $f), $use(['use' => 'cure', 'by' => 'poison'], $f), $f->status, $use(['use' => 'cure', 'by' => 'poison'], $f)], 'a status is ended by what cures it, not by what cures another; one without it takes none');
[$f->status, $f->sleep, $f->confused] = ['sleep', 3, 2];
same([true, null, 0, 0], [$use(['use' => 'cure', 'by' => 'any'], $f), $f->status, $f->sleep, $f->confused], 'what cures any ends any, and confusion with it');
[$f->status, $f->confused] = ['burn', 2];
same([true, 160, null, 0, false], [$use(['use' => 'restore'], $f), $f->hp, $f->status, $f->confused, $use(['use' => 'restore'], $f)], 'what restores makes whole and well; one that is, takes none');
same(false, $use(['use' => 'catch'], $f), 'a seal is no medicine');
