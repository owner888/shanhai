<?php
// 大騩之山, thirty li east, the last of the range. The classic: iron, fine jade and green clay on its north side; a herb
// whose name is a rare character, which keeps one from dying young. It names no beast.
declare(strict_types=1);

return [
    'id' => 'dagui',
    'name' => '大騩之山',
    'range' => 21,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^...^^^............^',
        '^...^^^............^',
        '^..................^',
        '^..................^',
        '^..................^',
        '===================^',
        '^..................^',
        '^..................^',
        '^...........^^^....^',
        '^...........^^^....^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['minshanqi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '大騩之山 —— 其阴多铁、美玉、青垩'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedZhongciqi', 'passed'],
            ['say', '豕身人面的神立在路的尽头。'],
            ['ask', '「自休与至大騩，十九山，苦山、少室、太室都在其中。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '中次七经之神', [['wenwen', 75], ['sanzugui', 77], ['tiyu', 77], ['shangao', 79], ['zhongciqi', 79]]],
            ['setFlag', 'passedZhongciqi'],
            ['say', '「好。往南是荆山。想去时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['give', 'sealKin', 1],
            ['say', '「这三枚契印也拿去：威契印、水契印、亲契印各一枚。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「中次八经之首是荆山的景山。要我送你去吗？」', [['去景山', 'send'], ['留在这里', 'stay']]],
            ['label', 'send'],
            ['warp', 'jingshanba', 2, 7, 'right'],
            ['label', 'stay'],
        ]],
    ],
];
