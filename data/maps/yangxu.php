<?php
// 阳虚之山, twelve li east, the last of the range. The classic: much gold; it stands on the 玄扈之水. It names no beast.
declare(strict_types=1);

return [
    'id' => 'yangxu',
    'name' => '阳虚之山',
    'range' => 19,
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
    'exits' => ['left' => ['shengshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '阳虚之山 —— 多金，临于玄扈之水'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedZhongciwu', 'passed'],
            ['say', '路的尽头立着这一段薄山的神，身后是玄扈之水。书上没有写它的样子。'],
            ['ask', '「自苟林至阳虚，都是薄山。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '中次五经之神', [['jinglu', 66], ['xie', 68], ['dainiao', 68], ['zhongciwu', 70]], ['dixiu', 'dixiu']],
            ['setFlag', 'passedZhongciwu'],
            ['say', '「好。缟羝山在西边，骄虫守着它的东头。想去时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['give', 'sealKin', 1],
            ['say', '「这三枚契印也拿去：威契印、水契印、亲契印各一枚。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「中次六经是缟羝山。它的神在东头的平逢之山，我送你到西头的阳华之山，你走过去。去吗？」', [['去阳华之山', 'send'], ['留在这里', 'stay']]],
            ['label', 'send'],
            ['warp', 'yanghua', 2, 7, 'right'],
            ['label', 'stay'],
        ]],
    ],
];
