<?php
// 堇理之山, a hundred li northwest. The classic: pines, cypresses and fine 梓; 丹雘 and gold on its north side; its beasts are
// mostly leopards and tigers; the bird 青耕 lives there, a magpie with a green body, a white beak, white eyes and a white
// tail, which wards off plague.
declare(strict_types=1);

return [
    'id' => 'jinli',
    'name' => '堇理之山',
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
    'exits' => ['left' => ['zhidiao', 0], 'right' => ['yigu', 0]],
    'signs' => [
        [9, 6, [
            ['say', '堇理之山 —— 其上多松柏，多美梓，其阴多丹雘，多金，其兽多豹虎'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 㚟 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[46, 'bao'], [47, 'hu'], [47, 'chuotu'], [49, 'bao'], [48, 'qinggeng'], [50, 'qinggeng'], [50, 'qinggeng']]],
];
