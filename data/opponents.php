<?php
// Those met on the roads: people of the mountains who have beasts bound to them and try whoever passes. The player's
// beasts grow mostly by them, as in the reference a player's do by the trainers on its routes (docs/numbers.md 15).
//
// How many there are in a range, how many beasts each sends, at what levels, and how far each sees, are the
// reference's own, trainer for trainer in the order its routes and gym are walked, before the gym of the range's
// number. Which beasts they send is ours: for each of the reference's, one of this range or an earlier whose base
// stats add up nearest to its own, those as near taken in turn.
// Where they stand is ours: beside the road, looking across it.
//   needs    the flag without which they do not fight: the player has a beast
//   unready  what they say to one who has none
//   says     [what is said before, when beaten, when met again], taken in turn
//   at       map => [[x, y, facing, sight, who, [[beast, level], ...]], ...]
// Data::maps() puts each on its map as a person (id foe0, foe1, ...) whose script is the challenge, and who watches.
declare(strict_types=1);

return [
    'needs' => 'metShaman',
    'unready' => '「你身边没有异兽。山路上不太平，快回去吧。」',
    'says' => [
        ['「站住。让我看看你的异兽。」', '「是我输了。」', '「你的异兽养得不错。」'],
        ['「走这条路的，都要和我比一场。」', '「好本事。」', '「前面的路还长，当心些。」'],
        ['「我的异兽正想活动活动。」', '「它尽力了。」', '「下次再比。」'],
        ['「山里难得见到生人。来，比一场。」', '「输得心服。」', '「一路平安。」'],
    ],
    'at' => [
        'tangting' => [
            [7, 6, 'down', 3, '采玉人', [['fuchong', 4]]],
            [13, 8, 'up', 1, '牧人', [['fuchong', 2], ['fuchong', 4]]],
        ],
        'yuanyi' => [
            [10, 6, 'down', 3, '采药人', [['fuchong', 3], ['fuchong', 3]]],
        ],
        'niuyang' => [
            [7, 8, 'up', 5, '采玉人', [['fuchong', 2], ['fuchong', 2], ['fuchong', 3], ['fuchong', 2]]],
            [13, 8, 'up', 4, '采药人', [['baiyuan', 3], ['xingxing', 3], ['baiyuan', 3]]],
        ],
        'dishan' => [
            [10, 6, 'down', 4, '行商', [['xingxing', 3], ['baiyuan', 3], ['xingxing', 3]]],
        ],
        'danyuan' => [
            [7, 6, 'down', 5, '樵夫', [['baiyuan', 3], ['xingxing', 3], ['baiyuan', 3]]],
            [13, 8, 'up', 3, '牧人', [['xingxing', 6]]],
        ],
        'jishan' => [
            [10, 6, 'down', 2, '采药人', [['baiyuan', 7], ['fuchong', 7]]],
        ],
        'qingqiu' => [
            [7, 6, 'down', 3, '巫祝弟子', [['baiyuan', 6]]],
            [13, 8, 'up', 3, '采玉人', [['fuchong', 7], ['fuchong', 7]]],
        ],
        'jiwei' => [
            [10, 6, 'down', 3, '采玉人', [['fuchong', 9]]],
        ],
        'guishan' => [
            [7, 6, 'down', 1, '樵夫', [['fuchong', 9]]],
            [13, 8, 'up', 3, '行商', [['biluo', 5], ['fuchong', 5], ['jiyu', 15], ['biluo', 5]]],
        ],
        'changyou' => [
            [10, 6, 'down', 1, '行商', [['xingxing', 10]]],
        ],
        'yaoguang' => [
            [7, 6, 'down', 3, '采玉人', [['baiyuan', 8], ['biluo', 8]]],
            [13, 8, 'up', 3, '渔人', [['fuchong', 9]]],
        ],
        'yushan' => [
            [10, 6, 'down', 3, '牧人', [['jiyu', 6], ['biluo', 8]]],
        ],
        'qufu' => [
            [10, 6, 'down', 1, '采药人', [['fuchong', 10]]],
        ],
        'gouyu' => [
            [7, 6, 'down', 3, '猎户', [['jiyu', 6], ['biluo', 6], ['fuchong', 8]]],
            [13, 8, 'up', 2, '樵夫', [['xingxing', 10]]],
        ],
        'fuyu' => [
            [10, 6, 'down', 3, '牧人', [['biluo', 4], ['xingxing', 6], ['baiyuan', 8]]],
        ],
        'chengshan' => [
            [10, 6, 'down', 1, '采药人', [['boyi', 11]]],
        ],
        'kuaiji' => [
            [7, 6, 'down', 4, '行商', [['xingxing', 6], ['lu', 6]]],
            [13, 8, 'up', 4, '采玉人', [['biluo', 9]]],
        ],
        'yishan' => [
            [10, 6, 'down', 2, '樵夫', [['xingxing', 11], ['baiyuan', 11]]],
        ],
        'pugou' => [
            [10, 6, 'down', 3, '牧人', [['biluo', 9], ['fuchong', 9]]],
        ],
        'xianyin' => [
            [7, 6, 'down', 1, '采药人', [['lei', 14]]],
            [13, 8, 'up', 2, '采玉人', [['biluo', 7], ['fuchong', 9], ['jiyu', 9]]],
        ],
        'xunshan' => [
            [10, 8, 'up', 4, '樵夫', [['biluo', 9], ['biluo', 11]]],
        ],
        'huzhuo' => [
            [10, 6, 'down', 2, '牧人', [['jiyu', 10], ['biluo', 10]]],
        ],
        'quwu' => [
            [7, 6, 'down', 3, '巫祝弟子', [['fuchong', 10], ['jiyu', 10]]],
            [13, 8, 'up', 3, '樵夫', [['biluo', 7], ['fuchong', 9], ['chiru', 12]]],
        ],
        'luwu' => [
            [10, 6, 'down', 1, '渔人', [['biluo', 12], ['fuchong', 12]]],
        ],
        'qiwu' => [
            [10, 6, 'down', 1, '牧人', [['biluo', 13]]],
        ],
        'tianyu' => [
            [7, 6, 'down', 0, '巫祝弟子', [['biluo', 8], ['fuchong', 10]]],
            [13, 8, 'up', 5, '猎户', [['xingxing', 14]]],
        ],
        'daoguo' => [
            [7, 6, 'down', 3, '樵夫', [['biluo', 9], ['fuchong', 9], ['xingxing', 12]]],
            [13, 8, 'up', 3, '行商', [['biluo', 7], ['xingxing', 10], ['jiyu', 8], ['biluo', 8]]],
        ],
        'danxue' => [
            [7, 6, 'down', 3, '猎户', [['xingxing', 10], ['jiyu', 12]]],
            [13, 8, 'up', 3, '樵夫', [['biluo', 13]]],
        ],
        'fashuang' => [
            [10, 6, 'down', 2, '渔人', [['fuchong', 12], ['xi', 14]]],
        ],
        'maoshan' => [
            [7, 6, 'down', 3, '行商', [['xingxing', 2], ['xingxing', 6], ['xingxing', 10], ['xingxing', 14]]],
            [13, 8, 'up', 3, '渔人', [['fuchong', 10], ['jiyu', 10], ['biluo', 14]]],
        ],
        'feishan' => [
            [7, 6, 'down', 1, '牧人', [['xingxing', 13], ['jiyu', 15]]],
            [13, 8, 'up', 0, '巫祝弟子', [['biluo', 16]]],
        ],
        'yangjia' => [
            [10, 8, 'up', 2, '巫祝弟子', [['xingxing', 15]]],
        ],
        'guanxiang' => [
            [7, 6, 'down', 3, '行商', [['xingxing', 15]]],
            [13, 8, 'up', 2, '樵夫', [['hujiao', 11], ['changyou', 13]]],
        ],
        'jishan3' => [
            [7, 6, 'down', 3, '渔人', [['xingxing', 12], ['xingxing', 15]]],
            [13, 8, 'up', 2, '采药人', [['xuangui', 14]]],
        ],
        'lingqiu' => [
            [10, 8, 'up', 2, '采药人', [['baiyuan', 14]]],
        ],
        'lunzhe' => [
            [7, 6, 'down', 3, '行商', [['xingxing', 12], ['xingxing', 15]]],
            [13, 8, 'up', 4, '采玉人', [['baiyuan', 18]]],
        ],
        'yugao' => [
            [7, 6, 'down', 1, '渔人', [['biluo', 15], ['fuchong', 15], ['jiyu', 15]]],
            [13, 8, 'up', 3, '行商', [['biluo', 9], ['fuchong', 13], ['jiyu', 17]]],
        ],
        'nanyu' => [
            [10, 6, 'down', 3, '猎户', [['baiyuan', 16], ['biluo', 16]]],
        ],
        'qianlai' => [
            [10, 6, 'down', 3, '采玉人', [['zhi', 16]]],
        ],
        'taihua' => [
            [10, 6, 'down', 5, '牧人', [['ling', 13], ['she', 13], ['huan', 15]]],
        ],
        'fuyu4' => [
            [10, 6, 'down', 1, '巫祝弟子', [['xingxing', 16], ['fuchong', 16]]],
        ],
        'yingshan' => [
            [10, 6, 'down', 1, '猎户', [['jiyu', 16], ['she', 16]]],
        ],
        'fushan' => [
            [10, 8, 'up', 1, '采玉人', [['xingxing', 17]]],
        ],
        'shishan' => [
            [10, 6, 'down', 1, '樵夫', [['conglong', 22]]],
        ],
        'dashi' => [
            [10, 6, 'down', 3, '行商', [['baihan', 16], ['ling', 16], ['she', 16], ['xingxing', 16], ['shijiu', 16]]],
        ],
        'tiandi' => [
            [10, 8, 'up', 1, '樵夫', [['baihan', 18], ['xiang', 20], ['she', 20]]],
        ],
        'huangshan' => [
            [10, 8, 'up', 1, '牧人', [['conglong', 20], ['mengbao', 20]]],
        ],
        'qianshan' => [
            [5, 6, 'down', 1, '行商', [['xingxing', 15], ['shijiu', 16], ['baihan', 17]]],
            [10, 8, 'up', 2, '采玉人', [['fuchong', 19]]],
            [15, 6, 'down', 1, '牧人', [['min', 18], ['ling', 18]]],
        ],
        'taimao' => [
            [7, 6, 'down', 1, '渔人', [['fuxi', 19]]],
            [13, 8, 'up', 2, '巫祝弟子', [['jiyu', 17], ['xingxing', 17]]],
        ],
        'shuli' => [
            [7, 6, 'down', 1, '巫祝弟子', [['chibi', 19]]],
            [13, 8, 'up', 5, '猎户', [['shijiu', 17]]],
        ],
        'gaoshan' => [
            [7, 6, 'down', 4, '猎户', [['she', 17]]],
            [13, 8, 'up', 4, '牧人', [['lu', 17], ['zhu', 17], ['baihan', 19]]],
        ],
        'nuchuang' => [
            [7, 6, 'down', 1, '采药人', [['ling', 17], ['she', 20]]],
            [13, 8, 'up', 1, '渔人', [['biluo', 17], ['fuchong', 15], ['jiyu', 19], ['biluo', 15], ['fuchong', 15]]],
        ],
        'longshou' => [
            [7, 6, 'down', 4, '牧人', [['min', 18], ['feiyi', 18]]],
            [13, 8, 'up', 3, '巫祝弟子', [['conglong', 20]]],
        ],
        'lutai' => [
            [7, 6, 'down', 3, '巫祝弟子', [['jiyu', 21]]],
            [13, 8, 'up', 1, '采玉人', [['she', 18], ['yelu', 20]]],
        ],
        'niaowei' => [
            [7, 6, 'down', 3, '渔人', [['jiyu', 18], ['haozhi', 20], ['fuchong', 18]]],
            [13, 8, 'up', 4, '行商', [['shijiu', 18], ['baihan', 18], ['baihao', 18]]],
        ],
        'xiaoci' => [
            [7, 6, 'down', 4, '行商', [['baihan', 21]]],
            [13, 8, 'up', 5, '樵夫', [['ling', 19], ['xingxing', 19]]],
        ],
        'daci' => [
            [7, 6, 'down', 4, '渔人', [['qianyang', 20], ['tongqu', 20]]],
            [13, 8, 'up', 3, '行商', [['baihan', 18], ['quru', 20], ['she', 18]]],
        ],
        'xunwu' => [
            [7, 6, 'down', 3, '采玉人', [['yelu', 18], ['xingxing', 20], ['zhi', 20]]],
            [13, 8, 'up', 3, '牧人', [['ling', 20], ['she', 22]]],
        ],
        'diyang' => [
            [7, 6, 'down', 3, '牧人', [['yelu', 22]]],
            [13, 8, 'up', 3, '行商', [['xingxing', 20], ['jueru', 20]]],
        ],
        'zhongshou' => [
            [7, 6, 'down', 3, '猎户', [['biluo', 21], ['shijiu', 21]]],
            [13, 8, 'up', 3, '牧人', [['shijiu', 21], ['baihan', 19], ['leiniao', 19]]],
        ],
        'huangren' => [
            [7, 6, 'down', 3, '采玉人', [['she', 16], ['yelu', 17], ['xingxing', 16], ['xingxing', 19], ['tongqu', 17], ['xingxing', 19]]],
            [13, 8, 'up', 3, '渔人', [['xiao', 23]]],
        ],
        'zhonghuang' => [
            [7, 6, 'down', 4, '牧人', [['leiniao', 20], ['zhuyan', 20]]],
            [13, 8, 'up', 2, '巫祝弟子', [['baihan', 23]]],
        ],
        'xihuang' => [
            [7, 6, 'down', 3, '巫祝弟子', [['luoluo', 27]]],
            [13, 8, 'up', 3, '猎户', [['lushu', 27]]],
        ],
        'laishan' => [
            [7, 6, 'down', 2, '采玉人', [['baihan', 25], ['mengbao', 25]]],
            [13, 8, 'up', 1, '采药人', [['she', 23], ['yelu', 23], ['huahuai', 25]]],
        ],
    ],
];
