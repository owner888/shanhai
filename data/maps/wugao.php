<?php
// 无皋之山, five hundred li south by water and three hundred over drifting sand, the last of the range. The classic: it
// looks south on 幼海 and east on the 榑木; no grass or trees, much wind; a hundred li round. It names no beast.
declare(strict_types=1);

return [
    'id' => 'wugao',
    'name' => '无皋之山',
    'range' => 13,
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
    'exits' => ['left' => ['muyu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '无皋之山 —— 南望幼海，东望榑木，无草木，多风；是山也，广员百里'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedDongcisan', 'passed'],
            ['say', '人身羊角的神立在路的尽头，风很大，南边是海。'],
            ['ask', '「自尸胡至无皋，九山，都隔着水。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '东次三经之神', [['wanhu', 42], ['gege', 42], ['jingjing', 44], ['dongcisan', 47]]],
            ['setFlag', 'passedDongcisan'],
            ['say', '「好。东山经还剩最后一个山系。想去时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['say', '「这两枚契印也拿去：一枚威契印，一枚水契印。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「东次四经之首是北号之山，在北海边上。要我送你去吗？」', [['去北号之山', 'send'], ['留在这里', 'stay']]],
            ['label', 'send'],
            ['warp', 'beihao', 2, 7, 'right'],
            ['label', 'stay'],
        ]],
    ],
];
