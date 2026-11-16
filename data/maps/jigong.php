<?php
// 即公之山, two hundred li southeast. The classic: gold on it, 㻬琈 jade below; willow, 杻, 檀 and mulberry; a beast lives there
// like a tortoise, with a white body and a red head, called 蛫, which wards off fire.
declare(strict_types=1);

return [
    'id' => 'jigong',
    'name' => '即公之山',
    'range' => 26,
    'rows' => [
        'TTTTTTTTTTTTTTTTTTTT',
        'T..................T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..,,,,,....,,,,,..T',
        'T..............TT..T',
        'T..................T',
        '====================',
        'T..................T',
        'T.......,,,,,......T',
        'T.......,,,,,^^....T',
        'T.......,,,,,^^....T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['baoshan', 0], 'right' => ['yaoshanyao', 0]],
    'signs' => [
        [9, 6, [
            ['say', '即公之山 —— 有兽焉，其状如龟，而白身赤首，名曰蛫，是可以御火'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[48, 'guigui'], [49, 'guigui'], [49, 'guigui'], [51, 'guigui'], [50, 'guigui'], [52, 'guigui'], [52, 'guigui']]],
];
