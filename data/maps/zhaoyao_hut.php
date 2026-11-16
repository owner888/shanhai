<?php
// The shaman's hut at the foot of 招摇之山.
declare(strict_types=1);

return [
    'id' => 'zhaoyao_hut',
    'name' => '巫祝的小屋',
    'range' => 1,
    'indoor' => true,
    'rows' => [
        'HHHHHHHH',
        'H______H',
        'H______H',
        'H______H',
        'H______H',
        'HHHDHHHH',
    ],
    'warps' => [
        [3, 5, 'zhaoyao', 5, 6, 'down'],
    ],
    'npcs' => [
        ['shaman', 4, 2, 'down', 'shaman', [
            ['ifFlag', 'metShaman', 'again'],
            ['say', '山海之间，异兽无数。你若想走遍群山，得先有一只与你同行的异兽。'],
            ['say', '鹿蜀、旋龟、灌灌，三者择一。'],
            ['label', 'pick'],
            ['ask', '你想与哪一只同行？', [['鹿蜀', 'lushu'], ['旋龟', 'xuangui'], ['灌灌', 'guanguan']]],
            ['label', 'lushu'],
            ['ask', '鹿蜀，形如马，白首，身有虎纹，赤尾，其音如人歌唱。就是它了吗？', [['就是它', 'takeLushu'], ['再想想', 'pick']]],
            ['label', 'xuangui'],
            ['ask', '旋龟，怪水中的龟，鸟首，虺尾，其音如劈木。就是它了吗？', [['就是它', 'takeXuangui'], ['再想想', 'pick']]],
            ['label', 'guanguan'],
            ['ask', '灌灌，形如鸠，其音如人呵斥。就是它了吗？', [['就是它', 'takeGuanguan'], ['再想想', 'pick']]],
            ['label', 'takeLushu'],
            ['giveBeast', 'lushu', 5],
            ['goto', 'taken'],
            ['label', 'takeXuangui'],
            ['giveBeast', 'xuangui', 5],
            ['goto', 'taken'],
            ['label', 'takeGuanguan'],
            ['giveBeast', 'guanguan', 5],
            ['label', 'taken'],
            ['say', '好，从今往后它与你同行。这几枚契印也拿去：遇到愿与你同行的异兽，就用它结契。'],
            ['give', 'seal', 5],
            ['setFlag', 'metShaman'],
            ['end'],
            ['label', 'again'],
            ['say', '回来了。来，让你的异兽歇一歇。'],
            ['rest'],
            ['say', '好了，它们都恢复了精神。契印也再拿几枚去。'],
            ['give', 'seal', 5],
        ]],
    ],
];
