<?php
// 几山, three hundred and fifty li east, the last of the range. The classic: 楢, 檀 and 杻, fragrant herbs; the beast 闻粦
// lives there, a pig with a yellow body, a white head and a white tail, at whose sight the world has great wind.
declare(strict_types=1);

return [
    'id' => 'jishanji',
    'name' => '几山',
    'range' => 25,
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
    'exits' => ['left' => ['yaoshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '几山 —— 其木多楢檀杻，其草多香'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedZhongcishiyi', 'passed'],
            ['say', '彘身人首的神立在路的尽头，山上的香草被风吹得伏下去。'],
            ['ask', '「自翼望至几山，四十八山，是山经里最长的一段。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '中次十一经之神', [['qinggeng', 92], ['yonghe', 94], ['yiji', 94], ['wenlin', 96], ['liangqushou', 96], ['zhongcishiyi', 96]], ['dixiu', 'dixiu']],
            ['setFlag', 'passedZhongcishiyi'],
            ['say', '「好。只剩洞庭山了。想去时再来找我。」'],
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
            ['ask', '「中次十二经之首是洞庭山的篇遇之山。要我送你去吗？」', [['去篇遇之山', 'send'], ['留在这里', 'stay'], ['受祭', 'rite']]],
            ['label', 'send'],
            ['warp', 'pianyu', 2, 7, 'right'],
            ['label', 'stay'],
            ['end'],
            ['label', 'rite'],
            ['rite'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 豹、虎、鸩、豹 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[46, 'bao'], [47, 'hu'], [47, 'zhenniao'], [49, 'bao'], [48, 'wenlin'], [50, 'wenlin'], [50, 'wenlin']]],
];
