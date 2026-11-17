<?php
// 放皋之山, fifty-two li east. The classic: the 明水 rises on it and runs south into the 伊水, with much grey jade in it; a tree
// called 蒙木; the beast 文文 lives there, shaped like a bee with a forked tail and a turned tongue, given to calling.
declare(strict_types=1);

return [
    'id' => 'fanggao',
    'name' => '放皋之山',
    'range' => 21,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..................T',
        'T..................T',
        '====================',
        'T........~~........T',
        'T........~~........T',
        'T...TT...~~........T',
        'T...TT...~~........T',
        'T........~~........T',
        'TTTTTTTTT~~TTTTTTTTT',
    ],
    'exits' => ['left' => ['dushanqi', 0], 'right' => ['dakushan', 0]],
    'signs' => [
        [12, 6, [
            ['say', '放皋之山 —— 明水出焉，南流注于伊水，其中多苍玉'],
            ['ifFlag', 'found_mengmu', 'had_mengmu'],
            ['setFlag', 'found_mengmu'],
            ['give', 'mengmu', 1],
            ['say', '牌子旁边有蒙木。你采了一份。'],
            ['label', 'had_mengmu'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 㸲牛、赤鷩、㸲牛、赤鷩 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[33, 'zuoniu'], [34, 'chibi'], [34, 'zuoniu'], [36, 'chibi'], [35, 'wenwen'], [37, 'wenwen'], [37, 'wenwen']]],
];
