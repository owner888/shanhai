<?php
// 騩山, two hundred and fifty li west of 翠山, the last of the range (`guishan` is 柜山, of the second range). The
// classic: it stands against the Western Sea; no grass or trees, much jade; a water rises on it and runs west into the
// sea. It names no beast. One waits at the shore who takes those the god of 羭山 has passed on to the second range.
declare(strict_types=1);

return [
    'id' => 'guishan4',
    'name' => '騩山',
    'range' => 4,
    'rows' => [
        '~~~~~^^^^^^^^^^^^^^^',
        '~~~~~~-------------^',
        '~~~~~~--^^^--------^',
        '~~~~~~--^^^---^^---^',
        '~~~~~~--------^^---^',
        '~~~~~--------------^',
        '~~~~~--------------^',
        '~~~~~===============',
        '~~~~~~~~~~---------^',
        '~~~~~~~~~~~--------^',
        '~~~~~~-------^^^---^',
        '~~~~~~-------^^^---^',
        '~~~~~~-------------^',
        '~~~~~^^^^^^^^^^^^^^^',
    ],
    'exits' => ['right' => ['cuishan', 0]],
    'signs' => [
        [8, 6, [
            ['say', '騩山 —— 是錞于西海，无草木，多玉'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 5, 7, 'right', 'guard', [
            ['ifFlag', 'passedRange4', 'ok'],
            ['say', '「前面是西次二经的山。羭山的神还没有认可你，我不能送。」'],
            ['end'],
            ['label', 'ok'],
            ['ask', '「西次二经之首是钤山。要我送你去吗？」', [['去钤山', 'go'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'qianshan', 17, 7, 'left'],
            ['label', 'stay'],
        ]],
    ],
];
