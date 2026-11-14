<?php
// The beasts of the first range of the Classic of Mountains and Seas' Southern Mountains (南山经之首, 鹊山).
//
// 'mountain', 'looks' and 'lore' restate what the classic says of the beast, each checked against its text
// (zh.wikisource.org/wiki/山海經/南山經); an entry is not written from memory. 'unchecked' names what is
// still to be checked against a second edition. 'element' and 'stats' are the game's own.
// stats: [hp, attack, defense, special attack, special defense, speed].
declare(strict_types=1);

return [
    'xingxing' => [
        'name' => '狌狌', 'element' => 'wind', 'mountain' => '招摇之山',
        'looks' => '形如猿猴，白耳，伏地而行，也能像人一样奔走。',
        'lore' => '食之善走。',
        'stats' => [45, 55, 35, 30, 35, 80],
    ],
    'lushu' => [
        'name' => '鹿蜀', 'element' => 'flame', 'mountain' => '杻阳之山', 'starter' => true,
        'looks' => '形如马，白首，身有虎纹，赤尾，鸣声如人歌唱。',
        'lore' => '佩之宜子孙。',
        'stats' => [50, 60, 45, 55, 45, 60],
    ],
    'xuangui' => [
        'name' => '旋龟', 'element' => 'tide', 'mountain' => '杻阳之山', 'starter' => true,
        'looks' => '怪水中的龟，鸟首蛇尾，叫声如劈木。',
        'lore' => '佩之不聋。',
        'stats' => [55, 45, 70, 50, 60, 35],
    ],
    'lu' => [
        'name' => '鯥', 'element' => 'frost', 'mountain' => '柢山',
        'looks' => '形如牛，居于山陵，蛇尾，胁下生翼。',
        'lore' => '冬死而夏生。食之无肿疾。',
        'stats' => [70, 60, 55, 40, 50, 30],
    ],
    'lei' => [
        'name' => '类', 'element' => 'wood', 'mountain' => '亶爰之山',
        'looks' => '形如狸而有长毛。',
        'lore' => '自为雌雄。食之不妒。',
        'stats' => [55, 50, 45, 60, 55, 55],
    ],
    'boyi' => [
        'name' => '猼訑', 'element' => 'rock', 'mountain' => '基山',
        'looks' => '形如羊，九尾四耳，目在背上。',
        'lore' => '佩之不畏。',
        'stats' => [65, 55, 75, 35, 60, 30],
    ],
    'changfu' => [
        'name' => '尚付', 'element' => 'thunder', 'mountain' => '基山',
        'looks' => '三首、六目、六足、三翼的鸟。',
        'lore' => '食之无卧。',
        'unchecked' => '原文用字为「𪁺𩿧」；「尚付」是通行写法，待另一版本核对。',
        'stats' => [50, 45, 40, 70, 50, 75],
    ],
    'jiuweihu' => [
        'name' => '九尾狐', 'element' => 'shadow', 'mountain' => '青丘之山',
        'looks' => '九尾之狐，声如婴儿。',
        'lore' => '能食人。食之不蛊。',
        'stats' => [75, 70, 65, 95, 85, 90],
    ],
    'guanguan' => [
        'name' => '灌灌', 'element' => 'wood', 'mountain' => '青丘之山', 'starter' => true,
        'looks' => '形如鸠，叫声如人呵斥。',
        'lore' => '佩之不惑。',
        'stats' => [45, 50, 45, 60, 50, 65],
    ],
    'chiru' => [
        'name' => '赤鱬', 'element' => 'tide', 'mountain' => '青丘之山',
        'looks' => '英水中的鱼，鱼身人面，声如鸳鸯。',
        'lore' => '',
        'unchecked' => '功效一句在所查版本中残缺，待另一版本核对后补上。',
        'stats' => [50, 40, 45, 65, 60, 55],
    ],
];
