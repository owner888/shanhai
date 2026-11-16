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
            ['ask', '「要回蔓渠之山吗？我送你一程。也可以在这里歇一歇，或者去兽栏看看。」', [['回蔓渠之山', 'go'], ['歇一歇', 'rest'], ['去兽栏', 'pen'], ['不用', 'stay']]],
            ['label', 'go'],
            ['warp', 'manqu', 16, 7, 'left'],
            ['end'],
            ['label', 'rest'],
            ['rest'],
            ['say', '「歇好了再走。」你的异兽们恢复了精神。'],
            ['end'],
            ['label', 'pen'],
            ['pen'],
            ['label', 'stay'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[29, 'fuzhu'], [30, 'fuzhu'], [30, 'fuzhu'], [32, 'fuzhu'], [31, 'fuzhu'], [33, 'fuzhu'], [33, 'fuzhu']]],
];
