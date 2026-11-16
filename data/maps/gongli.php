<?php
// 攻离之山, forty li east. The classic: the 淯水 rises on it and runs south into the 汉; the bird 婴勺 lives there, a magpie with
// red eyes, a red beak and a white body, whose tail is like a ladle; many 㸲牛 and 羬羊.
declare(strict_types=1);

return [
    'id' => 'gongli',
    'name' => '攻离之山',
    'range' => 25,
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
    'exits' => ['left' => ['yaobi', 0], 'right' => ['zhidiao', 0]],
    'signs' => [
        [12, 6, [
            ['say', '攻离之山 —— 淯水出焉，南流注于汉；多㸲牛，多羬羊'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[46, 'yingshao'], [47, 'zuoniu'], [47, 'qianyang'], [49, 'yingshao'], [48, 'zuoniu'], [50, 'qianyang'], [50, 'yingshao']]],
];
