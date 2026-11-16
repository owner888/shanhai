<?php
// 荣余之山, two hundred and thirty li east, the last hill of the range and of the book's twenty-six. The classic: copper on
// it, silver below; willow and 芑; its crawling things are mostly strange snakes and strange worms.
declare(strict_types=1);

return [
    'id' => 'rongyu',
    'name' => '荣余之山',
    'range' => 26,
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
    'exits' => ['left' => ['chaisang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '荣余之山 —— 其上多铜，其下多银，其木多柳芑，其虫多怪蛇、怪虫'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedZhongcishier', 'passed'],
            ['say', '鸟身龙首的神立在路的尽头，山下的铜与银在暮色里发着光。'],
            ['ask', '「自篇遇至荣余，十五山。这是山经里最后一段路。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '中次十二经之神', [['jiuniao', 96], ['feishe', 98], ['guainiao', 98], ['jilu', 100], ['guigui', 100], ['zhongcishier', 100]], ['dixiu', 'dixiu']],
            ['setFlag', 'passedZhongcishier'],
            ['say', '「好。南西北东中，二十六个山系，你都走过了。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['give', 'sealKin', 1],
            ['say', '「这三枚契印也拿去：威契印、水契印、亲契印各一枚。」'],
            ['give', 'yugui', 1],
            ['say', '「这枚圭玉也拿去。受过璧礼、又与你十分亲近的，可以凭它受圭礼：它的同心会更强。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['say', '「二十六个山系，你都走过了。」'],
            ['ask', '「要让你的异兽受祭吗？」', [['受祭', 'rite'], ['不用', 'norite']]],
            ['label', 'rite'],
            ['rite'],
            ['label', 'norite'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[48, 'guaishe'], [49, 'guaichong'], [49, 'guaishe'], [51, 'guaichong'], [50, 'guaishe'], [52, 'guaichong'], [52, 'guaishe']]],
];
