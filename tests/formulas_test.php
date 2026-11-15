<?php
// The numbers' baseline as code, each rule against a value worked out by hand from docs/numbers.md.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\Battle\{Formulas, Rng};

// 2: stats
same(88, Formulas::stat(75, 8, 0, 50, false), 'a stat: ((75+8)×2)×50÷100+5');
same(143, Formulas::stat(75, 8, 0, 50, true), 'hp: the same, +50+10 in place of +5');
same(119, Formulas::stat(75, 8, 65535, 50, false), 'stat experience adds the fourth of its root, the root at most 255');
same(5, Formulas::stat(1, 0, 0, 1, false), 'the least a stat is');
same(713, Formulas::stat(255, 15, 65535, 100, true), 'the most the formula gives: ((255+15)×2+63)×100÷100+100+10');
same(0b1010, Formulas::hpDv(15, 8, 3, 2), 'the hp\'s individual value, from the others\' low bits');

// 3: damage
same(37, Formulas::damage(50, 80, 100, 100, false), 'damage: (50×2÷5+2)×80×100÷100÷50, +2');
same(72, Formulas::damage(50, 80, 100, 100, true), 'a critical hit doubles it before the +2');
same(0, Formulas::damage(50, 0, 100, 100, false), 'a move of no power does none');
same([75, 50], Formulas::truncate(300, 200), 'attack and defense are both quartered when either is over 255');
same([255, 1], Formulas::truncate(255, 0), 'defense is 1 at least');
same(Formulas::damage(50, 80, 75, 50, false), Formulas::damage(50, 80, 300, 200, false), 'and the formula takes them so');
same(999, Formulas::damage(100, 250, 255, 1, true), 'damage is 999 at most');
same(2, Formulas::damage(1, 10, 1, 255, false), 'and 2 at least');
same(40, Formulas::damage(50, 80, 100, 100, false, 10), 'an item of its element adds its tenth: 35×110÷100 is 38, +2');
same(55, Formulas::sameElement(37), 'a move of the user\'s own element: half as much again');
same(74, Formulas::times(37, 20), 'double');
same(18, Formulas::times(37, 5), 'half');
same(1, Formulas::times(1, 5), 'what was something stays 1 at least');
same(31, Formulas::vary(37, 217), 'the least of the random spread: ×217÷255');
same(37, Formulas::vary(37, 255), 'the most');
same(1, Formulas::vary(1, 217), 'under 2 it is left alone');
$rng = Rng::scripted([0, 255]);
same(255, Formulas::varyRoll($rng), 'the roll: a byte turned one bit right, taken again while under 217');
same(0, $rng->left(), 'the first byte, 0, was taken again');
same(217, Formulas::varyRoll(Rng::scripted([179])), '179 turned one bit right is 217');

// 4, 5, 6
same([17, 32, 64], array_slice(Formulas::CRITICAL, 0, 3), 'the critical thresholds');
same(150, Formulas::staged(100, 1), 'a stage up');
same(66, Formulas::staged(100, -1), 'a stage down');
same(25, Formulas::staged(100, -6), 'the lowest stage');
same(999, Formulas::staged(400, 6), 'a stat with its stage is 999 at most');
same(255, Formulas::accuracy(100, 0, 0), 'full accuracy is 255: it always hits');
same(191, Formulas::accuracy(75, 0, 0), '75% is 191 of 255');
same(143, Formulas::accuracy(75, 0, 1), 'evasion up one takes accuracy down one: 191×75÷100');
same(255, Formulas::accuracy(100, 6, 0), 'and it is 255 at most');
same(25, Formulas::chance(10), 'a 10% effect is under 25 of 255');

// 7: experience
same(1000, Formulas::expAtLevel('mediumFast', 10), 'n³');
same(560, Formulas::expAtLevel('mediumSlow', 10), '6n³÷5 − 15n² + 100n − 140');
same(800, Formulas::expAtLevel('fast', 10), '4n³÷5');
same(1250, Formulas::expAtLevel('slow', 10), '5n³÷4');
same(1720, Formulas::expAtLevel('slightlyFast', 10), '3n³÷4 + 10n² − 30');
same(0, Formulas::expAtLevel('mediumSlow', 1), 'as meant: nothing below zero at level 1');
check(throws(fn() => Formulas::expAtLevel('sideways', 5)), 'a growth rate that is none is refused');
same(142, Formulas::expGain(100, 10, 1, false), 'experience: 100×10÷7');
same(213, Formulas::expGain(100, 10, 1, true), 'half as much again from a trainer\'s beast');
same(71, Formulas::expGain(100, 10, 2, false), 'shared by two, the base is halved first');

// 8: catching
same(63, Formulas::catchValue(190, 100, 100, null), 'at full hp: 3M=300 and 2H=200 quartered to 75 and 50; 190×25÷75');
same(187, Formulas::catchValue(190, 100, 1, null), 'at 1 hp: 190×74÷75');
same(197, Formulas::catchValue(190, 100, 1, 'sleep'), 'asleep: +10');
same(192, Formulas::catchValue(190, 100, 1, 'poison'), 'as meant: another status, +5');
same(1, Formulas::catchValue(3, 200, 200, null), 'it is 1 at least');
same(255, Formulas::catchValue(255, 20, 1, 'sleep'), 'and 255 at most');
check(Formulas::catchValue(45, 400, 1, null) > Formulas::catchValue(45, 400, 400, null), 'as meant: a beast of great hp is still easier to catch when weakened');

// 11.4: fleeing
same(null, Formulas::fleeValue(50, 50, 1), 'a beast no slower than the other always flees');
same(64, Formulas::fleeValue(40, 80, 1), 'a slower one: 40×32÷(80÷4)');
same(94, Formulas::fleeValue(40, 80, 2), 'each try before adds 30');
same(214, Formulas::fleeValue(40, 80, 6), 'and again');
same(null, Formulas::fleeValue(40, 80, 8), 'until it is sure: 64+210 is over 255');

// 7.3: the level experience makes.
same([1, 4, 5, 5, 6], array_map(fn(int $exp) => Formulas::levelAt('mediumFast', $exp), [0, 124, 125, 215, 216]), 'a level is reached with its n³, and held until the next');
same([5, 6], [Formulas::levelAt('mediumSlow', 178), Formulas::levelAt('mediumSlow', 179)], 'by each growth rate\'s own curve: 135 at level 5, 179 at level 6');
same(100, Formulas::levelAt('slow', 99999999), 'and no level is over 100');
