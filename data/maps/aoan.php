<?php
// 敖岸之山, the first of the Central Mountains' third range, the head of 萯山. The classic: 㻬琈 jade on its south side, ochre
// and gold on its north; the god 熏池 lives there; it looks north on the 河 and its woods; the beast 夫诸 lives there, a
// white deer with four horns, at whose sight a town has floods.
declare(strict_types=1);

return [
    'id' => 'aoan',
    'name' => '敖岸之山',
    'range' => 17,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '^===================',
        '^..................^',
        '^.......,,,,,......^',
        '^.......,,,,,^^....^',
        '^.......,,,,,^^....^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['right' => ['qingyao', 0]],
    'signs' => [
        [9, 6, [
            ['say', '中次三经萯山之首，曰敖岸之山 —— 其阳多㻬琈之玉，其阴多赭、黄金；神熏池居之'],
        ]],
    ],
    'npcs' => [
        ['ferryman', 1, 7, 'right', 'guard', [
            ['ask', '「要回蔓渠之山吗？我送你一程。也可以歇一歇、去兽栏，或者买点东西。」', [['回蔓渠之山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['买东西', 'shop'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'manqu', 16, 7, 'left'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['end'],
            ['label', 'pen'],
            ['pen'],
            ['end'],
            ['label', 'shop'],
            ['shop', ['seal', 'seal3', 'licao', 'youmu', 'dixiu', 'yupei', 'jibai', 'yuzhang', 'yubi']],
            ['label', 'stay'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 驾鸟、仆累、驾鸟、仆累 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[28, 'jianiao'], [29, 'pulei'], [29, 'jianiao'], [31, 'pulei'], [30, 'fuzhu'], [32, 'fuzhu'], [32, 'fuzhu']]],
];
