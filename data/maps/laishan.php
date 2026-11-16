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
            ['trial', '西次二经之神', [['luoluo', 27], ['xici', 30]], ['yupei']],
            ['setFlag', 'passedXici'],
            ['say', '「好。再往西是西次三经，昆仑就在那个山系里。想去时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['give', 'sealKin', 1],
            ['give', 'sealFast', 1],
            ['give', 'sealHeavy', 1],
            ['say', '「这五枚契印也拿去：威、水、亲、速、重契印各一枚。」'],
            ['give', 'yuzhang', 1],
            ['say', '「这枚璋玉也拿去。常兽到了二十五级，带着它来找任何一位你通过了试炼的山神，就能受璋礼：六项能力都会长。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「西次三经之首是崇吾之山。要我送你去吗？」', [['去崇吾之山', 'send'], ['留在这里', 'stay'], ['受祭', 'rite']]],
            ['label', 'send'],
            ['warp', 'chongwu', 17, 7, 'left'],
            ['label', 'stay'],
            ['end'],
            ['label', 'rite'],
            ['rite'],
        ]],
    ],
    // The tall grass: 罗罗.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 犀、兕、虎、犀 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[16, 'xi'], [16, 'si'], [16, 'hu'], [16, 'xi'], [13, 'luoluo'], [13, 'luoluo'], [13, 'luoluo']]],
];
