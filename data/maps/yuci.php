<?php
// 羭次之山, seventy li west of 浮山: 羭山, which the classic says is the range's god. The classic: on it many 棫 and 橿,
// below it much bamboo; on its north side much red copper; the 漆水 rises on it and runs north into the 渭; the beast
// 嚻 lives there, and the bird 橐𩇯. The god stands off the road; the classic gives it no shape.
declare(strict_types=1);

return [
    'id' => 'yuci',
    'name' => '羭次之山',
    'range' => 4,
    'rows' => [
        'TTTTTTTTTTTTT~~TTTTT',
        'T.TT....TT...~~....T',
        'T....,,,,....~~....T',
        'T....,,,,....~~....T',
        'T....,,,,.....~....T',
        'T..................T',
        'T..................T',
        '====================',
        'T..................T',
        'T..,,,,,...........T',
        'T..,,,,,......TT...T',
        'T..,,,,,......TT...T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['shishan', 0], 'right' => ['fushan', 0]],
    'signs' => [
        [16, 6, [
            ['say', '羭次之山 —— 其上多棫橿，其下多竹箭；漆水出焉，北流注于渭'],
        ]],
    ],
    'npcs' => [
        ['god', 11, 5, 'down', 'god', [
            ['ifFlag', 'passedRange4', 'passed'],
            ['say', '羭山的神立在棫橿之下。你看不清祂的样子。'],
            ['ask', '「自钱来至騩山，十九山，以羭山为神。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '羭山之神', [['ling', 21], ['mengbao', 21], ['xiong', 23], ['xishan', 25]]],
            ['setFlag', 'passedRange4'],
            ['say', '「好。从今往后，水属的异兽可以载你渡水。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['say', '「水能载你了。走过的那些山下的水，也可以回去看看。」'],
        ]],
    ],
    // The tall grass: 嚻 and 橐𩇯.
    'wild' => ['rate' => 10, 'slots' => [[13, 'xiao'], [14, 'tuofei'], [15, 'xiao'], [16, 'tuofei'], [15, 'xiao'], [15, 'tuofei'], [15, 'xiao']]],
];
