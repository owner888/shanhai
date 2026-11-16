<?php
// 琴鼓之山, two hundred li southeast, the last of the range. The classic: 榖, 柞, pepper and 柘; white 珉 on it, 洗 stone below;
// its beasts are mostly boar, deer and white rhinoceros, its birds 鸩. The white rhinoceros is not in the data.
declare(strict_types=1);

return [
    'id' => 'qingu',
    'name' => '琴鼓之山',
    'range' => 22,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..............TT..T',
        'T..................T',
        '===================T',
        'T..................T',
        'T.......,,,,,......T',
        'T.......,,,,,^^....T',
        'T.......,,,,,^^....T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['shimei', 0]],
    'signs' => [
        [9, 6, [
            ['say', '琴鼓之山 —— 其木多榖柞椒柘，其上多白珉，其下多洗石，其兽多豕鹿，多白犀，其鸟多鸩'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedZhongciba', 'passed'],
            ['say', '鸟身人面的神立在路的尽头，山上的榖柞椒柘长得很密。'],
            ['ask', '「自景山至琴鼓，二十三山。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '中次八经之神', [['zhulu', 79], ['jiaoyu', 81], ['zhenniao', 81], ['bao', 83], ['zhongciba', 83]]],
            ['setFlag', 'passedZhongciba'],
            ['say', '「好。往西是岷山，江水从那里出来。想去时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['say', '「这两枚契印也拿去：一枚威契印，一枚水契印。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「中次九经之首是岷山的女几之山。要我送你去吗？」', [['去女几之山', 'send'], ['留在这里', 'stay']]],
            ['label', 'send'],
            ['warp', 'nvjijiu', 2, 7, 'right'],
            ['label', 'stay'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[40, 'yeshi'], [41, 'yelu'], [41, 'zhenniao'], [43, 'yeshi'], [42, 'yelu'], [44, 'zhenniao'], [44, 'yeshi']]],
];
