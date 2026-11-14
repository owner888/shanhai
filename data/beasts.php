<?php
// The beasts of the Classic of Mountains and Seas' Southern Mountains (南山经), all three ranges.
//
// Every field here restates what the classic says, checked against its text (zh.wikisource.org/wiki/山海經/南山經,
// which carries Guo Pu's commentary; the names and wording cross-checked with a second edition where one could be
// read). Nothing is written from memory.
//   kind      'strange': a beast the text names and describes; 'common': an ordinary animal the text says a mountain has many of
//   range     1, 2 or 3: which of the Southern Mountains' three ranges
//   mountain  where the text places it (several, when it names several)
//   looks     its shape; 'note' when that comes from the commentary, not the text
//   sound     what its call is like
//   omen      what its appearing foretells (见则…)
//   lore      what it does, or what eating or wearing it does
//   unchecked what is still to be checked against a second edition
//   elements  the game's own: one or two of data/elements.php, the first the main one, settled as docs/elements.md says
//   why       what in the text settles them
// The game's stats come with roadmap 0.5.
declare(strict_types=1);

return [
    // 南山经之首 (鹊山): 招摇之山 to 箕尾之山
    'xingxing' => [
        'name' => '狌狌', 'kind' => 'strange', 'range' => 1, 'mountain' => '招摇之山',
        'elements' => ['wood'], 'why' => '栖于多桂之山，林中的兽。',
        'looks' => '形如禺而白耳，伏地而行，也能像人一样奔走。',
        'lore' => '食之善走。',
    ],
    'baiyuan' => [
        'name' => '白猿', 'kind' => 'common', 'range' => 1, 'mountain' => '堂庭之山、发爽之山',
        'elements' => ['wood'], 'why' => '猿，栖于林木。',
        'looks' => '白色的猿。',
    ],
    'fuchong' => [
        'name' => '蝮虫', 'kind' => 'common', 'range' => 1, 'mountain' => '猨翼之山、羽山、非山',
        'elements' => ['earth'], 'why' => '蛇虫，穴居。',
        'looks' => '毒蛇一类。', 'note' => '形貌据郭璞注。',
    ],
    'lushu' => [
        'name' => '鹿蜀', 'kind' => 'strange', 'range' => 1, 'mountain' => '杻阳之山', 'starter' => true,
        'elements' => ['fire'], 'why' => '赤尾。',
        'looks' => '形如马，白首，身有虎纹，赤尾。',
        'sound' => '如人歌唱。',
        'lore' => '佩之宜子孙。',
    ],
    'xuangui' => [
        'name' => '旋龟', 'kind' => 'strange', 'range' => 1, 'mountain' => '杻阳之山', 'starter' => true,
        'elements' => ['water'], 'why' => '生于怪水中。',
        'looks' => '怪水中的龟，鸟首，虺尾。',
        'sound' => '如劈木。',
        'lore' => '佩之不聋，可以治足茧。',
    ],
    'lu' => [
        'name' => '鯥', 'kind' => 'strange', 'range' => 1, 'mountain' => '柢山',
        'elements' => ['earth'], 'why' => '居于山陵。',
        'looks' => '形如牛，居于山陵，蛇尾，胁下生翼。',
        'sound' => '如留牛。',
        'lore' => '冬死而夏生。食之无肿疾。',
    ],
    'lei' => [
        'name' => '类', 'kind' => 'strange', 'range' => 1, 'mountain' => '亶爰之山',
        'elements' => ['fire'], 'why' => '征兆、栖居、形貌都无可据，取南山经的本地属性。',
        'looks' => '形如狸而有长毛。',
        'lore' => '自为雌雄。食之不妒。',
    ],
    'boyi' => [
        'name' => '猼訑', 'kind' => 'strange', 'range' => 1, 'mountain' => '基山',
        'elements' => ['fire'], 'why' => '征兆、栖居、形貌都无可据，取南山经的本地属性。',
        'looks' => '形如羊，九尾四耳，目在背上。',
        'lore' => '佩之不畏。',
    ],
    'changfu' => [
        'name' => '尚付', 'kind' => 'strange', 'range' => 1, 'mountain' => '基山',
        'elements' => ['wood'], 'why' => '鸟。',
        'looks' => '三首、六目、六足、三翼的鸟。',
        'lore' => '食之无卧。',
        'unchecked' => '原文用字为「𪁺𩿧」；「尚付」是通行写法，待另一版本核对。',
    ],
    'jiuweihu' => [
        'name' => '九尾狐', 'kind' => 'strange', 'range' => 1, 'mountain' => '青丘之山',
        'elements' => ['fire'], 'why' => '征兆、栖居、形貌都无可据，取南山经的本地属性。',
        'looks' => '形如狐而九尾。',
        'sound' => '如婴儿。',
        'lore' => '能食人。食之不蛊。',
    ],
    'guanguan' => [
        'name' => '灌灌', 'kind' => 'strange', 'range' => 1, 'mountain' => '青丘之山', 'starter' => true,
        'elements' => ['wood'], 'why' => '鸟。',
        'looks' => '形如鸠。',
        'sound' => '如人呵斥。',
        'lore' => '佩之不惑。',
    ],
    'chiru' => [
        'name' => '赤鱬', 'kind' => 'strange', 'range' => 1, 'mountain' => '青丘之山',
        'elements' => ['water'], 'why' => '生于英水中。',
        'looks' => '英水中的鱼，鱼身人面。',
        'sound' => '如鸳鸯。',
        'unchecked' => '功效一句在所查版本中残缺，待另一版本核对后补上。',
    ],

    // 南次二经: 柜山 to 漆吴之山
    'lili' => [
        'name' => '狸力', 'kind' => 'strange', 'range' => 2, 'mountain' => '柜山',
        'elements' => ['earth'], 'why' => '见则多土功。',
        'looks' => '形如豚，足上有距。',
        'sound' => '如狗吠。',
        'omen' => '见则其县多土功。',
    ],
    'zhu' => [
        'name' => '鴸', 'kind' => 'strange', 'range' => 2, 'mountain' => '柜山',
        'elements' => ['wood'], 'why' => '鸟；它的征兆是人事，不属五行。',
        'looks' => '形如鴟，而生着人手。',
        'sound' => '如痺，叫的就是自己的名字。',
        'omen' => '见则其县多放士。',
    ],
    'changyou' => [
        'name' => '长右', 'kind' => 'strange', 'range' => 2, 'mountain' => '长右之山',
        'elements' => ['water'], 'why' => '见则大水。',
        'looks' => '形如禺而四耳。',
        'sound' => '如人呻吟。',
        'omen' => '见则郡县大水。',
    ],
    'huahuai' => [
        'name' => '猾褢', 'kind' => 'strange', 'range' => 2, 'mountain' => '尧光之山',
        'elements' => ['earth'], 'why' => '见则有大繇；穴居。',
        'looks' => '形如人而有猪鬣，穴居，冬天蛰伏。',
        'sound' => '如斫木。',
        'omen' => '见则县有大繇。',
    ],
    'zhi' => [
        'name' => '彘', 'kind' => 'strange', 'range' => 2, 'mountain' => '浮玉之山',
        'elements' => ['metal'], 'why' => '形如虎，有爪牙。',
        'looks' => '形如虎而牛尾。',
        'sound' => '如吠犬。',
        'lore' => '食人。',
    ],
    'jiyu' => [
        'name' => '鮆鱼', 'kind' => 'strange', 'range' => 2, 'mountain' => '浮玉之山',
        'elements' => ['water'], 'why' => '生于苕水中。',
        'looks' => '苕水中的鱼，身狭而薄，头长。', 'note' => '形貌据郭璞注，正文未载。',
    ],
    'huan' => [
        'name' => '䍺', 'kind' => 'strange', 'range' => 2, 'mountain' => '洵山',
        'elements' => ['fire'], 'why' => '征兆、栖居、形貌都无可据，取南山经的本地属性。',
        'looks' => '形如羊而无口。',
        'lore' => '不可杀。',
        'unchecked' => '所查版本此处山名缺字；因其水名洵水，通称洵山，待另一版本核对。',
    ],
    'biluo' => [
        'name' => '芘蠃', 'kind' => 'strange', 'range' => 2, 'mountain' => '洵山',
        'elements' => ['water'], 'why' => '生于洵水中。',
        'looks' => '洵水中的螺，紫色。', 'note' => '形貌据郭璞注，正文未载。',
    ],
    'gudiao' => [
        'name' => '蛊雕', 'kind' => 'strange', 'range' => 2, 'mountain' => '鹿吴之山',
        'elements' => ['water', 'metal'], 'why' => '生于水中；有角。',
        'looks' => '水中的兽，形如雕而有角。',
        'sound' => '如婴儿。',
        'lore' => '食人。',
    ],

    // 南次三经: 天虞之山 to 南禺之山
    'xi' => [
        'name' => '犀', 'kind' => 'common', 'range' => 3, 'mountain' => '祷过之山',
        'elements' => ['metal'], 'why' => '有角。',
        'looks' => '似水牛，猪头，黑身，三角。', 'note' => '形貌据郭璞注。',
    ],
    'si' => [
        'name' => '兕', 'kind' => 'common', 'range' => 3, 'mountain' => '祷过之山',
        'elements' => ['metal'], 'why' => '有角。',
        'looks' => '似水牛，青色，一角。', 'note' => '形貌据郭璞注。',
    ],
    'xiang' => [
        'name' => '象', 'kind' => 'common', 'range' => 3, 'mountain' => '祷过之山',
        'elements' => ['metal'], 'why' => '有长牙。',
        'looks' => '兽中最大的，长鼻，长牙。', 'note' => '形貌据郭璞注。',
    ],
    'quru' => [
        'name' => '瞿如', 'kind' => 'strange', 'range' => 3, 'mountain' => '祷过之山',
        'elements' => ['wood'], 'why' => '鸟。',
        'looks' => '形如鵁，白首，三足，人面。',
        'sound' => '叫的就是自己的名字。',
    ],
    'hujiao' => [
        'name' => '虎蛟', 'kind' => 'strange', 'range' => 3, 'mountain' => '祷过之山',
        'elements' => ['water'], 'why' => '生于泿水中。',
        'looks' => '泿水中的蛟，鱼身而蛇尾。',
        'sound' => '如鸳鸯。',
        'lore' => '食之不肿，可以治痔。',
    ],
    'fenghuang' => [
        'name' => '凤皇', 'kind' => 'strange', 'range' => 3, 'mountain' => '丹穴之山、南禺之山',
        'elements' => ['wood'], 'why' => '鸟；它的征兆是天下安宁，不属五行。',
        'looks' => '形如鸡，五彩而有纹：首纹为德，翼纹为义，背纹为礼，胸纹为仁，腹纹为信。',
        'lore' => '饮食自然，自歌自舞。',
        'omen' => '见则天下安宁。',
    ],
    'tuanyu' => [
        'name' => '鱄鱼', 'kind' => 'strange', 'range' => 3, 'mountain' => '鸡山',
        'elements' => ['fire', 'water'], 'why' => '见则天下大旱；生于水中。',
        'looks' => '形如鲋而生着猪毛。',
        'sound' => '如豚。',
        'omen' => '见则天下大旱。',
    ],
    'yong' => [
        'name' => '顒', 'kind' => 'strange', 'range' => 3, 'mountain' => '令丘之山',
        'elements' => ['fire', 'wood'], 'why' => '见则天下大旱；鸟。',
        'looks' => '形如枭，人面，四目而有耳。',
        'sound' => '叫的就是自己的名字。',
        'omen' => '见则天下大旱。',
    ],
    'yuanchu' => [
        'name' => '鵷鶵', 'kind' => 'strange', 'range' => 3, 'mountain' => '南禺之山',
        'elements' => ['wood'], 'why' => '鸟。',
        'looks' => '凤一类的鸟。', 'note' => '形貌据郭璞注，正文未载。',
    ],
];
