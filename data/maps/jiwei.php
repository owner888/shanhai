<?php
// 箕尾之山, three hundred and fifty li east of 青丘之山, the last of the range. The classic: its tail runs down into
// the Eastern Sea; much sand and stone; the 汸水 rises on it and runs south into the 淯, with much white jade in it.
// It names no beast here.
declare(strict_types=1);

return [
    'id' => 'jiwei',
    'name' => '箕尾之山',
    'rows' => [
        '^^^^^^^^^^^^^^^~~~~~',
        '^^^----^^^----~~~~~~',
        '^^------^-----~~~~~~',
        '^-------------~~~~~~',
        '^--------------~~~~~',
        '^---------------~~~~',
        '^---------------~~~~',
        '================~~~~',
        '^----~~---------~~~~',
        '^---~~~--------~~~~~',
        '^---~~~-------~~~~~~',
        '^^--~~~~-----~~~~~~~',
        '^^^-~~~~----~~~~~~~~',
        '^^^^~~~~^^^^~~~~~~~~',
    ],
    'exits' => ['left' => ['qingqiu', 0]],
    'signs' => [
        [13, 6, [
            ['say', '箕尾之山 —— 其尾踆于东海，多沙石'],
        ]],
    ],
];
