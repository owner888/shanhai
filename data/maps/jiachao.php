<?php
// 贾超之山, a hundred and seventy li east, the last of the range. The classic: yellow clay on its south side, fine ochre on
// its north; 柤, chestnut, orange and pomelo; much 龙脩. It names no beast.
declare(strict_types=1);

return [
    'id' => 'jiachao',
    'name' => '贾超之山',
    'range' => 23,
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
    'exits' => ['left' => ['geshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '贾超之山 —— 其阳多黄垩，其阴多美赭，其木多柤栗橘櫾，其中多龙脩'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedZhongcijiu', 'passed'],
            ['say', '马身龙首的神立在路的尽头。'],
            ['ask', '「自女几至贾超，十六山，江水从这里出去。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '中次九经之神', [['kuiniu', 83], ['tuo', 85], ['qiezhi', 85], ['yilang', 87], ['zhongcijiu', 87]]],
            ['setFlag', 'passedZhongcijiu'],
            ['say', '「好。首阳山不远，只有九座。想去时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['give', 'sealKin', 1],
            ['say', '「这三枚契印也拿去：威契印、水契印、亲契印各一枚。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「中次十经之首是首阳之山。要我送你去吗？」', [['去首阳之山', 'send'], ['留在这里', 'stay']]],
            ['label', 'send'],
            ['warp', 'shouyang', 2, 7, 'right'],
            ['label', 'stay'],
        ]],
    ],
];
