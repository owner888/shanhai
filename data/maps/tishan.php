<?php
// 隄山, a hundred and seventy li north of 北鲜之山, the last of the range. The classic: many horses; the beast 狕 lives
// there, a leopard with a patterned head; the 隄水 rises on it and runs east into 泰泽, with many 龙龟 in it. The god of
// the range, a human face on a snake's body, keeps its end.
declare(strict_types=1);

return [
    'id' => 'tishan',
    'name' => '隄山',
    'range' => 8,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,..,,,,,....^',
        '^..,,,,,..,,,,,....^',
        '^..,,,,,..,,,,,....^',
        '^..................^',
        '^..................^',
        '===================^',
        '^..................^',
        '^...,,,,,...~~~~~~~^',
        '^...,,,,,...~~~~~~~^',
        '^...,,,,,...~~~~~~~^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['beixian', 0]],
    'signs' => [
        [9, 6, [
            ['say', '隄山 —— 多马；隄水出焉，而东流注于泰泽，其中多龙龟'],
        ]],
    ],
    // The 隄水: 龙龟.
    'water' => ['rate' => 2, 'slots' => [[20, 'longgui'], [15, 'longgui'], [20, 'longgui']]],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedBeishan', 'passed'],
            ['say', '人面蛇身的神盘在路的尽头，隄水从祂身旁流过。'],
            ['ask', '「自单狐至隄山，二十五山。山北的人，都吃生的、不用火的东西。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '北山经之首之神', [['nafu', 37], ['tuotuo', 37], ['shuima', 37], ['beishan', 40]], ['yupei', 'licao']],
            ['setFlag', 'passedBeishan'],
            ['say', '「好。再往北是北次二经。想去时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['give', 'sealKin', 1],
            ['say', '「这三枚契印也拿去：威契印、水契印、亲契印各一枚。」'],
            ['give', 'sealSure', 1],
            ['say', '「还有这一枚天契印。天下只此一枚，一定成契，想好了再用。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「北次二经之首是管涔之山。要我送你去吗？」', [['去管涔之山', 'send'], ['留在这里', 'stay']]],
            ['label', 'send'],
            ['warp', 'guancen', 2, 7, 'right'],
            ['label', 'stay'],
        ]],
    ],
    // The tall grass: 马 and 狕.
    'wild' => ['rate' => 2, 'slots' => [[24, 'ma'], [25, 'yao'], [25, 'ma'], [26, 'yao'], [22, 'ma'], [24, 'yao'], [26, 'ma']]],
];
