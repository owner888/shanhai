<?php
// 錞于毋逢之山, five hundred li north, the last of the range. The classic: north of it is 鸡号之山, whose wind is fierce; west of
// it 幽都之山, where the 浴水 rises; a great snake lives there with a red head and a white body, at whose sight there is
// drought. The god of the range keeps its end: a boar's body with eight feet and a snake's tail.
declare(strict_types=1);

return [
    'id' => 'chunyu',
    'name' => '錞于毋逢之山',
    'range' => 10,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^--:::::----:::::--^',
        '^--:::::----:::::--^',
        '^--:::::----:::::--^',
        '^------------------^',
        '^------------------^',
        '===================^',
        '^------------------^',
        '^-------:::::------^',
        '^-------:::::^^----^',
        '^-------:::::^^----^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['didu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '錞于毋逢之山 —— 北望鸡号之山，其风如䬅；西望幽都之山，浴水出焉'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedBeicisan', 'passed'],
            ['say', '彘身八足、拖着蛇尾的神立在路的尽头，北边吹来的风很大。'],
            ['ask', '「自太行至毋逢，四十六山，北山到此为止。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '北次三经之神', [['jingwei', 46], ['tianma', 46], ['beicisan', 48]], ['licao']],
            ['setFlag', 'passedBeicisan'],
            ['say', '「好。北山经三个山系，八十七山，你都走过了。想去东山经时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['give', 'sealKin', 1],
            ['give', 'sealFast', 1],
            ['give', 'sealHeavy', 1],
            ['say', '「这五枚契印也拿去：威、水、亲、速、重契印各一枚。」'],
            ['give', 'yuzhang', 1],
            ['say', '「这枚璋玉也拿去。常兽到了二十五级，带着它来找任何一位你通过了试炼的山神，就能受璋礼：六项能力都会长。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「东山经之首是樕蛛之山。要我送你去吗？」', [['去樕蛛之山', 'send'], ['留在这里', 'stay'], ['受祭', 'rite']]],
            ['label', 'send'],
            ['warp', 'sudu', 2, 7, 'right'],
            ['label', 'stay'],
            ['end'],
            ['label', 'rite'],
            ['rite'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the three areas before the reference's tenth gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 橐驼、鹠、白蛇、橐驼 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[17, 'tuotuo'], [19, 'liu'], [16, 'baishe'], [17, 'tuotuo'], [16, 'dashe'], [18, 'dashe'], [18, 'dashe']]],
];
