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
same([25, 73, 100, 0], [Formulas::catchPercent(63), Formulas::catchPercent(187), Formulas::catchPercent(255), Formulas::catchPercent(1)], 'of a hundred, how often it binds: the bytes not over it, of 256');
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

// 13: wild beasts in the grass.
same([25, 5, 15], [Formulas::wildLimit(10), Formulas::wildLimit(2), Formulas::wildLimit(6)], 'a rate in percent is that part of 255');
$slot = fn(int ...$bytes) => Formulas::wildSlot(\Shanhai\Battle\Rng::scripted($bytes));
same([0, 0, 1, 1, 2, 3, 4, 5, 5, 6], array_map($slot, [0, 29, 30, 59, 79, 89, 94, 95, 98, 99]), 'a byte under a hundred, plus one, falls to a slot: 30, 30, 20, 10, 5, 4, 1');
same(6, $slot(100, 255, 99), 'a byte of a hundred or more is asked for again');

// 8: a seal that fails seems to hold while bytes are under the limit of the first row its catch value is not over.
same([63, 63, 75, 191, 191, 201, 253, 255], array_map(Formulas::wobbleLimit(...), [0, 1, 2, 61, 80, 81, 254, 255]), 'the table\'s rows');

// 13: on water, three beasts, and up to four levels more.
same([0, 0, 1, 1, 2], array_map(fn(int $b) => Formulas::wildSlot(\Shanhai\Battle\Rng::scripted([$b]), Formulas::WATER_SLOTS), [0, 59, 60, 89, 90]), 'a byte under a hundred, plus one, falls to one of the water\'s three: 60, 30, 10');
// 35, 65, 85 and 95 in a hundred of 255 are 89, 165, 216 and 242
same([0, 0, 1, 1, 2, 2, 3, 3, 4, 4], array_map(Formulas::waterLevelBonus(...), [0, 88, 89, 164, 165, 215, 216, 241, 242, 255]), 'a byte adds none to four levels');

// 17: winning pays the base of the one beaten for each level of their last beast, four times over.
same([900, 4000, 160], [Formulas::reward(Formulas::REWARD_GOD, 9), Formulas::reward(Formulas::REWARD_GOD, 40), Formulas::reward(Formulas::REWARD_ROAD, 5)], 'a god of level 9 pays 900, of 40 pays 4000; one on the road whose last is level 5 pays 160');
same([3000, 999999, 99], [Formulas::START_MONEY, Formulas::MAX_MONEY, Formulas::MAX_STACK], 'a game begins with 3000; there can be 999999; a bag holds 99 of a thing');

// 22: a better seal makes a kind's catch rate half as much again, or twice, 255 at most.
same([45, 67, 90, 255, 255, 190], [Formulas::sealRate(45, 100), Formulas::sealRate(45, 150), Formulas::sealRate(45, 200), Formulas::sealRate(190, 150), Formulas::sealRate(255, 200), Formulas::sealRate(127, 150)],
    'the plain seal leaves the rate; 45 half as much again is 67, twice is 90; 190 half as much again is over 255, and is 255; 127 is 190');
same([45, 90, 90, 180, 180, 255, 255], array_map(fn(int $theirs) => Formulas::sealRate(45, 'level', 40, $theirs), [40, 39, 20, 19, 10, 9, 1]),
    'the seal that goes by level: nothing at the same level; twice under it; four times under half of it; eight under a quarter, 255 at most');
same([45, 135, 255], [Formulas::sealRate(45, 'water', 0, 0, false), Formulas::sealRate(45, 'water', 0, 0, true), Formulas::sealRate(120, 'water', 0, 0, true)], 'the seal of the water: three times in a battle begun on it, 255 at most');

// 23: two bytes make a beast's four individual values.
same([['atk' => 8, 'def' => 8, 'spe' => 8, 'spc' => 8], ['atk' => 15, 'def' => 0, 'spe' => 2, 'spc' => 10]], [Formulas::dvs(136, 136), Formulas::dvs(0xF0, 0x2A)], 'attack and defense are the first byte\'s halves, speed and special the second\'s');

// 25: a bond changes by what happens, less the closer it is, and stays from 0 to 255.
same([75, 103, 202, 255], [Formulas::bond(70, 'level'), Formulas::bond(100, 'level'), Formulas::bond(200, 'level'), Formulas::bond(254, 'level')], 'a level brings 5 under 100, 3 under 200, 2 after, to 255 at most');
same([71, 200, 73, 201, 69, 0, 65, 190, 71], [Formulas::bond(70, 'move'), Formulas::bond(200, 'move'), Formulas::bond(70, 'trial'), Formulas::bond(200, 'trial'), Formulas::bond(70, 'fainted'), Formulas::bond(0, 'fainted'), Formulas::bond(70, 'outmatched'), Formulas::bond(200, 'outmatched'), Formulas::bond(70, 'step')],
    'a move learned, a trial gone into, a fall, a fall to one far stronger, and the steps walked');
