<?php
// The things a bag holds.
//   name   as it is shown
//   use    what it does when used: 'catch', in a battle with a wild beast, tries to bind it (docs/numbers.md 8); none: nothing yet
//   hold   what it does held by a beast (docs/numbers.md 18, 19): share, flee, leftovers, first, endure, evade, calm, flinch, crit, coin,
//          boost, heal, cure or pp
//   by     the number or the kind it does it by: a chance of 256, hp, a part of the hp at most, an element (or none), a status (or any)
//   price  what one costs where it is sold (docs/numbers.md 17)
//   from   what in the classic, or in the game's own story, it comes from
declare(strict_types=1);

return [
    'seal' => ['name' => '契印', 'use' => 'catch', 'price' => 200, 'from' => '巫祝所制，人与异兽结契的凭信。游戏自己的设定。'],
    // What a beast can hold (docs/numbers.md 19): each the classic's own, named with its mountain and what the classic says of
    // it; what it does held is the game's. Those with no price are found once, on their mountain; those with one are sold.
    'mihu' => ['name' => '迷榖', 'hold' => 'flee', 'from' => '招摇之山：「佩之不迷」。拿着它的异兽，野外的战斗一定逃得掉。'],
    'danmu' => ['name' => '丹木', 'hold' => 'leftovers', 'by' => 16, 'from' => '峚山：「食之不饥」。拿着它的异兽，每回合末回复十六分之一体力。'],
    'duheng' => ['name' => '杜衡', 'hold' => 'first', 'by' => 60, 'from' => '天帝之山：「可以走马」。拿着它的异兽，有时抢在对手前面行动。'],
    'jinyu' => ['name' => '瑾瑜之玉', 'hold' => 'endure', 'by' => 30, 'from' => '钟山：「君子服之，以御不祥」。拿着它的异兽，有时在会倒下的一击里撑住，剩一点体力。'],
    'niushang' => ['name' => '牛伤', 'hold' => 'evade', 'by' => 20, 'from' => '大苦山：「可以御兵」。拿着它的异兽，对手的招式更难打中。'],
    'diwu' => ['name' => '帝屋', 'hold' => 'calm', 'from' => '讲山：「可以御凶」。队里有异兽拿着它，野外遇到异兽的机会减半。'],
    'yaocao' => ['name' => '䔄草', 'hold' => 'flinch', 'by' => 30, 'from' => '姑媱山：「服之媚于人」。拿着它的异兽出招打中，对手有时畏缩。'],
    'tuo' => ['name' => '箨', 'hold' => 'crit', 'from' => '甘枣之山：「可以已瞢」。拿着它的异兽更容易击中要害。'],
    'jigu' => ['name' => '鸡谷', 'hold' => 'coin', 'from' => '兔床之山：「食者利于人」。拿着它的异兽上过场，打赢得的贝加倍。'],
    'huaimu' => ['name' => '櫰木', 'hold' => 'boost', 'by' => 'none', 'from' => '中曲之山：「食之多力」。拿着它的异兽，无属性招式的伤害多一成。'],
    'jiarong' => ['name' => '嘉荣', 'hold' => 'boost', 'by' => 'fire', 'from' => '半石之山：「服之者不畏霆」。拿着它的异兽，火属性招式的伤害多一成。'],
    'shatang' => ['name' => '沙棠', 'hold' => 'boost', 'by' => 'water', 'from' => '昆仑之丘：「可以御水」。拿着它的异兽，水属性招式的伤害多一成。'],
    'xuncao' => ['name' => '薰草', 'hold' => 'boost', 'by' => 'wood', 'from' => '浮山：「佩之可以已疠」。拿着它的异兽，木属性招式的伤害多一成。'],
    'yushi' => ['name' => '礜', 'hold' => 'boost', 'by' => 'metal', 'from' => '皋涂之山：「可以毒鼠」。拿着它的异兽，金属性招式的伤害多一成。'],
    'liuzhe' => ['name' => '流赭', 'hold' => 'boost', 'by' => 'earth', 'from' => '石脆之山：「以涂牛马无病」。拿着它的异兽，土属性招式的伤害多一成。'],
    'baigao' => ['name' => '白䓘', 'hold' => 'heal', 'by' => 10, 'price' => 10, 'from' => '仑者之山：「食者不饥，可以释劳」。拿着它的异兽体力不到一半时吃掉，回复 10 点。'],
    'jiaguo' => ['name' => '嘉果', 'hold' => 'heal', 'by' => 20, 'price' => 100, 'from' => '不周之山：「食之不劳」。拿着它的异兽体力不到一半时吃掉，回复 20 点。'],
    'pincao' => ['name' => '薲草', 'hold' => 'heal', 'by' => 30, 'price' => 10, 'from' => '昆仑之丘：「食之已劳」。拿着它的异兽体力不到一半时吃掉，回复 30 点。'],
    'kangmu' => ['name' => '亢木', 'hold' => 'cure', 'by' => 'poison', 'price' => 10, 'from' => '浮戏之山：「食之不蛊」。拿着它的异兽中毒时吃掉，解毒。'],
    'huangguan' => ['name' => '黄雚', 'hold' => 'cure', 'by' => 'burn', 'price' => 10, 'from' => '竹山：「浴之已疥，又可以已胕」。拿着它的异兽灼伤时用掉，治好灼伤。'],
    'rongcao' => ['name' => '荣草', 'hold' => 'cure', 'by' => 'paralyze', 'price' => 10, 'from' => '鼓镫之山：「食之已风」。拿着它的异兽麻痹时吃掉，治好麻痹。'],
    'zhichu' => ['name' => '植楮', 'hold' => 'cure', 'by' => 'sleep', 'price' => 10, 'from' => '脱扈之山：「食之不眯」。拿着它的异兽睡着时吃掉，醒过来。'],
    'mengmu' => ['name' => '蒙木', 'hold' => 'cure', 'by' => 'confuse', 'price' => 10, 'from' => '放皋之山：「服之不惑」。拿着它的异兽混乱时吃掉，清醒过来。'],
    'yupei' => ['name' => '育沛', 'hold' => 'cure', 'by' => 'any', 'price' => 10, 'from' => '招摇之山的丽之水：「佩之无瘕疾」。拿着它的异兽有任何异常时用掉，全都治好。'],
    'ditai' => ['name' => '帝台之浆', 'hold' => 'pp', 'by' => 5, 'price' => 10, 'from' => '高前之山：「饮之者不心痛」。拿着它的异兽有招式次数用完时喝掉，那一招回复 5 次。'],
    'tongxiu' => ['name' => '同修玉', 'hold' => 'share', 'from' => '佩着它的异兽，不上场也分得一半修行。游戏自己的设定；山经里处处有玉。'],
];
