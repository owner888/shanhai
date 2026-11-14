<?php
// The first moves. Names and effects are ours, taken from what the classic says its beasts do or foretell ('from');
// the numbers are ours, inside the bands measured from the reference (docs/numbers.md 10.4).
//   element   one of data/elements.php, which makes the move physical or special; null: of no element, physical,
//             even against everything, and no beast's own
//   power     0 for a move that does no damage
//   accuracy  in percent
//   pp        how many times it can be used
//   effect    [what, chance in percent]: what else it does. With power 0 the effect is the move, and the chance is 100.
//             burn poison paralyze sleep confuse flinch: the status, on the target
//             rain sun sandstorm: the weather, for five turns
//             up:<stat>:<stages> on the user, down:<stat>:<stages> on the target; crit: a critical hit is likelier
declare(strict_types=1);

return [
    // of no element
    'ram'       => ['name' => '冲撞', 'element' => null, 'power' => 40, 'accuracy' => 100, 'pp' => 35, 'from' => '走兽相抵。'],
    'bite'      => ['name' => '噬咬', 'element' => null, 'power' => 60, 'accuracy' => 100, 'pp' => 25, 'effect' => ['flinch', 30], 'from' => '食人之兽。'],
    'trample'   => ['name' => '践踏', 'element' => null, 'power' => 80, 'accuracy' => 100, 'pp' => 15, 'from' => '犀、兕、象。'],
    'infantcry' => ['name' => '婴啼', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 30, 'effect' => ['down:def:1', 100], 'from' => '九尾狐、蛊雕，其音如婴儿。'],
    'chant'     => ['name' => '歌谣', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 30, 'effect' => ['down:atk:1', 100], 'from' => '鹿蜀，其音如谣。'],
    'swift'     => ['name' => '善走', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 30, 'effect' => ['up:spe:2', 100], 'from' => '狌狌，食之善走。'],
    'fearless'  => ['name' => '不畏', 'element' => null, 'power' => 0, 'accuracy' => 100, 'pp' => 30, 'effect' => ['up:def:2', 100], 'from' => '猼訑，佩之不畏。'],
    'bewilder'  => ['name' => '迷惑', 'element' => null, 'power' => 0, 'accuracy' => 85, 'pp' => 20, 'effect' => ['confuse', 100], 'from' => '灌灌，佩之不惑。'],
    'slumber'   => ['name' => '冬蛰', 'element' => null, 'power' => 0, 'accuracy' => 75, 'pp' => 15, 'effect' => ['sleep', 100], 'from' => '猾褢，冬蛰。'],

    // wood: wind and what grows
    'breeze'    => ['name' => '微风', 'element' => 'wood', 'power' => 40, 'accuracy' => 100, 'pp' => 35, 'from' => '鸟。'],
    'cassia'    => ['name' => '桂华', 'element' => 'wood', 'power' => 60, 'accuracy' => 100, 'pp' => 25, 'from' => '招摇之山多桂。'],
    'gale'      => ['name' => '疾风', 'element' => 'wood', 'power' => 80, 'accuracy' => 100, 'pp' => 15, 'effect' => ['flinch', 10], 'from' => '旄山之尾，凯风自是出。'],
    'tempest'   => ['name' => '大风', 'element' => 'wood', 'power' => 110, 'accuracy' => 80, 'pp' => 5, 'from' => '见则天下大风。'],
    'duststorm' => ['name' => '风沙', 'element' => 'wood', 'power' => 0, 'accuracy' => 100, 'pp' => 10, 'effect' => ['sandstorm', 100], 'from' => '见则天下大风。'],

    // fire: drought and flame
    'spark'     => ['name' => '火星', 'element' => 'fire', 'power' => 40, 'accuracy' => 100, 'pp' => 30, 'effect' => ['burn', 10], 'from' => '赤尾。'],
    'flame'     => ['name' => '丹火', 'element' => 'fire', 'power' => 65, 'accuracy' => 100, 'pp' => 20, 'effect' => ['burn', 10], 'from' => '丹穴之山。'],
    'blaze'     => ['name' => '烈焰', 'element' => 'fire', 'power' => 90, 'accuracy' => 100, 'pp' => 15, 'effect' => ['burn', 10], 'from' => '见则有火。'],
    'scorch'    => ['name' => '赤地', 'element' => 'fire', 'power' => 120, 'accuracy' => 85, 'pp' => 5, 'effect' => ['burn', 30], 'from' => '见则天下大旱。'],
    'drought'   => ['name' => '大旱', 'element' => 'fire', 'power' => 0, 'accuracy' => 100, 'pp' => 5, 'effect' => ['sun', 100], 'from' => '鱄鱼、顒，见则天下大旱。'],

    // water: flood
    'splash'    => ['name' => '水溅', 'element' => 'water', 'power' => 40, 'accuracy' => 100, 'pp' => 30, 'from' => '水中之物。'],
    'surge'     => ['name' => '涌泉', 'element' => 'water', 'power' => 65, 'accuracy' => 100, 'pp' => 20, 'from' => '怪水、英水。'],
    'billow'    => ['name' => '怒涛', 'element' => 'water', 'power' => 90, 'accuracy' => 100, 'pp' => 15, 'from' => '临于西海之上。'],
    'deluge'    => ['name' => '洪水', 'element' => 'water', 'power' => 120, 'accuracy' => 80, 'pp' => 5, 'from' => '见则郡县大水。'],
    'flood'     => ['name' => '大水', 'element' => 'water', 'power' => 0, 'accuracy' => 100, 'pp' => 5, 'effect' => ['rain', 100], 'from' => '长右，见则郡县大水。'],

    // earth: hills, burrows, labour
    'pebble'    => ['name' => '掷石', 'element' => 'earth', 'power' => 40, 'accuracy' => 100, 'pp' => 30, 'from' => '山石。'],
    'burrow'    => ['name' => '穴击', 'element' => 'earth', 'power' => 60, 'accuracy' => 100, 'pp' => 20, 'from' => '猾褢，穴居。'],
    'rockfall'  => ['name' => '落石', 'element' => 'earth', 'power' => 80, 'accuracy' => 90, 'pp' => 15, 'effect' => ['flinch', 30], 'from' => '山陵。'],
    'landslide' => ['name' => '土功', 'element' => 'earth', 'power' => 110, 'accuracy' => 85, 'pp' => 5, 'from' => '狸力，见则其县多土功。'],

    // metal: horn, spur, claw, tusk
    'claw'      => ['name' => '利爪', 'element' => 'metal', 'power' => 40, 'accuracy' => 100, 'pp' => 30, 'from' => '虎爪。'],
    'spur'      => ['name' => '距击', 'element' => 'metal', 'power' => 60, 'accuracy' => 100, 'pp' => 25, 'effect' => ['crit', 100], 'from' => '狸力，有距。'],
    'gore'      => ['name' => '角抵', 'element' => 'metal', 'power' => 80, 'accuracy' => 100, 'pp' => 15, 'from' => '犀、兕、蛊雕，有角。'],
    'tusk'      => ['name' => '象牙', 'element' => 'metal', 'power' => 110, 'accuracy' => 85, 'pp' => 5, 'from' => '象。'],
];
