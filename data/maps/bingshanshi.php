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
            ['trial', '中次十经之神', [['quyu', 88], ['yilang', 90], ['qiezhi', 90], ['qizhongniao', 92], ['zhongcishi', 92]]],
            ['setFlag', 'passedZhongcishi'],
            ['say', '「好。荆山还有一段，四十八山，是最长的。想去时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['say', '「这两枚契印也拿去：一枚威契印，一枚水契印。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「中次十一经之首是荆山的翼望之山。要我送你去吗？」', [['去翼望之山', 'send'], ['留在这里', 'stay']]],
            ['label', 'send'],
            ['warp', 'yiwangzhong', 2, 7, 'right'],
            ['label', 'stay'],
        ]],
    ],
];
