<?php
// 丙山, seventy li west, the last of the range (`bingshan` is 柄山, of the fourth). The classic: 梓 and 檀, 弞 and 杻. It names
// no beast.
declare(strict_types=1);

return [
    'id' => 'bingshanshi',
    'name' => '丙山',
    'range' => 24,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T...^^^............T',
        'T...^^^............T',
        'T..................T',
        'T..............TT..T',
        'T..................T',
        '===================T',
        'T..................T',
        'T..................T',
        'T...........^^^....T',
        'T...........^^^....T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['zhuoshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '丙山 —— 其木多梓檀，多弞杻'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedZhongcishi', 'passed'],
            ['say', '龙身人面的神盘在路的尽头的梓树和檀树之间。'],
            ['ask', '「自首阳至丙山，九山，是中山经最短的一段。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '中次十经之神', [['quyu', 73], ['yilang', 75], ['qiezhi', 75], ['qizhongniao', 77], ['zhongcishi', 77]], ['dixiu', 'dixiu']],
            ['setFlag', 'passedZhongcishi'],
            ['say', '「好。荆山还有一段，四十八山，是最长的。想去时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['give', 'sealKin', 1],
            ['give', 'sealFast', 1],
            ['give', 'sealHeavy', 1],
            ['give', 'sealMate', 1],
            ['say', '「这六枚契印也拿去：威、水、亲、速、重、偶契印各一枚。」'],
            ['give', 'yugui', 1],
            ['say', '「这枚圭玉也拿去。受过璧礼、又与你十分亲近的，可以凭它受圭礼：它的同心会更强。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「中次十一经之首是荆山的翼望之山。要我送你去吗？」', [['去翼望之山', 'send'], ['留在这里', 'stay'], ['受祭', 'rite']]],
            ['label', 'send'],
            ['warp', 'yiwangzhong', 2, 7, 'right'],
            ['label', 'stay'],
            ['end'],
            ['label', 'rite'],
            ['rite'],
        ]],
    ],
];
