<?php
// 亶爰之山, four hundred li east of 柢山. The classic: much water, no grass or trees; it cannot be climbed; 类 lives
// there. So the map is its foot, bare: the road between the cliff and the water.
declare(strict_types=1);

return [
    'id' => 'danyuan',
    'name' => '亶爰之山 · 山脚',
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
        '^^^^^^^^^^^^^^^^^^^^',
        '^^::::^^^^::::^^^:^^',
        '^:::::-^^::::::^:::^',
        '^-:::---::::----::-^',
        '^------------------^',
        '====================',
        '^------------------^',
        '^~~~~----~~~~~~----^',
        '~~~~~~~-~~~~~~~~~-^^',
        '~~~~~~~~~~~~~~~~~~~~',
        '~~~~~~~~~~~~~~~~~~~~',
        '~~~~~~~~~~~~~~~~~~~~',
    ],
    'exits' => ['left' => ['dishan', 0], 'right' => ['jishan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '亶爰之山 —— 多水，无草木，不可以上'],
        ]],
    ],
    // The scree: 类, the one beast the classic gives this mountain.
    'wild' => ['rate' => 10, 'slots' => [[12, 'lei'], [13, 'lei'], [14, 'lei'], [13, 'lei'], [15, 'lei'], [14, 'lei'], [15, 'lei']]],
];
