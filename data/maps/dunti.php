<?php
// 敦题之山, three hundred li north of 洹山, the last of the range. The classic: no grass or trees, much gold and jade;
// it stands against the Northern Sea. The classic counts seventeen mountains and names sixteen. The god of the range,
// a snake's body with a human face, keeps its end.
declare(strict_types=1);

return [
    'id' => 'dunti',
    'name' => '敦题之山',
    'range' => 9,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^~~~~~~~~~~~~~~~~~~^',
        '^~~~~~~~~~~~~~~~~~~^',
        '^~~~~~~~~~~~~~~~~~~^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '===================^',
        '^------------------^',
        '^------------------^',
        '^----^^^----^^^----^',
        '^----^^^----^^^----^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['huanshan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '敦题之山 —— 无草木，多金玉，是錞于北海'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedBeici', 'passed'],
            ['say', '蛇身人面的神盘在北海边上。'],
            ['ask', '「自管涔至敦题，北海就在我身后。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '北次二经之神', [['boma', 44], ['duyu', 40], ['banmao', 40], ['xiaoque', 40], ['beici', 46]]],
            ['setFlag', 'passedBeici'],
            ['say', '「好。北山经还有最后一列，是最长的一列。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['say', '「北次三经的路还没有开。」'],
        ]],
    ],
];
