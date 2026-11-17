<?php
// 昆仑之丘, four hundred li southwest of 槐江之山. The classic: the spirit 陆吾 keeps it, a tiger's body with nine tails,
// a human face and a tiger's claws; the beast 土蝼 lives there, which eats people, the bird 钦原, whose sting kills
// beast and tree, and the bird 鹑鸟, which keeps the god's garments; four waters rise on it: the 河, the 赤水, the 洋水
// and the 黑水.
declare(strict_types=1);

return [
    'id' => 'kunlun',
    'name' => '昆仑之丘',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^........^^........^',
        '^.,,,,,,.^^.,,,,,,.^',
        '^.,,,,,,....,,,,,,.^',
        '^.,,,,,,....,,,,,,.^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^..,,,,,.....~~~~~.^',
        '^..,,,,,.....~~~~~.^',
        '^..,,,,,.....~~~~~.^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['leyou', 0], 'right' => ['huaijiang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '昆仑之丘 —— 神陆吾司之，其神状虎身而九尾，人面而虎爪'],
            ['ifFlag', 'found_pincao', 'had_pincao'],
            ['setFlag', 'found_pincao'],
            ['give', 'pincao', 1],
            ['say', '牌子旁边有薲草。你采了一份。'],
            ['label', 'had_pincao'],
            ['ifFlag', 'found_shatang', 'had'],
            ['setFlag', 'found_shatang'],
            ['give', 'shatang', 1],
            ['say', '牌子旁边长着沙棠。你采了一份。'],
            ['label', 'had'],
        ]],
    ],
    // docs/numbers.md 32: the Lower Capital's five, where the reference has its league between its eighth and ninth gyms.
    // 陆吾 keeps the place; the four before him are the range's other named gods, each as the classic has them.
    'npcs' => [
        ['luwu', 14, 5, 'down', 'god', [
            ['ifFlag', 'passedLeague', 'done'],
            ['say', '虎身九尾、人面虎爪的神守在这里。'],
            ['ifFlag', 'passedBeishan', 'ready'],
            ['say', '「这里是帝之下都。走完北山经之首，再来。」'],
            ['end'],
            ['label', 'ready'],
            ['ask', '「我是陆吾，司天之九部和帝的苑囿。帝之下都要试一试你：西次三经的四位神先来，最后是我，一场接一场，中间不歇。输给哪一位，都要从头再来。现在就开始吗？」', [['开始', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['say', '马身人面、虎纹鸟翼的神从槐江之山来了。「我是英招，司帝之平圃，巡行四海。」'],
            ['league', '英招', [['yingzhan', 40], ['tiangou', 41], ['sanqingniao', 41], ['wenyaoyu', 41], ['dae', 42]], ['youmu']],
            ['say', '形如人而犳尾的神从蠃母之山来了。「我是长乘。天之九德，在我。」'],
            ['league', '长乘', [['jufu', 40], ['manman', 41], ['qitu', 43], ['huanshou', 42], ['junniao', 44]], ['yupei', 'dixiu']],
            ['say', '泑山的神来了，身后是落日。「我是蓐收。泑山西望，是日之所入。」'],
            ['league', '蓐收', [['chi', 42], ['shengyu', 42], ['tulou', 42], ['jiao', 43], ['aoye', 46]], ['youmu']],
            ['say', '豹尾虎齿、蓬发戴胜的神从玉山来了，长啸一声。「我是西王母，司天之厉及五残。」'],
            ['league', '西王母', [['jiao', 42], ['shengyu', 42], ['sanqingniao', 45], ['zheng', 44], ['bifang', 47]], ['yupei', 'youmu']],
            ['say', '陆吾站起身来。「该我了。」'],
            ['league', '陆吾', [['chunniao', 44], ['tulou', 47], ['qinyuan', 47], ['dae', 46], ['zheng', 46], ['aoye', 50]], ['yupei', 'dixiu']],
            ['setFlag', 'passedLeague'],
            ['say', '「好。帝之下都记下你了。往北的路，隄山的神会为你开。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'done'],
            ['say', '「帝之下都记得你。」'],
        ]],
    ],
    // The tall grass: 土蝼, 钦原 and 鹑鸟.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 鹰鸇、积蛇、鹰鸇、积蛇 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 2, 'slots' => [[16, 'yingzhan'], [16, 'jishe'], [16, 'yingzhan'], [16, 'jishe'], [15, 'chunniao'], [15, 'tulou'], [15, 'qinyuan']]],
];
