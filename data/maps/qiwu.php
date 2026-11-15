<?php
// 漆吴之山, five hundred li east of 鹿吴之山, the last of the range. The classic: no grass or trees; much 博石, no
// jade; it stands in the Eastern Sea and looks out on 丘山; a light comes and goes there: it is where the sun rests.
// The god of the range, a dragon's body with a bird's head, keeps its end.
declare(strict_types=1);

return [
    'id' => 'qiwu',
    'name' => '漆吴之山',
    'range' => 2,
    'rows' => [
        '^^^^^^^^^^^^^^^~~~~~',
        '^-------------~~~~~~',
        '^--^^^--------~~~~~~',
        '^--^^^---^^---~~~~~~',
        '^--------^^---~~~~~~',
        '^-------------~~~~~~',
        '^--------------~~~~~',
        '===============~~~~~',
        '^--------------~~~~~',
        '^-------------~~~~~~',
        '^---^^^^------~~~~~~',
        '^---^^^^------~~~~~~',
        '^-------------~~~~~~',
        '^^^^^^^^^^^^^^^~~~~~',
    ],
    'exits' => ['left' => ['luwu', 0]],
    'signs' => [
        [13, 6, [
            ['say', '漆吴之山 —— 处于东海，是惟日次'],
        ]],
    ],
    'npcs' => [
        ['god', 14, 7, 'left', 'god', [
            ['ifFlag', 'passedNanci2', 'passed'],
            ['say', '龙身鸟首的神立在海边，身后的海面上有光时出时入。'],
            ['ask', '「自柜山至漆吴，十七山皆在我治下。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '南次二经之神', [['jiyu', 14], ['biluo', 14], ['nanci2', 16]]],
            ['setFlag', 'passedNanci2'],
            ['say', '「好。南次三经在更南的地方，路通了，自会有人告诉你。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['say', '「日出日入，都在这片海上。去吧。」'],
        ]],
    ],
];
