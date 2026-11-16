<?php
// 翼望之山, a hundred li west of 泑山 by water, the last of the range. The classic: no grass or trees, much gold and
// jade; the beast 讙 lives there, one eye and three tails, and the bird 鵸鵌, three heads and six tails, which laughs;
// both keep ill away. The god of the range, a sheep's body with a human face, keeps its end.
declare(strict_types=1);

return [
    'id' => 'yiwang',
    'name' => '翼望之山',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^---:::::---:::::--^',
        '^---:::::---:::::--^',
        '^---:::::---:::::--^',
        '^------------------^',
        '^------------------^',
        '^===================',
        '^------------------^',
        '^--------:::::-----^',
        '^--------:::::-----^',
        '^--------:::::-----^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['right' => ['youshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '翼望之山 —— 无草木，多金玉'],
        ]],
    ],
    'npcs' => [
        ['god', 1, 7, 'right', 'god', [
            ['ifFlag', 'passedXicisan', 'passed'],
            ['say', '羊身人面的神卧在路的尽头，抬头看着你。'],
            ['ask', '「自崇吾至翼望，二十三山，昆仑也在其中。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '西次三经之神', [['luomu', 30], ['jishe', 30], ['xicisan', 35]], ['licao']],
            ['setFlag', 'passedXicisan'],
            ['say', '「好。西山经还有最后一个山系。想去时再来找我。」'],
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
            ['ask', '「西次四经之首是阴山。要我送你去吗？」', [['去阴山', 'send'], ['留在这里', 'stay'], ['受祭', 'rite']]],
            ['label', 'send'],
            ['warp', 'yinshansi', 17, 7, 'left'],
            ['label', 'stay'],
            ['end'],
            ['label', 'rite'],
            ['rite'],
        ]],
    ],
    // The scree: 讙 and 鵸鵌.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 积蛇、鹰鸇、积蛇、鹰鸇 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[16, 'jishe'], [16, 'yingzhan'], [16, 'jishe'], [16, 'yingzhan'], [13, 'huanshou'], [13, 'qitu'], [13, 'huanshou']]],
];
