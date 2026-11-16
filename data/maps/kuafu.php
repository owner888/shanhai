<?php
// 夸父之山, ninety li east of 阳华之山. The classic: 棕 and 楠, bamboo; its beasts are mostly 㸲牛 and 羬羊, its birds 赤鷩; jade on its
// south side, iron on its north; north of it the forest 桃林, three hundred li round, with many horses; the 湖水 rises on it
// and runs north into the 河.
declare(strict_types=1);

return [
    'id' => 'kuafu',
    'name' => '夸父之山',
    'range' => 20,
    'rows' => [
        'TTTTTTTTT~~TTTTTTTTT',
        'T........~~........T',
        'T..,,,,,.~~.,,,,,..T',
        'T..,,,,,.~~.,,,,,..T',
        'T..,,,,,.~~.,,,,,..T',
        'T........~~........T',
        'T..................T',
        '====================',
        'T..................T',
        'T..,,,,,...........T',
        'T..,TT,,...........T',
        'T..,TT,,...........T',
        'T..................T',
        'TTTTTTTTTTTTTTTTTTTT',
    ],
    'exits' => ['left' => ['yanghua', 0], 'right' => ['changzheng', 0]],
    'signs' => [
        [12, 6, [
            ['say', '夸父之山 —— 其木多棕楠，多竹箭；其兽多㸲牛、羬羊，其鸟多赤鷩；其北有林焉，名曰桃林'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[36, 'zuoniu'], [37, 'qianyang'], [37, 'chibi'], [39, 'zuoniu'], [38, 'qianyang'], [40, 'chibi'], [40, 'zuoniu']]],
];
