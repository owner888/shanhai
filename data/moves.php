<?php
// The moves. Names and effects are ours, taken from what the classic says its beasts are, do or foretell ('from');
// the numbers are ours, inside the bands measured from the reference (docs/numbers.md 10.4).
//   element   one of data/elements.php, which makes the move physical or special; null: of no element, physical,
//             even against everything, and no beast's own
//   power     0 for a move that does no damage
//   accuracy  in percent
//   pp        how many times it can be used
//   effect    [what, chance in percent]: what else it does. With power 0 the effect is the move, and the chance is 100.
//             burn poison paralyze sleep confuse flinch: the status, on the target
//             rain sun sandstorm: the weather, for five turns
//             up:<stat>:<stages> on the user, down:<stat>:<stages> on the target (atk def spa spd spe acc eva)
//             crit: a critical hit is likelier; heal: the user regains half its hp; drain: the user regains half the damage done
//   only      the one beast whose move it is
declare(strict_types=1);

return [
    // of no element: what any beast does with its body
    'ram'       => ['name' => '冲撞', 'element' => null, 'power' => 40, 'accuracy' => 100, 'pp' => 35, 'from' => '走兽相抵。'],
    'tailwhip'  => ['name' => '尾击', 'element' => null, 'power' => 50, 'accuracy' => 100, 'pp' => 30, 'from' => '牛尾、蛇尾、虺尾。'],
    'bite'      => ['name' => '噬咬', 'element' => null, 'power' => 60, 'accuracy' => 100, 'pp' => 25, 'effect' => ['flinch', 30], 'from' => '食人之兽。'],
    'grapple'   => ['name' => '搏击', 'element' => null, 'power' => 70, 'accuracy' => 100, 'pp' => 20, 'from' => '形如禺、如人的兽。'],
    'devour'    => ['name' => '食人', 'element' => null, 'power' => 75, 'accuracy' => 100, 'pp' => 15, 'effect' => ['drain', 100], 'from' => '彘、蛊雕、九尾狐，是食人。'],
    'trample'   => ['name' => '践踏', 'element' => null, 'power' => 80, 'accuracy' => 100, 'pp' => 15, 'from' => '犀、兕、象。'],
    'pounce'    => ['name' => '猛扑', 'element' => null, 'power' => 100, 'accuracy' => 90, 'pp' => 10, 'from' => '形如虎的兽。'],

    // of no element: cries, and what wearing or eating a beast does
    'infantcry' => ['name' => '婴啼', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 30, 'effect' => ['down:def:1', 100], 'from' => '九尾狐、蛊雕，其音如婴儿。'],
    'chant'     => ['name' => '歌谣', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 30, 'effect' => ['down:atk:1', 100], 'from' => '鹿蜀，其音如谣。'],
    'rebuke'    => ['name' => '呵斥', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 20, 'effect' => ['down:atk:2', 100], 'from' => '灌灌，其音若呵。'],
    'woodsplit' => ['name' => '判木', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 30, 'effect' => ['down:spd:1', 100], 'from' => '旋龟，其音如判木。'],
    'lowing'    => ['name' => '留牛', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 30, 'effect' => ['down:spe:1', 100], 'from' => '鯥，其音如留牛。'],
    'swift'     => ['name' => '善走', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 30, 'effect' => ['up:spe:2', 100], 'from' => '狌狌，食之善走。'],
    'fearless'  => ['name' => '不畏', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 30, 'effect' => ['up:def:2', 100], 'from' => '猼訑，佩之不畏。'],
    'undeaf'    => ['name' => '不聋', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 30, 'effect' => ['up:spd:2', 100], 'from' => '旋龟，佩之不聋。'],
    'sleepless' => ['name' => '无卧', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 20, 'effect' => ['up:spa:2', 100], 'from' => '尚付，食之无卧。'],
    'stripes'   => ['name' => '虎文', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 20, 'effect' => ['up:atk:2', 100], 'from' => '鹿蜀，其文如虎。'],
    'backeyes'  => ['name' => '背目', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 15, 'effect' => ['up:eva:1', 100], 'from' => '猼訑，其目在背。'],
    'bewilder'  => ['name' => '迷惑', 'element' => null, 'power' => 0, 'accuracy' => 85, 'pp' => 20, 'effect' => ['confuse', 100], 'from' => '灌灌，佩之不惑。'],
    'slumber'   => ['name' => '冬蛰', 'element' => null, 'power' => 0, 'accuracy' => 75, 'pp' => 15, 'effect' => ['sleep', 100], 'from' => '猾褢，冬蛰。'],
    'venom'     => ['name' => '蛊毒', 'element' => null, 'power' => 0, 'accuracy' => 90, 'pp' => 30, 'effect' => ['poison', 100], 'from' => '九尾狐，食者不蛊。'],
    'sting'     => ['name' => '蝮螫', 'element' => null, 'power' => 0, 'accuracy' => 75, 'pp' => 30, 'effect' => ['paralyze', 100], 'from' => '蝮虫。'],
    'revive'    => ['name' => '夏生', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 10, 'effect' => ['heal', 100], 'from' => '鯥，冬死而夏生。'],
    'dance'     => ['name' => '自舞', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 20, 'effect' => ['up:spd:1', 100], 'only' => 'fenghuang', 'from' => '凤皇，自歌自舞。'],

    // wood: wind, birds, what grows
    'breeze'    => ['name' => '微风', 'element' => 'wood', 'power' => 40, 'accuracy' => 100, 'pp' => 35, 'from' => '鸟。'],
    'feathers'  => ['name' => '落羽', 'element' => 'wood', 'power' => 50, 'accuracy' => 100, 'pp' => 30, 'effect' => ['down:spe:1', 10], 'from' => '鸟。'],
    'cassia'    => ['name' => '桂华', 'element' => 'wood', 'power' => 60, 'accuracy' => 100, 'pp' => 25, 'from' => '招摇之山多桂。'],
    'selfcall'  => ['name' => '自号', 'element' => 'wood', 'power' => 70, 'accuracy' => 100, 'pp' => 20, 'effect' => ['confuse', 10], 'from' => '鴸、瞿如、顒，其鸣自号。'],
    'gale'      => ['name' => '疾风', 'element' => 'wood', 'power' => 80, 'accuracy' => 100, 'pp' => 15, 'effect' => ['flinch', 10], 'from' => '旄山之尾。'],
    'southwind' => ['name' => '凯风', 'element' => 'wood', 'power' => 100, 'accuracy' => 90, 'pp' => 10, 'from' => '旄山之尾，凯风自是出。'],
    'tempest'   => ['name' => '大风', 'element' => 'wood', 'power' => 110, 'accuracy' => 80, 'pp' => 5, 'from' => '见则天下大风。'],
    'fivehues'  => ['name' => '五采', 'element' => 'wood', 'power' => 100, 'accuracy' => 100, 'pp' => 10, 'effect' => ['down:spd:1', 20], 'only' => 'fenghuang', 'from' => '凤皇，五采而文。'],
    'duststorm' => ['name' => '风沙', 'element' => 'wood', 'power' => 0, 'accuracy' => 100, 'pp' => 10, 'effect' => ['sandstorm', 100], 'from' => '见则天下大风。'],

    // fire: red, drought, flame
    'spark'     => ['name' => '火星', 'element' => 'fire', 'power' => 40, 'accuracy' => 100, 'pp' => 30, 'effect' => ['burn', 10], 'from' => '赤色。'],
    'redtail'   => ['name' => '赤尾', 'element' => 'fire', 'power' => 50, 'accuracy' => 100, 'pp' => 25, 'effect' => ['crit', 100], 'from' => '鹿蜀，赤尾。'],
    'flame'     => ['name' => '丹火', 'element' => 'fire', 'power' => 65, 'accuracy' => 100, 'pp' => 20, 'effect' => ['burn', 10], 'from' => '丹穴之山。'],
    'drywind'   => ['name' => '旱气', 'element' => 'fire', 'power' => 70, 'accuracy' => 100, 'pp' => 20, 'effect' => ['down:spd:1', 10], 'from' => '见则天下大旱。'],
    'blaze'     => ['name' => '烈焰', 'element' => 'fire', 'power' => 90, 'accuracy' => 100, 'pp' => 15, 'effect' => ['burn', 10], 'from' => '见则有火。'],
    'wildfire'  => ['name' => '燎原', 'element' => 'fire', 'power' => 100, 'accuracy' => 90, 'pp' => 10, 'effect' => ['burn', 10], 'from' => '见则有火。'],
    'scorch'    => ['name' => '赤地', 'element' => 'fire', 'power' => 120, 'accuracy' => 85, 'pp' => 5, 'effect' => ['burn', 30], 'from' => '见则天下大旱。'],
    'ninetails' => ['name' => '九尾', 'element' => 'fire', 'power' => 95, 'accuracy' => 100, 'pp' => 10, 'effect' => ['confuse', 30], 'only' => 'jiuweihu', 'from' => '九尾狐。'],
    'drought'   => ['name' => '大旱', 'element' => 'fire', 'power' => 0, 'accuracy' => 100, 'pp' => 5, 'effect' => ['sun', 100], 'from' => '鱄鱼、顒，见则天下大旱。'],

    // water: rivers, the sea, flood
    'splash'    => ['name' => '水溅', 'element' => 'water', 'power' => 40, 'accuracy' => 100, 'pp' => 30, 'from' => '水中之物。'],
    'foam'      => ['name' => '濡沫', 'element' => 'water', 'power' => 50, 'accuracy' => 100, 'pp' => 30, 'effect' => ['down:spe:1', 10], 'from' => '水中之鱼。'],
    'surge'     => ['name' => '涌泉', 'element' => 'water', 'power' => 65, 'accuracy' => 100, 'pp' => 20, 'from' => '怪水、英水。'],
    'whirl'     => ['name' => '旋流', 'element' => 'water', 'power' => 70, 'accuracy' => 100, 'pp' => 20, 'effect' => ['confuse', 10], 'from' => '旋龟。'],
    'billow'    => ['name' => '怒涛', 'element' => 'water', 'power' => 90, 'accuracy' => 100, 'pp' => 15, 'from' => '临于西海之上。'],
    'seatide'   => ['name' => '海潮', 'element' => 'water', 'power' => 100, 'accuracy' => 90, 'pp' => 10, 'effect' => ['down:spe:1', 30], 'from' => '南流注于海。'],
    'deluge'    => ['name' => '洪水', 'element' => 'water', 'power' => 120, 'accuracy' => 80, 'pp' => 5, 'from' => '见则郡县大水。'],
    'darkshell' => ['name' => '玄甲', 'element' => 'water', 'power' => 85, 'accuracy' => 100, 'pp' => 15, 'effect' => ['up:def:1', 10], 'only' => 'xuangui', 'from' => '旋龟，玄龟。'],
    'flood'     => ['name' => '大水', 'element' => 'water', 'power' => 0, 'accuracy' => 100, 'pp' => 5, 'effect' => ['rain', 100], 'from' => '长右，见则郡县大水。'],

    // earth: hills, burrows, labour
    'pebble'    => ['name' => '掷石', 'element' => 'earth', 'power' => 40, 'accuracy' => 100, 'pp' => 30, 'from' => '山石。'],
    'dust'      => ['name' => '扬尘', 'element' => 'earth', 'power' => 50, 'accuracy' => 95, 'pp' => 30, 'effect' => ['down:acc:1', 30], 'from' => '山陵。'],
    'burrow'    => ['name' => '穴击', 'element' => 'earth', 'power' => 60, 'accuracy' => 100, 'pp' => 20, 'from' => '猾褢，穴居。'],
    'hillcharge' => ['name' => '陵突', 'element' => 'earth', 'power' => 70, 'accuracy' => 100, 'pp' => 20, 'from' => '鯥，陵居。'],
    'rockfall'  => ['name' => '落石', 'element' => 'earth', 'power' => 80, 'accuracy' => 90, 'pp' => 15, 'effect' => ['flinch', 30], 'from' => '山陵。'],
    'cliffbreak' => ['name' => '崩崖', 'element' => 'earth', 'power' => 100, 'accuracy' => 90, 'pp' => 10, 'effect' => ['flinch', 10], 'from' => '其下多水，不可以上。'],
    'landslide' => ['name' => '土功', 'element' => 'earth', 'power' => 110, 'accuracy' => 85, 'pp' => 5, 'from' => '狸力，见则其县多土功。'],

    // metal: horn, spur, claw, tusk, beak
    'claw'      => ['name' => '利爪', 'element' => 'metal', 'power' => 40, 'accuracy' => 100, 'pp' => 30, 'from' => '虎爪。'],
    'peck'      => ['name' => '喙啄', 'element' => 'metal', 'power' => 50, 'accuracy' => 100, 'pp' => 30, 'from' => '鸟喙。'],
    'spur'      => ['name' => '距击', 'element' => 'metal', 'power' => 60, 'accuracy' => 100, 'pp' => 25, 'effect' => ['crit', 100], 'from' => '狸力，有距。'],
    'fang'      => ['name' => '虎牙', 'element' => 'metal', 'power' => 70, 'accuracy' => 100, 'pp' => 20, 'effect' => ['flinch', 10], 'from' => '彘，形如虎。'],
    'gore'      => ['name' => '角抵', 'element' => 'metal', 'power' => 80, 'accuracy' => 100, 'pp' => 15, 'from' => '犀、兕、蛊雕，有角。'],
    'onehorn'   => ['name' => '独角', 'element' => 'metal', 'power' => 100, 'accuracy' => 90, 'pp' => 10, 'effect' => ['crit', 100], 'from' => '兕，一角。'],
    'tusk'      => ['name' => '象牙', 'element' => 'metal', 'power' => 110, 'accuracy' => 85, 'pp' => 5, 'from' => '象。'],
    // docs/numbers.md 25: with `bond`, its power is not the one written but the user's bond with the player times ten over twenty-five, 102 at most;
    // a beast comes to it of itself, close enough.
    'tongxin' => ['name' => '同心', 'element' => null, 'power' => 100, 'accuracy' => 100, 'pp' => 15, 'bond' => true, 'from' => '与人同心，其力自倍。游戏自己的设定。'],
];
