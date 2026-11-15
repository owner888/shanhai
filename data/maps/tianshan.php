<?php
// 天山, three hundred and fifty li west of 騩山. The classic: much gold and jade, and 青雄黄; the 英水 rises on it and
// runs southwest into 汤谷; the spirit 帝江 is there, like a yellow sack, red as cinnabar fire, six feet and four wings,
// with no face, who knows song and dance. It names no beast.
declare(strict_types=1);

return [
    'id' => 'tianshan',
    'name' => '天山',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^------------------^',
        '^-----------^^^----^',
        '^-----------^^^----^',
        '^------------------^',
        '^------------------^',
        '====================',
        '^------------------^',
        '~~~~~~~------------^',
        '~~~~~~~------------^',
        '^------------------^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['youshan', 0], 'right' => ['guishanxi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '天山 —— 有神焉，其状如黄囊，赤如丹火，六足四翼，浑敦无面目，是识歌舞，实为帝江也'],
        ]],
    ],
];
