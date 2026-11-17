<?php
// 䃌山, five hundred li south, the last of the range (`yinshan` is 阴山, of the west). The classic: it looks south on the 䃌水
// and east on 湖泽; the beast 峳峳 lives there, a horse with a sheep's eyes, four horns and an ox's tail; and the bird 絜钩, a
// duck with a rat's tail, good at climbing trees, at whose sight a state has much plague.
declare(strict_types=1);

return [
    'id' => 'yinshandong',
    'name' => '䃌山',
    'range' => 12,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
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
    'exits' => ['left' => ['fuli', 0]],
    'signs' => [
        [9, 6, [
            ['say', '䃌山 —— 南临䃌水，东望湖泽'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedDongci', 'passed'],
            ['say', '兽身人面、头上生着鹿角的神立在路的尽头，南边是䃌水。'],
            ['ask', '「自空桑至䃌山，十七山。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '东次二经之神', [['longzhi', 36], ['bibi', 36], ['lingling', 36], ['qiuyu', 33], ['dongci', 39]]],
            ['setFlag', 'passedDongci'],
            ['say', '「好。往南还有两个山系。想去时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['give', 'sealKin', 1],
            ['give', 'sealFast', 1],
            ['give', 'sealHeavy', 1],
            ['give', 'sealMate', 1],
            ['say', '「这六枚契印也拿去：威、水、亲、速、重、偶契印各一枚。」'],
            ['give', 'yubi', 1],
            ['say', '「这枚璧玉也拿去。受过璋礼的常兽，或者本来就不弱的常兽，到了三十六级，可以凭它受璧礼。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「东次三经之首是尸胡之山，要走水路。要我送你去吗？」', [['去尸胡之山', 'send'], ['留在这里', 'stay'], ['受祭', 'rite']]],
            ['label', 'send'],
            ['warp', 'shihu', 2, 7, 'right'],
            ['label', 'stay'],
            ['end'],
            ['label', 'rite'],
            ['rite'],
        ]],
    ],
    // Its wild ground. The range's levels are those of the areas before the reference's twelfth gym (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 虎、麋、鹿、虎 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[23, 'hu'], [23, 'mi'], [25, 'yelu'], [25, 'hu'], [27, 'jiegou'], [27, 'youyou'], [25, 'jiegou']]],
];
