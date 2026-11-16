<?php
// 太山, two hundred li east, the last of the range and of the Eastern Mountains (`taishan` is 泰山, of the first range). The
// classic: gold and jade and 桢 trees; the beast 蜚 lives there, an ox with a white head, one eye and a snake's tail:
// water it walks in dries up, grass it walks on dies, and at its sight the world has plague; the 钩水 rises on it and runs
// north into the 劳水, with many 鱃鱼 in it.
declare(strict_types=1);

return [
    'id' => 'taishandong',
    'name' => '太山',
    'range' => 14,
    'rows' => [
        'TTTTTTTTT~~TTTTTTTTT',
        'T........~~........T',
        'T..,,,,,.~~.,,,,,..T',
        'T..,,,,,.~~.,,,,,..T',
        'T..,,,,,.~~.,,,,,..T',
        'T........~~........T',
        'T..................T',
        '===================T',
        'T..................T',
        'T..,,,,,...........T',
        'T..,TT,,...........T',
        'T..,TT,,...........T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['shanshan', 0]],
    'signs' => [
        [12, 6, [
            ['say', '太山 —— 上多金玉、桢木；钩水出焉，而北流注于劳水'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedDongcisi', 'passed'],
            ['say', '路的尽头立着这一山系的神。书上没有写它的样子；你只看见桢木的影子里有什么在等着。'],
            ['ask', '「自北号至太山，八山，东山到此为止。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '东次四经之神', [['dangkang', 41], ['boyu', 41], ['qique', 42], ['heyu', 42], ['dongcisi', 44]]],
            ['setFlag', 'passedDongcisi'],
            ['say', '「好。东山经四个山系，四十六山，你都走过了。想去中山经时再来找我。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「中山经之首是薄山的甘枣之山。要我送你去吗？」', [['去甘枣之山', 'send'], ['留在这里', 'stay']]],
            ['label', 'send'],
            ['warp', 'ganzao', 2, 7, 'right'],
            ['label', 'stay'],
        ]],
    ],
    // Its water.
    'water' => ['rate' => 2, 'slots' => [[20, 'xiuyu'], [15, 'xiuyu'], [20, 'xiuyu']]],
    // Its wild ground. The range's levels are those of the areas before the reference's fourteenth gym (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[5, 'fei'], [5, 'fei'], [8, 'fei'], [10, 'fei'], [10, 'fei'], [10, 'fei'], [10, 'fei']]],
];
