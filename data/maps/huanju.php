<?php
// 讙举之山, three hundred and fifty li west, the last of the range. The classic: the 雒水 rises on it and runs northeast into
// the 玄扈之水, in which are many things like horse guts, of which it says no more: they are not in the data.
declare(strict_types=1);

return [
    'id' => 'huanju',
    'name' => '讙举之山',
    'range' => 18,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^...^^.............^',
        '^...^^.............^',
        '^..................^',
        '^..................^',
        '^..................^',
        '===================^',
        '^..................^',
        '^...........~~~~~~~^',
        '^...........~~~~~~~^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['mushan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '讙举之山 —— 雒水出焉，而东北流注于玄扈之水'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedZhongcisi', 'passed'],
            ['say', '人面兽身的神立在路的尽头，雒水从这里流出去。'],
            ['ask', '「自鹿蹄至讙举，九山。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '中次四经之神', [['yinmo', 62], ['xie', 64], ['xiqu', 64], ['zhongcisi', 66]], ['dixiu', 'dixiu']],
            ['setFlag', 'passedZhongcisi'],
            ['say', '「好。薄山还有一段，在东边。想去时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['give', 'sealKin', 1],
            ['say', '「这三枚契印也拿去：威契印、水契印、亲契印各一枚。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「中次五经之首是薄山的苟林之山。要我送你去吗？」', [['去苟林之山', 'send'], ['留在这里', 'stay']]],
            ['label', 'send'],
            ['warp', 'goulin', 2, 7, 'right'],
            ['label', 'stay'],
        ]],
    ],
];
