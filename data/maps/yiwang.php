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
            ['say', '「这三枚契印也拿去：威契印、水契印、亲契印各一枚。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「西次四经之首是阴山。要我送你去吗？」', [['去阴山', 'send'], ['留在这里', 'stay']]],
            ['label', 'send'],
            ['warp', 'yinshansi', 17, 7, 'left'],
            ['label', 'stay'],
        ]],
    ],
    // The scree: 讙 and 鵸鵌.
    'wild' => ['rate' => 10, 'slots' => [[16, 'huanshou'], [16, 'qitu'], [16, 'huanshou'], [16, 'qitu'], [13, 'huanshou'], [13, 'qitu'], [13, 'huanshou']]],
];
