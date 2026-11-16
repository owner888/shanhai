<?php
// 丰山, three hundred li southeast. The classic: the beast 雍和 lives there, an ape with red eyes, a red beak and a yellow
// body, at whose sight a state is in great fear; the god 耕父 lives there, who wanders the deep 清泠 and shines as he comes
// and goes, and at whose sight a state is ruined; nine bells on it that ring when the frost comes; gold on it, 榖, 柞, 杻
// and 橿 below.
declare(strict_types=1);

return [
    'id' => 'fengshan',
    'name' => '丰山',
    'range' => 25,
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
    'exits' => ['left' => ['qianshansy', 0], 'right' => ['tuchuang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '丰山 —— 神耕父处之，常游清泠之渊，出入有光，见则其国为败'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[46, 'yonghe'], [47, 'yonghe'], [47, 'yonghe'], [49, 'yonghe'], [48, 'yonghe'], [50, 'yonghe'], [50, 'yonghe']]],
];
