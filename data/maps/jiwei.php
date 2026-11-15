<?php
// 箕尾之山, three hundred and fifty li east of 青丘之山, the last of the range. The classic: its tail runs down into
// the Eastern Sea; much sand and stone; the 汸水 rises on it and runs south into the 淯, with much white jade in it.
// It names no beast here. The god of the range, a bird's body with a dragon's head, keeps its end: who would go on
// to the next range is tried here.
declare(strict_types=1);

return [
    'id' => 'jiwei',
    'name' => '箕尾之山',
    'range' => 1,
    'rows' => [
        '^^^^^^^^^^^^^^^~~~~~',
        '^^^----^^^----~~~~~~',
        '^^------^-----~~~~~~',
        '^-------------~~~~~~',
        '^--------------~~~~~',
        '^---------------~~~~',
        '^---------------~~~~',
        '================~~~~',
        '^----~~---------~~~~',
        '^---~~~--------~~~~~',
        '^---~~~-------~~~~~~',
        '^^--~~~~-----~~~~~~~',
        '^^^-~~~~----~~~~~~~~',
        '^^^^~~~~^^^^~~~~~~~~',
    ],
    'exits' => ['left' => ['qingqiu', 0]],
    'npcs' => [
        ['god', 14, 7, 'left', 'god', [
            ['ifFlag', 'passedQueshan', 'passed'],
            ['say', '鸟身龙首的神立在海边。「自招摇至箕尾，十山皆在我治下。」'],
            ['ifFlag', 'metShaman', 'ask'],
            ['say', '「你身边没有异兽。回去吧。」'],
            ['end'],
            ['label', 'ask'],
            ['ask', '「想去南次二经，先让我看看你和异兽的本事。」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '鹊山之神', [['boyi', 7], ['queshan', 9]]],
            ['setFlag', 'passedQueshan'],
            ['say', '「好。南次二经的山路，为你而开。想去时再来找我。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「南次二经之首是柜山。要我送你去吗？」', [['去柜山', 'send'], ['留在这里', 'stay']]],
            ['label', 'send'],
            ['warp', 'guishan', 2, 7, 'right'],
            ['label', 'stay'],
        ]],
    ],
    'signs' => [
        [13, 6, [
            ['say', '箕尾之山 —— 其尾踆于东海，多沙石'],
        ]],
    ],
];
