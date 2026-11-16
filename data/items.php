<?php
// The things a bag holds.
//   name   as it is shown
//   use    what it does when used: 'catch', in a battle with a wild beast, tries to bind it (docs/numbers.md 8, 22); used on a beast of the
//          party (docs/numbers.md 21): 'heal' restores hp, 'cure' ends a status, 'revive' stands a fallen one up, 'restore' makes it whole and well
//   hold   what it does held by a beast (docs/numbers.md 18, 19): share, flee, leftovers, first, endure, evade, calm, flinch, crit, coin,
//          boost, heal, cure or pp
//   by     the number or the kind it does it by: a chance of 256, hp (or all), a part of the hp at most, an element (or none), a status (or any),
//          for a seal the hundredths of the catch rate it makes it, or level, water, bond or sure
//   grows  the mountain the classic has it on: a wild beast of that mountain may hold it (docs/numbers.md 20)
//   price  what one costs where it is sold (docs/numbers.md 17)
//   from   what in the classic, or in the game's own story, it comes from
declare(strict_types=1);

return [
    'seal' => ['name' => '契印', 'use' => 'catch', 'price' => 200, 'from' => '巫祝所制，人与异兽结契的凭信。游戏自己的设定。'],
    // Medicines (docs/numbers.md 21): used on a beast from the bag, in a battle or out of one. Each is the classic's, as above.
    'zhuyu' => ['name' => '祝余', 'use' => 'heal', 'by' => 20, 'price' => 300, 'from' => '招摇之山：「食之不饥」。回复 20 点体力。'],
    'bili' => ['name' => '萆荔', 'use' => 'heal', 'by' => 50, 'price' => 700, 'from' => '小华之山：「食之已心痛」。回复 50 点体力。'],
    'licao' => ['name' => '梨', 'use' => 'heal', 'by' => 200, 'price' => 1200, 'from' => '中次七经的太山：草名，「可以已疽」。回复 200 点体力。'],
    'youmu' => ['name' => '栯木', 'use' => 'heal', 'by' => 'all', 'price' => 2500, 'from' => '泰室之山：「服者不妒」。体力全满。'],
    'dixiu' => ['name' => '帝休', 'use' => 'restore', 'price' => 3000, 'from' => '少室之山：「服者不怒」。体力全满，异常全消。'],
    'jibai' => ['name' => '葪柏', 'use' => 'revive', 'price' => 1500, 'from' => '敏山：「服者不寒」。倒下的异兽站起来，体力一半。'],
    // Better seals (docs/numbers.md 22): `by` is how many hundredths of a kind's catch rate one makes it.
    'seal2' => ['name' => '玉契印', 'use' => 'catch', 'by' => 150, 'price' => 600, 'from' => '刻在玉上的契印，比寻常的更容易成契。游戏自己的设定。'],
    'sealLevel' => ['name' => '威契印', 'use' => 'catch', 'by' => 'level', 'from' => '野生异兽的等级比你场上的那只低，就更容易成契：低于它两倍，低过一半四倍，低过四分之一八倍。游戏自己的设定。'],
    'sealWater' => ['name' => '水契印', 'use' => 'catch', 'by' => 'water', 'from' => '在水面上遇到的异兽，三倍容易成契。游戏自己的设定。'],
    'sealKin' => ['name' => '亲契印', 'use' => 'catch', 'by' => 'bond', 'from' => '用它结契的异兽，一上来就和你十分亲近。游戏自己的设定。'],
    // docs/numbers.md 27: the jades of the rites a common beast receives from a god whose trial is passed.
    'yuzhang' => ['name' => '璋玉', 'use' => 'rite', 'by' => 1, 'price' => 2100, 'from' => '南山经之首祠神之礼：「毛用一璋玉瘞」。常兽二十五级起，凭它受璋礼。'],
    'yubi' => ['name' => '璧玉', 'use' => 'rite', 'by' => 2, 'price' => 2100, 'from' => '南次二经祠神之礼：「毛用一璧瘞」。受过璋礼的常兽、或本就不弱的常兽，三十六级起，凭它受璧礼。'],
    'yugui' => ['name' => '圭玉', 'use' => 'rite', 'by' => 3, 'price' => 2100, 'from' => '中次八经祠神之礼：「用一藻圭」。受过璧礼、又与你十分亲近的，凭它受圭礼。'],
    'sealFast' => ['name' => '速契印', 'use' => 'catch', 'by' => 'fast', 'from' => '对见人就逃的异兽，成契的机会是四倍。游戏自己的设定。'],
    'sealHeavy' => ['name' => '重契印', 'use' => 'catch', 'by' => 'heavy', 'from' => '越重的异兽越容易成契，轻的反而更难。游戏自己的设定。'],
    'sealSure' => ['name' => '天契印', 'use' => 'catch', 'by' => 'sure', 'from' => '一定成契。全天下只有一枚。游戏自己的设定。'],
    'seal3' => ['name' => '金契印', 'use' => 'catch', 'by' => 200, 'price' => 1200, 'from' => '嵌金的契印，最容易成契。游戏自己的设定。'],
    // What a beast can hold (docs/numbers.md 19): each the classic's own, named with its mountain and what the classic says of
    // it; what it does held is the game's. Those with no price are found once, on their mountain; those with one are sold.
    'mihu' => ['name' => '迷榖', 'hold' => 'flee', 'grows' => '招摇之山', 'from' => '招摇之山：「佩之不迷」。拿着它的异兽，野外的战斗一定逃得掉。'],
    'danmu' => ['name' => '丹木', 'hold' => 'leftovers', 'grows' => '峚山', 'by' => 16, 'from' => '峚山：「食之不饥」。拿着它的异兽，每回合末回复十六分之一体力。'],
    'duheng' => ['name' => '杜衡', 'hold' => 'first', 'grows' => '天帝之山', 'by' => 60, 'from' => '天帝之山：「可以走马」。拿着它的异兽，有时抢在对手前面行动。'],
    'jinyu' => ['name' => '瑾瑜之玉', 'hold' => 'endure', 'grows' => '钟山', 'by' => 30, 'from' => '钟山：「君子服之，以御不祥」。拿着它的异兽，有时在会倒下的一击里撑住，剩一点体力。'],
    'niushang' => ['name' => '牛伤', 'hold' => 'evade', 'grows' => '大苦之山', 'by' => 20, 'from' => '大苦山：「可以御兵」。拿着它的异兽，对手的招式更难打中。'],
    'diwu' => ['name' => '帝屋', 'hold' => 'calm', 'grows' => '讲山', 'from' => '讲山：「可以御凶」。队里有异兽拿着它，野外遇到异兽的机会减半。'],
    'yaocao' => ['name' => '䔄草', 'hold' => 'flinch', 'grows' => '姑媱之山', 'by' => 30, 'from' => '姑媱山：「服之媚于人」。拿着它的异兽出招打中，对手有时畏缩。'],
    'tuo' => ['name' => '箨', 'hold' => 'crit', 'grows' => '甘枣之山', 'from' => '甘枣之山：「可以已瞢」。拿着它的异兽更容易击中要害。'],
    'jigu' => ['name' => '鸡谷', 'hold' => 'coin', 'grows' => '兔床之山', 'from' => '兔床之山：「食者利于人」。拿着它的异兽上过场，打赢得的贝加倍。'],
    'huaimu' => ['name' => '櫰木', 'hold' => 'boost', 'grows' => '中曲之山', 'by' => 'none', 'from' => '中曲之山：「食之多力」。拿着它的异兽，无属性招式的伤害多一成。'],
    'jiarong' => ['name' => '嘉荣', 'hold' => 'boost', 'grows' => '半石之山', 'by' => 'fire', 'from' => '半石之山：「服之者不畏霆」。拿着它的异兽，火属性招式的伤害多一成。'],
    'shatang' => ['name' => '沙棠', 'hold' => 'boost', 'grows' => '昆仑之丘', 'by' => 'water', 'from' => '昆仑之丘：「可以御水」。拿着它的异兽，水属性招式的伤害多一成。'],
    'xuncao' => ['name' => '薰草', 'hold' => 'boost', 'grows' => '浮山', 'by' => 'wood', 'from' => '浮山：「佩之可以已疠」。拿着它的异兽，木属性招式的伤害多一成。'],
    'yushi' => ['name' => '礜', 'hold' => 'boost', 'grows' => '皋涂之山', 'by' => 'metal', 'from' => '皋涂之山：「可以毒鼠」。拿着它的异兽，金属性招式的伤害多一成。'],
    'liuzhe' => ['name' => '流赭', 'hold' => 'boost', 'grows' => '石脆之山', 'by' => 'earth', 'from' => '石脆之山：「以涂牛马无病」。拿着它的异兽，土属性招式的伤害多一成。'],
    'baigao' => ['name' => '白䓘', 'use' => 'heal', 'hold' => 'heal', 'grows' => '仑者之山', 'by' => 10, 'from' => '仑者之山：「食者不饥，可以释劳」。拿着它的异兽体力不到一半时吃掉，回复 10 点。'],
    'jiaguo' => ['name' => '嘉果', 'use' => 'heal', 'hold' => 'heal', 'grows' => '不周之山', 'by' => 20, 'from' => '不周之山：「食之不劳」。拿着它的异兽体力不到一半时吃掉，回复 20 点。'],
    'pincao' => ['name' => '薲草', 'use' => 'heal', 'hold' => 'heal', 'grows' => '昆仑之丘', 'by' => 30, 'from' => '昆仑之丘：「食之已劳」。拿着它的异兽体力不到一半时吃掉，回复 30 点。'],
    'kangmu' => ['name' => '亢木', 'use' => 'cure', 'hold' => 'cure', 'grows' => '浮戏之山', 'by' => 'poison', 'price' => 100, 'from' => '浮戏之山：「食之不蛊」。拿着它的异兽中毒时吃掉，解毒。'],
    'huangguan' => ['name' => '黄雚', 'use' => 'cure', 'hold' => 'cure', 'grows' => '竹山', 'by' => 'burn', 'price' => 250, 'from' => '竹山：「浴之已疥，又可以已胕」。拿着它的异兽灼伤时用掉，治好灼伤。'],
    'rongcao' => ['name' => '荣草', 'use' => 'cure', 'hold' => 'cure', 'grows' => '鼓镫之山', 'by' => 'paralyze', 'price' => 200, 'from' => '鼓镫之山：「食之已风」。拿着它的异兽麻痹时吃掉，治好麻痹。'],
    'zhichu' => ['name' => '植楮', 'use' => 'cure', 'hold' => 'cure', 'grows' => '脱扈之山', 'by' => 'sleep', 'price' => 250, 'from' => '脱扈之山：「食之不眯」。拿着它的异兽睡着时吃掉，醒过来。'],
    'mengmu' => ['name' => '蒙木', 'use' => 'cure', 'hold' => 'cure', 'grows' => '放皋之山', 'by' => 'confuse', 'from' => '放皋之山：「服之不惑」。拿着它的异兽混乱时吃掉，清醒过来。'],
    'yupei' => ['name' => '育沛', 'use' => 'cure', 'hold' => 'cure', 'grows' => '招摇之山', 'by' => 'any', 'price' => 600, 'from' => '招摇之山的丽之水：「佩之无瘕疾」。拿着它的异兽有任何异常时用掉，全都治好。'],
    'ditai' => ['name' => '帝台之浆', 'hold' => 'pp', 'grows' => '高前之山', 'by' => 5, 'from' => '高前之山：「饮之者不心痛」。拿着它的异兽有招式次数用完时喝掉，那一招回复 5 次。'],
    'tongxiu' => ['name' => '同修玉', 'hold' => 'share', 'from' => '佩着它的异兽，不上场也分得一半修行。游戏自己的设定；山经里处处有玉。'],
];
