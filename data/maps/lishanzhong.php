<?php
// 釐山, a hundred and twenty li west. The classic: jade on its south side, madder on its north; the beast 犀渠 lives there,
// a grey-green ox which cries like a baby and eats people; the 滽滽之水 rises on it and runs south into the 伊水; and a beast
// like a 獳犬 with scales and a boar's bristles.
declare(strict_types=1);

return [
    'id' => 'lishanzhong',
    'name' => '釐山',
    'range' => 18,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^..................^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..,,,,,....,,,,,..^',
        '^..................^',
        '^..................^',
        '====================',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^........~~........^',
        '^^^^^^^^^~~^^^^^^^^^',
    ],
    'exits' => ['left' => ['fuzhushan', 0], 'right' => ['jiweizhong', 0]],
    'signs' => [
        [12, 6, [
            ['say', '釐山 —— 其阳多玉，其阴多蒐；滽滽之水出焉，而南流注于伊水'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[31, 'xiqu'], [32, 'xie'], [32, 'xiqu'], [34, 'xie'], [33, 'xiqu'], [35, 'xie'], [35, 'xiqu']]],
];
