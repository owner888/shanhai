<?php
// 和山, twenty li east, the last of the range. The classic: no grass or trees, much 瑶 and 碧; the nine waters of the 河
// gather here and run north into it; the lucky god 泰逢 keeps it, shaped like a man with a tiger's tail, who shines as he
// comes and goes.
declare(strict_types=1);

return [
    'id' => 'heshan',
    'name' => '和山',
    'range' => 17,
    'rows' => [
        '^^^^^^^^^~~^^^^^^^^^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^--------~~--------^',
        '^------------------^',
        '===================^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['yisu', 0]],
    'signs' => [
        [12, 6, [
            ['say', '和山 —— 其上无草木而多瑶碧，实惟河之九都；吉神泰逢司之'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedZhongcisan', 'passed'],
            ['say', '路的尽头站着一个人，身后拖着一条虎尾。他走动的时候，身上有光。'],
            ['ask', '「我是泰逢。自敖岸至和山，五山，都在河边。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '泰逢', [['fuzhu', 58], ['yaoniao', 60], ['feiyutun', 60], ['taifeng', 62]], ['dixiu', 'dixiu']],
            ['setFlag', 'passedZhongcisan'],
            ['say', '「好。往西是釐山。想去时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['give', 'sealKin', 1],
            ['say', '「这三枚契印也拿去：威契印、水契印、亲契印各一枚。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「中次四经之首是釐山的鹿蹄之山。要我送你去吗？」', [['去鹿蹄之山', 'send'], ['留在这里', 'stay']]],
            ['label', 'send'],
            ['warp', 'luti', 2, 7, 'right'],
            ['label', 'stay'],
        ]],
    ],
];
