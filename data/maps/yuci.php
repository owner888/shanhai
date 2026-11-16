<?php
// 羭次之山, seventy li west of 浮山: 羭山, which the classic says is the range's god. The classic: on it many 棫 and 橿,
// below it much bamboo; on its north side much red copper; the 漆水 rises on it and runs north into the 渭; the beast
// 嚻 lives there, and the bird 橐非. The god stands off the road; the classic gives it no shape.
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
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['give', 'sealKin', 1],
            ['say', '「这三枚契印也拿去：威契印、水契印、亲契印各一枚。」'],
            ['give', 'tongxiu', 1],
            ['say', '「这块同修玉也拿去。佩着它的异兽，不上场也分得一半修行。」'],
            ['give', 'yuzhang', 1],
            ['say', '「这枚璋玉也拿去。常兽到了二十五级，带着它来找任何一位你通过了试炼的山神，就能受璋礼：六项能力都会长。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['say', '「水能载你了。走过的那些山下的水，也可以回去看看。」'],
            ['ask', '「要让你的异兽受祭吗？」', [['受祭', 'rite'], ['不用', 'norite']]],
            ['label', 'rite'],
            ['rite'],
            ['label', 'norite'],
        ]],
    ],
    // The tall grass: 嚻 and 橐非.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 㸲牛、熊、白翰、㸲牛 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[13, 'zuoniu'], [14, 'xiong'], [15, 'baihan'], [16, 'zuoniu'], [15, 'xiao'], [15, 'tuofei'], [15, 'xiao']]],
];
