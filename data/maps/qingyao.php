<?php
// 青要之山, ten li east. The classic: the hidden city of 帝; many 驾鸟; to the south 墠渚, with many 仆累 and 蒲卢; the god 武罗 keeps
// it, with a man's face and a leopard's markings; the 畛水 rises on it and runs north into the 河, and in it is the bird 鴢,
// a duck with a green body, red eyes and a red tail, met here on the ground by the water; and the herb 荀草.
declare(strict_types=1);

return [
    'id' => 'qingyao',
    'name' => '青要之山',
    'range' => 17,
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
    'exits' => ['left' => ['aoan', 0], 'right' => ['guishanzhong', 0]],
    'signs' => [
        [12, 6, [
            ['say', '青要之山 —— 实惟帝之密都；神武罗司之；畛水出焉，而北流注于河'],
        ]],
    ],
    // Its wild ground. The range is past the reference's sixteen gyms: its levels are ours, about half its trial's last (docs/numbers.md 14).
    'wild' => ['rate' => 10, 'slots' => [[29, 'yaoniao'], [30, 'jianiao'], [30, 'pulei'], [32, 'yaoniao'], [31, 'jianiao'], [33, 'pulei'], [33, 'yaoniao']]],
];
