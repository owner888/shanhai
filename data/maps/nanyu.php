<?php
// 南禺之山, five hundred and eighty li east of 禺槀之山, the last of the range. The classic: on it much gold and jade,
// below it much water; there is a hole in it that the water comes out of in summer and that is shut in winter; the
// 佐水 rises on it and runs southeast into the sea; 凤皇 and 鵷鶵 live there (neither is in the grass: docs/todo.md).
// The god of the range, a dragon's body with a human face, keeps its end, and sends who has passed its trial on to
// the Western Mountains.
declare(strict_types=1);

return [
    'id' => 'nanyu',
    'name' => '南禺之山',
    'range' => 3,
    'rows' => [
        '^^^^^^^^^^^^^^^~~~~~',
        '^^^^^^^^^^^^^^~~~~~~',
        '^^^^^~~^^^^^^^~~~~~~',
        '^....~~.......~~~~~~',
        '^....~~.......~~~~~~',
        '^..............~~~~~',
        '^..............~~~~~',
        '===============~~~~~',
        '^..............~~~~~',
        '^.....~~~~.....~~~~~',
        '^....~~~~~~~...~~~~~',
        '^.......~~~~~~~~~~~~',
        '^..........~~~~~~~~~',
        '^^^^^^^^^^^~~~~~~~~~',
    ],
    'exits' => ['left' => ['yugao', 0]],
    'signs' => [
        [13, 6, [
            ['say', '南禺之山 —— 其上多金玉，其下多水；佐水东南流注于海'],
        ]],
        [7, 3, [
            ['say', '山壁上有穴，水从里面流出来 —— 夏乃出，冬则闭'],
        ]],
    ],
    'npcs' => [
        ['god', 14, 7, 'left', 'god', [
            ['ifFlag', 'passedNanci3', 'passed'],
            ['say', '龙身人面的神立在海边，佐水从祂身后流进海里。'],
            ['ask', '「自天虞至南禺，诸山皆在我治下。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '南次三经之神', [['xi', 18], ['nanci3', 20]]],
            ['setFlag', 'passedNanci3'],
            ['say', '「好。南山经三个山系的山，你都走过了。想去西山时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['say', '「这两枚契印也拿去：一枚威契印，一枚水契印。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「西山经之首是钱来之山。要我送你去吗？」', [['去钱来之山', 'send'], ['留在这里', 'stay']]],
            ['label', 'send'],
            ['warp', 'qianlai', 17, 7, 'left'],
            ['label', 'stay'],
        ]],
    ],
];
