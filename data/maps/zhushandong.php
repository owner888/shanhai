<?php
// 竹山, three hundred li south, the last of the range (`zhushan` is the 竹山 of the west). The classic: no grass or trees,
// much 瑶 and 碧; the 激水 rises on it and runs southeast into the 娶檀之水, with many 茈蠃 in it.
declare(strict_types=1);

return [
    'id' => 'zhushandong',
    'name' => '竹山',
    'range' => 11,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^---^^-------------^',
        '^---^^-------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '===================^',
        '^------------------^',
        '^-----------~~~~~~~^',
        '^-----------~~~~~~~^',
        '^------------------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['taishan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '竹山 —— 无草木，多瑶碧；激水出焉，而东南流注于娶檀之水'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedDongshan', 'passed'],
            ['say', '人身龙首的神立在路的尽头，身后是汶水。'],
            ['ask', '「自樕蛛至竹山，十二山。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '东山经之首之神', [['tongtong', 42], ['congcong', 41], ['chaishou', 46], ['dongshan', 46]]],
            ['setFlag', 'passedDongshan'],
            ['say', '「好。东山经还有三个山系。想往南去时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['give', 'sealKin', 1],
            ['say', '「这三枚契印也拿去：威契印、水契印、亲契印各一枚。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「东次二经之首是空桑之山。要我送你去吗？」', [['去空桑之山', 'send'], ['留在这里', 'stay']]],
            ['label', 'send'],
            ['warp', 'kongsangdong', 2, 7, 'right'],
            ['label', 'stay'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'ziluo'], [15, 'ziluo'], [20, 'ziluo']]],
];
