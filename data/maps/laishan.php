<?php
// 莱山, three hundred and fifty li west of 西皇之山, the last of the range. The classic: its trees are mostly 檀 and 楮; its
// birds are mostly 罗罗, which eat people. The god of the range keeps its end: a human face on an ox's body, four feet
// and one arm, walking with a staff, the god of the beasts that fly.
declare(strict_types=1);

return [
    'id' => 'laishan',
    'name' => '莱山',
    'range' => 5,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T.TT.,,,,,..TT.TT..T',
        'T.TT.,,,,,..TT.TT..T',
        'T....,,,,,.........T',
        'T..................T',
        'T..................T',
        'T..................T',
        'T===================',
        'T..................T',
        'T..TT.......,,,,...T',
        'T..TT.......,,,,...T',
        'T...........,,,,...T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['right' => ['xihuang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '莱山 —— 其木多檀楮，其鸟多罗罗，是食人'],
        ]],
    ],
    'npcs' => [
        ['god', 1, 7, 'right', 'god', [
            ['ifFlag', 'passedXici', 'passed'],
            ['say', '人面牛身的神拄着杖立在路的尽头，只有一条手臂。'],
            ['ask', '「自钤山至莱山，十七山。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '西次二经之神', [['luoluo', 27], ['xici', 30]]],
            ['setFlag', 'passedXici'],
            ['say', '「好。再往西是西次三经，昆仑就在那一列里。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['say', '「西次三经的路还没有开。」'],
        ]],
    ],
    // The tall grass: 罗罗.
    'wild' => ['rate' => 10, 'slots' => [[16, 'luoluo'], [16, 'luoluo'], [16, 'luoluo'], [16, 'luoluo'], [13, 'luoluo'], [13, 'luoluo'], [13, 'luoluo']]],
];
