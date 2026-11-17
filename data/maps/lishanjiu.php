<?php
// 鬲山, five hundred li east. The classic: gold on its south side, white 珉 on its north; the 蒲薨之水 rises on it and runs
// east into the 江, with much white jade in it; its beasts are mostly rhinoceros, elephants, bears, 罴, apes and 蜼. The
// apes are not in the data.
declare(strict_types=1);

return [
    'id' => 'lishanjiu',
    'name' => '鬲山',
    'range' => 23,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^...........~~~~~~~^',
        '^...........~~~~~~~^',
        '^..................^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['sheshan', 0], 'right' => ['yuyang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '鬲山 —— 其阳多金，其阴多白珉；蒲薨之水出焉，而东流注于江'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[35, 'xi'], [36, 'xiang'], [36, 'xiong'], [38, 'pi'], [37, 'weihou'], [39, 'xi'], [39, 'xiang']]],
];
