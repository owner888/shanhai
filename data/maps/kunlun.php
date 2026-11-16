<?php
// 昆仑之丘, four hundred li southwest of 槐江之山. The classic: the spirit 陆吾 keeps it, a tiger's body with nine tails,
// a human face and a tiger's claws; the beast 土蝼 lives there, which eats people, the bird 钦原, whose sting kills
// beast and tree, and the bird 鹑鸟, which keeps the god's garments; four waters rise on it: the 河, the 赤水, the 洋水
// and the 黑水.
declare(strict_types=1);

return [
    'id' => 'kunlun',
    'name' => '昆仑之丘',
    'range' => 6,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^........^^........^',
        '^.,,,,,,.^^.,,,,,,.^',
        '^.,,,,,,....,,,,,,.^',
        '^.,,,,,,....,,,,,,.^',
        '^..................^',
        '^..................^',
        '====================',
        '^..................^',
        '^..,,,,,.....~~~~~.^',
        '^..,,,,,.....~~~~~.^',
        '^..,,,,,.....~~~~~.^',
        '^..................^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['leyou', 0], 'right' => ['huaijiang', 0]],
    'signs' => [
        [9, 6, [
            ['say', '昆仑之丘 —— 神陆吾司之，其神状虎身而九尾，人面而虎爪'],
            ['ifFlag', 'found_pincao', 'had_pincao'],
            ['setFlag', 'found_pincao'],
            ['give', 'pincao', 1],
            ['say', '牌子旁边有薲草。你采了一份。'],
            ['label', 'had_pincao'],
            ['ifFlag', 'found_shatang', 'had'],
            ['setFlag', 'found_shatang'],
            ['give', 'shatang', 1],
            ['say', '牌子旁边长着沙棠。你采了一份。'],
            ['label', 'had'],
        ]],
    ],
    // The tall grass: 土蝼, 钦原 and 鹑鸟.
    'wild' => ['rate' => 2, 'slots' => [[16, 'tulou'], [16, 'qinyuan'], [16, 'chunniao'], [16, 'tulou'], [15, 'qinyuan'], [15, 'chunniao'], [15, 'tulou']]],
];
