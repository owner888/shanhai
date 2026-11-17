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
    // The frequent places are the common beasts' of the range (docs/numbers.md 28): 㸲牛、赤鷩、㸲牛、赤鷩 come here from its other mountains; the rare places are the mountain's own.
    'wild' => ['rate' => 10, 'slots' => [[30, 'zuoniu'], [31, 'chibi'], [31, 'zuoniu'], [33, 'chibi'], [32, 'xie'], [34, 'xiqu'], [34, 'xie']]],
];
