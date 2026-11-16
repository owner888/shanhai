<?php
// What the one who sets a trial does with a turn (docs/numbers.md 26), with the rolls scripted. The beasts are a
// test's own, as the battle tests': all base stats 100 at level 50, 160 hp and 113 in each stat.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\Battle\{Ai, Battle, Fighter, Rng};
use Shanhai\Data;

$elements = Data::elements();
$things = Data::items();
$moves = [
    'wet' => ['element' => 'water', 'power' => 80, 'accuracy' => 100, 'pp' => 15],
    'hot' => ['element' => 'fire', 'power' => 80, 'accuracy' => 100, 'pp' => 15],
    'iron' => ['element' => 'metal', 'power' => 80, 'accuracy' => 100, 'pp' => 15],
    'tap' => ['element' => null, 'power' => 40, 'accuracy' => 100, 'pp' => 10],
    'sip' => ['element' => 'water', 'power' => 40, 'accuracy' => 100, 'pp' => 10, 'effect' => ['drain', 100]],
    'nap' => ['element' => null, 'power' => 0, 'accuracy' => 75, 'pp' => 10, 'effect' => ['sleep', 100]],
    'numb' => ['element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 10, 'effect' => ['paralyze', 100]],
    'bane' => ['element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 10, 'effect' => ['poison', 100]],
    'daze' => ['element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 10, 'effect' => ['confuse', 100]],
    'brace' => ['element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 10, 'effect' => ['up:def:2', 100]],
    'cow' => ['element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 10, 'effect' => ['down:atk:1', 100]],
    'blur' => ['element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 10, 'effect' => ['up:eva:1', 100]],
    'mend' => ['element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 10, 'effect' => ['heal', 100]],
    'rain' => ['element' => 'water', 'power' => 0, 'accuracy' => 100, 'pp' => 5, 'effect' => ['rain', 100]],
    'sand' => ['element' => 'earth', 'power' => 0, 'accuracy' => 100, 'pp' => 5, 'effect' => ['sandstorm', 100]],
];
$beast = function (string $element, array $known, int $speed = 100, int $level = 50) use ($moves): Fighter {
    $species = ['name' => $element, 'elements' => [$element], 'growth' => 'mediumFast', 'stats' => ['hp' => 100, 'atk' => 100, 'def' => 100, 'spa' => 100, 'spd' => 100, 'spe' => $speed]];
    return new Fighter($element, $species, $level, array_map(fn(string $m) => [1, $m], $known), $moves);
};
$battle = fn(array $mine, array $theirs) => new Battle([$mine, $theirs], $elements, $moves, Rng::scripted([]));
/** The scores one going by $layers gives $theirs' first beast's moves against $mine's, with these rolls. */
$scores = fn(array $layers, array $mine, array $theirs, array $rolls = []) => (new Ai($layers, $moves, $things))->scores($battle($mine, $theirs), Rng::scripted($rolls));

// With no layer every move stands at 20, and the one used is whichever the bytes' two low bits name.
$ai = new Ai([], $moves, $things);
$b = $battle([$beast('fire', ['hot'])], [$beast('water', ['wet', 'tap'])]);
same([[20, 20], ['move', 1]], [$ai->scores($b, Rng::scripted([])), $ai->act($b, Rng::scripted([3, 2, 1]))], 'each as likely: bytes naming no move it knows are passed over');
$b->fighter(1)->moves[1]['pp'] = 0;
same(['move', 0], $ai->act($b, Rng::scripted([1, 0])), 'a move with no uses left is not used');
$b->fighter(1)->moves[0]['pp'] = 0;
same(['move', 0], $ai->act($b, Rng::scripted([])), 'with none left it struggles, on no roll');

// Basic: nothing that would be for nothing.
same([30, 20], $scores(['basic'], [$beast('fire', ['hot'])], [$beast('water', ['mend', 'tap'])]), 'healing one that is whole');
$hurt = $beast('water', ['mend', 'nap', 'daze', 'rain']);
$hurt->hp = 100;
$b = $battle([$beast('fire', ['hot'])], [$hurt]);
same([20, 20, 20, 20], (new Ai(['basic'], $moves, $things))->scores($b, Rng::scripted([])), 'each has its use');
[$b->fighter(0)->status, $b->fighter(0)->confused, $b->weather] = ['burn', 3, 'rain'];
same([20, 30, 30, 30], (new Ai(['basic'], $moves, $things))->scores($b, Rng::scripted([])), 'a status on one that has one, confusing one confused, the weather there is');

// Setup: a move that only changes stats, on the first turn out or after it.
$pair = fn() => [[$beast('fire', ['hot'])], [$beast('water', ['brace', 'cow', 'tap'])]];
same([18, 18, 20], $scores(['setup'], ...[...$pair(), [128, 128]]), 'on the first turn, half the time much likelier');
same([20, 20, 20], $scores(['setup'], ...[...$pair(), [127, 127]]), 'and half the time not');
$ai = new Ai(['setup'], $moves, $things);
$b = $battle(...$pair());
$ai->act($b, Rng::scripted([128, 128, 0]));
$ai->seen($b, ['pass', 0], ['move', 2]);
$ai->act($b, Rng::scripted([30, 128, 1]));
same([22, 18, 20], $ai->scores($b, Rng::scripted([30, 128])), 'its own first turn past, raising its stats is much less likely; the other having used no move, lowering theirs still is likely');
$ai->seen($b, ['move', 0], ['move', 2]);
same([20, 22, 20], $ai->scores($b, Rng::scripted([29, 30])), 'after both, nearly always less: on a byte under 30, as before');

// Types: the elements only.
same([19, 20, 20], $scores(['types'], [$beast('fire', ['hot'])], [$beast('water', ['wet', 'hot', 'tap'])]), 'what overcomes the other\'s element is likelier');
same([20, 21, 20], $scores(['types'], [$beast('earth', ['tap'])], [$beast('water', ['wet', 'hot', 'tap'])]), 'what is weak against it is less likely');
same([20], $scores(['types'], [$beast('earth', ['tap'])], [$beast('water', ['hot'])]), 'unless it has no move of another element that does damage');
same([22, 20], $scores(['offensive'], [$beast('earth', ['tap'])], [$beast('water', ['brace', 'tap'])]), 'offensive: a move that does no damage is much less likely');

// Smart, effect by effect.
$with = function (array $known, int $hp, string $against = 'fire', int $theirHp = 160, int $speed = 100) use ($beast): array {
    [$mine, $theirs] = [$beast($against, ['tap'], $speed), $beast('water', $known)];
    [$mine->hp, $theirs->hp] = [$theirHp, $hp];
    return [[$mine], [$theirs]];
};
same([[18], [20], [20], [21]], [$scores(['smart'], ...[...$with(['mend'], 40), [25]]), $scores(['smart'], ...[...$with(['mend'], 40), [24]]), $scores(['smart'], ...$with(['mend'], 80)), $scores(['smart'], ...$with(['mend'], 81))],
    'healing: with a quarter of its hp or less, much likelier but on a byte under 25; over half, less likely');
same([[20], [21], [22], [21]], [$scores(['smart'], ...$with(['daze'], 160, 'fire', 81)), $scores(['smart'], ...[...$with(['daze'], 160, 'fire', 80), [25]]), $scores(['smart'], ...[...$with(['daze'], 160, 'fire', 40), [25]]), $scores(['smart'], ...[...$with(['daze'], 160, 'fire', 40), [24]])],
    'confusing: less likely on one with half its hp or less, and again with a quarter or less');
same([[18], [20], [20], [21]], [$scores(['smart'], ...[...$with(['numb'], 160), [50]]), $scores(['smart'], ...[...$with(['numb'], 160), [49]]), $scores(['smart'], ...$with(['numb'], 160, 'fire', 160, 50)), $scores(['smart'], ...[...$with(['numb'], 160, 'fire', 40), [128]])],
    'paralyzing: much likelier when it is no faster; not when it is; less likely on one with a quarter of its hp or less');
same([[19], [20], [21], [20]], [$scores(['smart'], ...[...$with(['sip'], 100), [50]]), $scores(['smart'], ...$with(['sip'], 160)), $scores(['smart'], ...[...$with(['sip'], 100, 'wood'), [100]]), $scores(['smart'], ...[...$with(['sip'], 100, 'wood'), [99]])],
    'draining: likelier, hurt, with what overcomes the other; less likely with what is weak against it');
same([[18], [21], [23], [18], [21]], [$scores(['smart'], ...[...$with(['blur'], 160), [177]]), $scores(['smart'], ...[...$with(['blur'], 160), [178]]), $scores(['smart'], ...$with(['blur'], 40)), $scores(['smart'], ...[...$with(['blur'], 100), [10, 49]]), $scores(['smart'], ...[...$with(['blur'], 60), [10, 127]])],
    'raising its evasion: whole, seven times in ten much likelier; with a quarter or less, far less likely');
same([[22], [21], [19]], [$scores(['smart'], ...$with(['sand'], 160, 'earth')), $scores(['smart'], ...$with(['sand'], 160, 'fire', 80)), $scores(['smart'], ...[...$with(['sand'], 160), [128]])],
    'the sandstorm: not on one it spares, less on one with half its hp or less');
same([[18, 20], [23, 20], [19, 20], [23], [23, 20]], [$scores(['smart'], ...$with(['rain', 'wet'], 160)), $scores(['smart'], ...$with(['rain', 'wet'], 160, 'water')), $scores(['smart'], ...[...$with(['rain', 'wet'], 160, 'wood'), [128]]), $scores(['smart'], ...$with(['rain'], 160, 'wood')), $scores(['smart'], ...$with(['rain', 'wet'], 160, 'wood', 80))],
    'the rain: against fire on a first turn; never against water, nor with no move of water, nor on one with half its hp or less');

// Aggressive and risky: the move that would do most, and any that would fell.
same([20, 21, 20], $scores(['aggressive'], ...$with(['wet', 'tap', 'mend'], 160)), 'aggressive: each move that does damage but the one that would do most is less likely');
same([[20, 20], [15, 20]], [$scores(['risky'], ...$with(['wet', 'tap'], 160, 'fire', 110)), $scores(['risky'], ...$with(['wet', 'tap'], 160, 'fire', 109))], 'risky: 110 on one with 109 left fells it');

// Cautious: after its first turn a move that only poisons or paralyzes is less likely; the one byte in ten ends the layer.
$ai = new Ai(['cautious'], $moves, $things);
$b = $battle(...$with(['bane', 'numb', 'tap'], 160));
same([20, 20, 20], $ai->scores($b, Rng::scripted([])), 'not on its first turn');
$ai->act($b, Rng::scripted([0]));
$ai->seen($b, ['move', 0], ['move', 0]);
same([[21, 21, 20], [21, 20, 20]], [$ai->scores($b, Rng::scripted([229, 0])), $ai->scores($b, Rng::scripted([0, 230]))], 'then nine times in ten');
same([Ai::GOD, Ai::ROAD], [['basic', 'setup', 'smart', 'aggressive', 'cautious', 'risky'], ['basic', 'types']], 'a god goes by the reference\'s leaders\' layers, one on the road by those most of its ordinary classes have');
same([], array_diff([...Ai::GOD, ...Ai::ROAD], Ai::LAYERS), 'and each is a layer there is');

// Sending in another: only when the one out stands badly, and only one that stands well.
$stand = function (string $mine = 'fire', int $waitingHp = 160) use ($beast, $battle): Battle {
    $waiting = $beast('water', ['wet']);
    $waiting->hp = $waitingHp;
    return $battle([$beast($mine, ['hot', 'tap'])], [$beast('metal', ['iron']), $waiting]);
};
$ai = new Ai([], $moves, $things);
same(1, $ai->another($stand()), 'fire overcomes the metal out, and the water waiting overcomes fire');
same([['switch', 1], ['move', 0]], [$ai->act($stand(), Rng::scripted([49])), $ai->act($stand(), Rng::scripted([50, 0]))], 'it is sent in a time in five');
same([null, null], [$ai->another($stand('fire', 40)), $ai->another($stand('wood'))], 'not one with a quarter of its hp or less; and not when the one out does not stand badly');
$b = $stand();
$ai->act($b, Rng::scripted([50, 0]));
$ai->seen($b, ['move', 1], ['move', 0]);
same(null, $ai->another($b), 'the player having used only a move that is even against it, it stays');
$ai->act($b, Rng::scripted([0]));
$ai->seen($b, ['move', 0], ['move', 0]);
same(1, $ai->another($b), 'and having used one that overcomes it, it looks again');

// The things it has: on the highest of its party only, each once.
$hurt = fn(int $hp, ?string $status = null, int $level = 50) => (function () use ($beast, $battle, $hp, $status, $level): Battle {
    $theirs = $beast('water', ['tap'], 100, $level);
    [$theirs->hp, $theirs->status, $theirs->sleep] = [$hp, $status, $status === 'sleep' ? 3 : 0];
    return $battle([$beast('fire', ['tap'])], [$theirs, $beast('water', ['tap'], 100, 50)]);
})();
$ai = new Ai([], $moves, $things, ['licao']);
same([['item', 'licao'], ['move', 0]], [$ai->act($hurt(40), Rng::scripted([])), $ai->act($hurt(40), Rng::scripted([0]))], 'with a quarter of its hp or less it restores it, and has the thing no more');
same([['move', 0], ['item', 'licao']], [(new Ai([], $moves, $things, ['licao']))->act($hurt(80), Rng::scripted([50, 0])), (new Ai([], $moves, $things, ['licao']))->act($hurt(80), Rng::scripted([49]))], 'with half or less, a time in five');
same([['move', 0], ['move', 0]], [(new Ai([], $moves, $things, ['licao']))->act($hurt(81), Rng::scripted([0])), (new Ai([], $moves, $things, ['licao']))->act($hurt(10, null, 49), Rng::scripted([0]))], 'not with more; and not on one under the highest of its party');
same([['item', 'yupei'], ['move', 0], ['item', 'dixiu']], [(new Ai([], $moves, $things, ['yupei']))->act($hurt(160, 'sleep'), Rng::scripted([])), (new Ai([], $moves, $things, ['yupei']))->act($hurt(160, 'burn'), Rng::scripted([0])), (new Ai([], $moves, $things, ['yupei', 'dixiu']))->act($hurt(160, 'sleep'), Rng::scripted([]))],
    'what ends any status is for one asleep; what does both comes first');
