<?php
// 平逢之山, ten li east: the classic's first of the range and the game's last. The classic: it looks south on the 伊 and the
// 洛, east on 谷城之山; no grass or trees, no water, much sand and stone; a god lives there shaped like a man with two heads,
// called 骄虫, lord of all that stings; it is the hive of the bees.
declare(strict_types=1);

return [
    'id' => 'pingfeng',
    'name' => '平逢之山',
    'range' => 20,
    'rows' => [
        '^^^^^^^^^^^^^^^^^^^^',
        '^------------------^',
        '^---^^^------------^',
        '^---^^^------------^',
        '^------------------^',
        '^------------------^',
        '^------------------^',
        '===================^',
        '^------------------^',
        '^------------------^',
        '^-----------^^^----^',
        '^-----------^^^----^',
        '^------------------^',
        '^^^^^^^^^^^^^^^^^^^^',
    ],
    'exits' => ['left' => ['gaodi', 0]],
    'signs' => [
        [9, 6, [
            ['say', '平逢之山 —— 南望伊洛，东望谷城之山，无草木，无水，多沙石'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedZhongciliu', 'passed'],
            ['say', '沙石地上站着一个人，长着两个头。四周嗡嗡的，全是蜂。'],
            ['ask', '「我是骄虫。自阳华至平逢，十四山，你是倒着走过来的。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '骄虫', [['qianyang', 71], ['xiubi', 73], ['lingyao', 73], ['jiaochong', 75]]],
            ['setFlag', 'passedZhongciliu'],
            ['say', '「好。往下是苦山。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['say', '「中次七经的路还没有开。」'],
        ]],
    ],
];
