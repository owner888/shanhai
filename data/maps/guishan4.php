<?php
// 騩山, two hundred and fifty li west of 翠山, the last of the range (`guishan` is 柜山, of the second range). The
// classic: it stands against the Western Sea; no grass or trees, much jade; a water rises on it and runs west into the
// sea. It names no beast.
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
];
