<?php
// 崦嵫之山, three hundred and sixty li southwest of 鸟鼠同穴之山, the last of the range and of the Western Mountains. The
// classic: on it many 丹木, whose fruit keeps fire off; on its south side many turtles, on its north much jade; the
// 苕水 rises on it and runs west into the sea; the beast 孰湖 lives there, a horse's body with a bird's wings, a human
// face and a snake's tail; and a bird like an owl with a human face, at whose sight there is drought. The god of the
// range keeps its end; the classic gives it no shape.
declare(strict_types=1);

return [
    'id' => 'yanzi',
    'name' => '崦嵫之山',
    'range' => 7,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..................T',
        'T..................T',
        'T===================',
        'T..................T',
        'T~~~~~....,,,,,....T',
        'T~~~~~....,,,,,....T',
        'T~~~~~....,,,,,....T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['right' => ['niaoshu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '崦嵫之山 —— 其上多丹木，其阳多龟，其阴多玉；苕水出焉，而西流注于海'],
        ]],
    ],
    'npcs' => [
        ['god', 1, 7, 'right', 'god', [
            ['ifFlag', 'passedXicisi', 'passed'],
            ['say', '西山尽头的神立在丹木之下。你看不清祂的样子。'],
            ['ask', '「自阴山至崦嵫，十九山，西山到此为止。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '西次四经之神', [['zhongshe', 27], ['shuhu', 29], ['xicisi', 31]]],
            ['setFlag', 'passedXicisi'],
            ['say', '「好。西山经四个山系，七十七山，你都走过了。想去北山时再来找我。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「北山经之首是单狐之山。要我送你去吗？」', [['去单狐之山', 'send'], ['留在这里', 'stay']]],
            ['label', 'send'],
            ['warp', 'danhu', 2, 7, 'right'],
            ['label', 'stay'],
        ]],
    ],
    // The tall grass: 龟, 孰湖, and the owl with a human face.
    'wild' => ['rate' => 10, 'slots' => [[13, 'gui'], [14, 'shuhu'], [15, 'renmianxiao'], [16, 'gui'], [15, 'shuhu'], [16, 'renmianxiao'], [16, 'gui']]],
];
