<?php
// 蔓渠之山, two hundred li west, the last of the range. The classic: gold and jade on it, bamboo below; the 伊水 rises on it
// and runs east into the 洛; the beast 马腹 lives there, with a man's face and a tiger's body, which cries like a baby and
// eats people.
declare(strict_types=1);

return [
    'id' => 'manqu',
    'name' => '蔓渠之山',
    'range' => 16,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..................T',
        'T..................T',
        '===================T',
        'T..................T',
        'T...........~~~~~~~T',
        'T...TT......~~~~~~~T',
        'T...TT.............T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['dusu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '蔓渠之山 —— 其上多金玉，其下多竹箭；伊水出焉，而东流注于洛'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedZhongci', 'passed'],
            ['say', '人面鸟身的神立在路的尽头，伊水从它脚下向东流去。'],
            ['ask', '「自煇诸至蔓渠，九山。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '中次二经之神', [['he', 54], ['longchi', 56], ['mingshe', 56], ['huashe', 58], ['mafu', 58], ['zhongci', 58]], ['dixiu', 'dixiu']],
            ['setFlag', 'passedZhongci'],
            ['say', '「好。参照的路到这里走完了；再往后的山，没有先例。想往下走时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['give', 'sealKin', 1],
            ['say', '「这三枚契印也拿去：威契印、水契印、亲契印各一枚。」'],
            ['give', 'yubi', 1],
            ['say', '「这枚璧玉也拿去。受过璋礼的常兽，或者本来就不弱的常兽，到了三十六级，可以凭它受璧礼。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「中次三经之首是萯山的敖岸之山。要我送你去吗？」', [['去敖岸之山', 'send'], ['留在这里', 'stay'], ['受祭', 'rite']]],
            ['label', 'send'],
            ['warp', 'aoan', 2, 7, 'right'],
            ['label', 'stay'],
            ['end'],
            ['label', 'rite'],
            ['rite'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's sixteenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[39, 'mafu'], [40, 'mafu'], [40, 'mafu'], [42, 'mafu'], [41, 'mafu'], [43, 'mafu'], [43, 'mafu']]],
];
