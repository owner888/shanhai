<?php
// 柢山, three hundred li east of 杻阳之山. The classic: much water, no grass or trees; 鯥 lives there, on the mounds.
// So the map has neither: bare ground, water, and the scree where 鯥 is met.
declare(strict_types=1);

return [
    'id' => 'dishan',
    'name' => '柢山',
    'range' => 1,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^~~~--::::--~~~~--^^',
        '^~~--::::::--~~~--^^',
        '^---:::::::---~~---^',
        '^----::::----------^',
        '^------------~~~---^',
        '^-----------~~~~~--^',
        '====================',
        '^--~~~-------------^',
        '^-~~~~~---::::-----^',
        '^--~~~---::::::--~~^',
        '^-------:::::::-~~~^',
        '^^------::::::-~~~~^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['niuyang', 0], 'right' => ['danyuan', 0]],
    'signs' => [
        [9, 6, [
            ['say', '柢山 —— 多水，无草木'],
        ]],
    ],
    // The scree: 鯥, the one beast the classic gives this mountain.
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 蝮虫、白猿、蝮虫、白猿 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[4, 'fuchong'], [4, 'baiyuan'], [5, 'fuchong'], [5, 'baiyuan'], [4, 'lu'], [5, 'lu'], [5, 'lu']]],
];