same([28, 88, 102, 1], [Formulas::bondPower(70), Formulas::bondPower(220), Formulas::bondPower(255), Formulas::bondPower(0)], 'the move of the bond: the bond times ten over twenty-five, 1 at least');
same(['疏远', '还生分', '渐渐熟了', '亲近', '十分亲近', '形影不离'], array_map(Formulas::bondWord(...), [49, 50, 100, 150, 200, 250]), 'the word for a bond, by the grades of 50, 100, 150, 200 and 250');

// 27: the rites. A common beast of the first tier receives the three in order, one of the second begins at the second; no other kind receives any.
same([1, 2, 3, null, 2, 3, null, null], [Formulas::nextRite('common', 1, 0), Formulas::nextRite('common', 1, 1), Formulas::nextRite('common', 1, 2), Formulas::nextRite('common', 1, 3),
    Formulas::nextRite('common', 2, 0), Formulas::nextRite('common', 2, 2), Formulas::nextRite('strange', 1, 0), Formulas::nextRite('strange', 3, 0)], 'which rite comes next');
$plain = ['name' => '白猿', 'kind' => 'common', 'tier' => 1, 'stats' => ['hp' => 45, 'atk' => 45, 'def' => 35, 'spa' => 50, 'spd' => 45, 'spe' => 70]];
same([$plain, ['hp' => 58, 'atk' => 58, 'def' => 45, 'spa' => 65, 'spd' => 58, 'spe' => 91], '白猿·璋'], [Formulas::risen($plain, 0), Formulas::risen($plain, 1)['stats'], Formulas::risen($plain, 1)['name']], 'the first leaves 131 hundredths of each base stat: 290 in all becomes 375');
same([['hp' => 76, 'atk' => 76, 'def' => 59, 'spa' => 85, 'spd' => 76, 'spe' => 120], '白猿·璧', 492], [Formulas::risen($plain, 2)['stats'], Formulas::risen($plain, 2)['name'], array_sum(Formulas::risen($plain, 3)['stats'])], 'the second 132 of that; the third leaves the stats');
$strong = ['name' => '兕', 'kind' => 'common', 'tier' => 2, 'stats' => ['hp' => 90, 'atk' => 90, 'def' => 75, 'spa' => 30, 'spd' => 45, 'spe' => 45]];
same(['hp' => 118, 'atk' => 118, 'def' => 99, 'spa' => 39, 'spd' => 59, 'spe' => 59], Formulas::risen($strong, 2)['stats'], 'one of the second tier has only the second\'s 132');
same(255, Formulas::risen(['name' => 'x', 'kind' => 'common', 'tier' => 2, 'stats' => ['hp' => 250]], 2)['stats']['hp'], 'a base stat is 255 at most');
check(throws(fn() => Formulas::risen(['name' => '鹿蜀', 'kind' => 'strange', 'tier' => 3, 'stats' => []], 1)), 'a kind that receives none is refused');
same([102, 122, 105], [Formulas::bondPower(255), Formulas::bondPower(255, true), Formulas::bondPower(220, true)], 'after the last rite the move of the bond is the bond times twelve over twenty-five');

// 29: female when the attack and the speed values together are no more than the kind's share of females.
same(['female', 'male', 'male', 'female', 'male', 'female', null], [Formulas::gender(127, 7, 15), Formulas::gender(127, 8, 0), Formulas::gender(0, 0, 0), Formulas::gender(254, 15, 15), Formulas::gender(31, 2, 0), Formulas::gender(31, 1, 15), Formulas::gender(null, 0, 0)],
    'half and half turns at an attack value of 8; none, all, one in eight; a kind with neither');
$females = 0;
for ($i = 0; $i < 256; $i++) {
    $females += Formulas::gender(Formulas::GENDER_EVEN, $i >> 4, $i & 15) === 'female' ? 1 : 0;
}
same(128, $females, 'of the 256 a beast can be, 128 are female');

// 30: the seal for those that flee: four times the catch rate of a kind that does, as it is of one that does not.
same([180, 45, 255], [Formulas::sealRate(45, 'fast', 0, 0, false, true), Formulas::sealRate(45, 'fast', 0, 0, false, false), Formulas::sealRate(120, 'fast', 0, 0, false, true)], 'four times, 255 at most');

// 31: the seal for the heavy: 20 less for the light, as it is, then 20, 30 and 40 more by the weight's class; 1 to 255.
same([25, 45, 65, 75, 85, 1, 255], [Formulas::sealRate(45, 'heavy'), ...array_map(fn(int $w) => Formulas::sealRate(45, 'heavy', 0, 0, false, false, $w), [1, 2, 3, 4]), Formulas::sealRate(15, 'heavy'), Formulas::sealRate(250, 'heavy', 0, 0, false, false, 4)],
    'by the weight\'s class');
same([20, 7, 2, 23], array_map(fn(int $w) => count(array_filter(\Shanhai\Data::beasts(), fn(array $b) => ($b['weight'] ?? 0) === $w)), [4, 3, 2, 1]), 'twenty kinds as heavy as an ox, seven as a horse, two as a bear, twenty-three as a tiger, a deer or a pig');
same([4, 3, 0], [\Shanhai\Data::beasts()['si']['weight'], \Shanhai\Data::beasts()['lushu']['weight'], \Shanhai\Data::beasts()['xingxing']['weight'] ?? 0], '兕 is like a water buffalo, 鹿蜀 like a horse, 狌狌 like an ape');
