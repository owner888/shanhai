<?php
// 鼓镫之山, four hundred li northeast, the last of the range. The classic: red copper; a herb called 荣草, which cures 风. It
// names no beast.
declare(strict_types=1);

return [
    'id' => 'gudeng',
    'name' => '鼓镫之山',
    'range' => 15,
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
    'exits' => ['left' => ['yinshanzhong', 0]],
    'signs' => [
        [9, 6, [
            ['say', '鼓镫之山 —— 多赤铜；有草焉，名曰荣草，食之已风'],
        ]],
    ],
    'npcs' => [
        ['god', 18, 7, 'left', 'god', [
            ['ifFlag', 'passedZhongshan', 'passed'],
            ['say', '路的尽头立着薄山的神。书上只写了怎么祭它，没有写它的样子。'],
            ['ask', '「自甘枣至鼓镫，十五山。要受我的试炼吗？」', [['接受试炼', 'go'], ['再等等', 'wait']]],
            ['label', 'go'],
            ['trial', '中山经之首之神', [['na', 45], ['feifei', 45], ['zhongshan', 50]], ['youmu', 'yupei']],
            ['setFlag', 'passedZhongshan'],
            ['say', '「好。中山经一共十二个山系，这是头一个。想往下走时再来找我。」'],
            ['give', 'sealLevel', 1],
            ['give', 'sealWater', 1],
            ['give', 'sealKin', 1],
            ['give', 'sealFast', 1],
            ['say', '「这四枚契印也拿去：威契印、水契印、亲契印、速契印各一枚。」'],
            ['give', 'yubi', 1],
            ['say', '「这枚璧玉也拿去。受过璋礼的常兽，或者本来就不弱的常兽，到了三十六级，可以凭它受璧礼。」'],
            ['end'],
            ['label', 'wait'],
            ['say', '「准备好了再来。」'],
            ['end'],
            ['label', 'passed'],
            ['ask', '「中次二经之首是济山的煇诸之山。要我送你去吗？」', [['去煇诸之山', 'send'], ['留在这里', 'stay'], ['受祭', 'rite']]],
            ['label', 'send'],
            ['warp', 'huizhu', 2, 7, 'right'],
            ['label', 'stay'],
            ['end'],
            ['label', 'rite'],
            ['rite'],
        ]],
    ],
];
