<?php
// Those met on the roads: people of the mountains who have beasts bound to them and try whoever passes. The player's
// beasts grow mostly by them, as in the reference a player's do by the trainers on its routes (docs/numbers.md 15).
//
// How many there are in a range, how many beasts each sends, at what levels, and how far each sees, are the
// reference's own, trainer for trainer in the order its routes and gym are walked, before the gym of the range's
// number. Past its sixteenth gym there are none to count, and the numbers are ours, by what its first half
// measures (docs/numbers.md 15). Which beasts they send is ours: for each of the reference's, one of this range or an earlier whose base
// stats add up nearest to its own, those as near taken in turn.
// Where they stand is ours: beside the road, looking across it.
//   needs    the flag without which they do not fight: the player has a beast
//   unready  what they say to one who has none
//   says     [what is said before, when beaten, when met again], taken in turn
//   at       map => [[x, y, facing, sight, who, [[beast, level], ...]], ...]; a beast that holds a thing is [beast, level, thing]:
//            those of the reference's that hold one hold ours of the same effect, and past its sixteenth gym as many do (docs/numbers.md 20)
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
            [13, 8, 'up', 3, '樵夫', [['biluo', 13, 'baigao']]],
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
            [13, 8, 'up', 2, '采药人', [['xuangui', 14, 'baigao']]],
        ],
        'lingqiu' => [
            [10, 8, 'up', 2, '采药人', [['baiyuan', 14, 'baigao']]],
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
            [13, 8, 'up', 5, '猎户', [['shijiu', 17, 'baigao']]],
        ],
        'gaoshan' => [
            [7, 6, 'down', 4, '猎户', [['she', 17, 'baigao']]],
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
        'yinshansi' => [
            [7, 6, 'down', 1, '采药人', [['hu', 18]]],
            [13, 8, 'up', 3, '猎户', [['si', 16], ['qianyang', 16]]],
        ],
        'laoshan' => [
            [10, 6, 'down', 3, '樵夫', [['fuchong', 14], ['she', 14], ['zhuo', 16]]],
        ],
        'bafu' => [
            [10, 6, 'down', 2, '采药人', [['xingxing', 17], ['fuxi', 17], ['biluo', 19]]],
        ],
        'shenshan' => [
            [10, 6, 'down', 3, '巫祝弟子', [['aoye', 19]]],
        ],
        'niaoshan' => [
            [7, 6, 'down', 2, '行商', [['chibi', 19]]],
            [13, 8, 'up', 4, '采玉人', [['qiongqi', 19]]],
        ],
        'shangshen' => [
            [10, 6, 'down', 2, '牧人', [['fuchong', 10], ['zhuyan', 10], ['biluo', 15], ['zheng', 15]]],
        ],
        'zhuci' => [
            [10, 6, 'down', 3, '采药人', [['yelu', 20]]],
        ],
        'haoshan' => [
            [10, 8, 'up', 3, '猎户', [['luomu', 20], ['jishe', 20], ['zhongshe', 20]]],
        ],
        'yushansi' => [
            [10, 6, 'down', 3, '牧人', [['biluo', 16], ['fuchong', 16], ['jiyu', 16], ['biluo', 16]]],
        ],
        'baiyu' => [
            [7, 6, 'down', 3, '采药人', [['xibian', 18]]],
            [13, 8, 'up', 1, '采玉人', [['jiyu', 17], ['biluo', 17], ['fuchong', 17]]],
        ],
        'shenshou' => [
            [10, 6, 'down', 4, '樵夫', [['baiyuan', 18], ['shijiu', 18]]],
        ],
        'jinggu' => [
            [10, 6, 'down', 0, '牧人', [['baiyuan', 18], ['maoniu', 18]]],
        ],
        'gangshan' => [
            [10, 6, 'down', 3, '采药人', [['chi', 19]]],
        ],
        'gangwei' => [
            [10, 6, 'down', 0, '行商', [['baidi', 22], ['xingxing', 22]]],
        ],
        'yingdi' => [
            [7, 6, 'down', 3, '猎户', [['shijiu', 24]]],
            [13, 8, 'up', 1, '樵夫', [['shengyu', 28]]],
        ],
        'zhongqu' => [
            [10, 6, 'down', 1, '樵夫', [['zhuyan', 28]]],
        ],
        'guishansi' => [
            [10, 8, 'up', 1, '采药人', [['baidi', 24], ['junniao', 25], ['baihan', 24]]],
        ],
        'niaoshu' => [
            [10, 6, 'down', 1, '行商', [['biluo', 26], ['fuchong', 26]]],
        ],
        'yanzi' => [
            [10, 6, 'down', 1, '樵夫', [['she', 24], ['bifang', 25], ['luomu', 24]]],
        ],
        'danhu' => [
            [10, 6, 'down', 1, '牧人', [['baishe', 25], ['yelu', 25]]],
        ],
        'daishan' => [
            [10, 6, 'down', 1, '巫祝弟子', [['xingxing', 24], ['maoniu', 26]]],
        ],
        'zhuoguang' => [
            [10, 6, 'down', 3, '采玉人', [['ling', 23], ['she', 23], ['xiao', 25]]],
        ],
        'guowei' => [
            [10, 6, 'down', 3, '渔人', [['chigui', 25], ['xingxing', 25]]],
        ],
        'shizhe' => [
            [10, 6, 'down', 2, '采药人', [['lu', 25], ['zhuhuai', 25]]],
        ],
        'manlian' => [
            [10, 6, 'down', 5, '巫祝弟子', [['feiyi', 27]]],
        ],
        'guanti' => [
            [10, 6, 'down', 5, '行商', [['tuotuo', 27]]],
        ],
        'xiaoxian' => [
            [10, 6, 'down', 3, '樵夫', [['chigui', 34], ['xingxing', 34], ['baiyuan', 34]]],
        ],
        'dunhong' => [
            [10, 6, 'down', 3, '渔人', [['zhu', 37]]],
        ],
        'yufa' => [
            [10, 6, 'down', 1, '采药人', [['she', 34], ['haozhi', 36]]],
        ],
        'hunxi' => [
            [10, 6, 'down', 1, '行商', [['shijiu', 34], ['lili', 36]]],
        ],
        'picha' => [
            [10, 6, 'down', 1, '猎户', [['zhu', 37]]],
        ],
        'guancen' => [
            [7, 6, 'down', 3, '渔人', [['zhuhuai', 30], ['zhongshe', 30], ['yao', 34]]],
            [13, 8, 'up', 4, '采药人', [['tongqu', 34]]],
        ],
        'shaoyang' => [
            [10, 6, 'down', 4, '行商', [['shuhu', 33], ['minniu', 31], ['baiyou', 31]]],
        ],
        'xianyong' => [
            [10, 6, 'down', 2, '猎户', [['changshe', 35]]],
        ],
        'huqi' => [
            [7, 6, 'down', 3, '渔人', [['aoye', 31], ['yayu', 33], ['qiongqi', 31]]],
            [13, 8, 'up', 3, '行商', [['she', 32], ['xiaoniao', 32], ['feiyishe', 32]]],
        ],
        'baisha' => [
            [10, 6, 'down', 2, '猎户', [['tuotuo', 33], ['paoxiao', 35]]],
        ],
        'ershi' => [
            [10, 6, 'down', 3, '渔人', [['renmianxiao', 32], ['jiao', 32], ['zheng', 32]]],
        ],
        'kuangshan' => [
            [7, 6, 'down', 3, '采药人', [['luomu', 36], ['aoye', 32]]],
            [13, 8, 'up', 4, '行商', [['bo', 36]]],
        ],
        'zhuyu' => [
            [10, 6, 'down', 2, '行商', [['qiongqi', 36]]],
        ],
        'duntou' => [
            [10, 6, 'down', 3, '樵夫', [['minniu', 30], ['hu', 30], ['jufu', 34]]],
        ],
        'gouwu' => [
            [7, 6, 'down', 0, '渔人', [['yelu', 35, 'baigao']]],
            [13, 8, 'up', 0, '采药人', [['luomu', 35, 'baigao']]],
        ],
        'beixiao' => [
            [10, 6, 'down', 3, '采药人', [['tongqu', 35]]],
        ],
        'liangqu' => [
            [10, 6, 'down', 3, '行商', [['tiangou', 33], ['zheng', 33]]],
        ],
        'guguan' => [
            [7, 6, 'down', 1, '樵夫', [['fan', 30], ['jiaoniao', 30], ['bo', 30]]],
            [13, 8, 'up', 3, '牧人', [['ranyi', 34]]],
        ],
        'huguan' => [
            [10, 6, 'down', 4, '采药人', [['shanyu', 37], ['huayub', 33]]],
        ],
        'huanshan' => [
            [10, 6, 'down', 3, '采玉人', [['baihan', 27], ['ling', 33], ['she', 32], ['yelu', 32]]],
        ],
        'dunti' => [
            [10, 6, 'down', 4, '采药人', [['qinyuan', 33], ['jiao', 33], ['shengyu', 33], ['zheng', 33]]],
        ],
        'guishanbei' => [
            [10, 6, 'down', 5, '采玉人', [['shanyu', 27], ['huyu', 28], ['meng', 29], ['liu', 30]]],
        ],
        'yangshan' => [
            [10, 6, 'down', 5, '樵夫', [['dashe', 34]]],
        ],
        'mengmen' => [
            [10, 6, 'down', 5, '牧人', [['xingxing', 32], ['xi', 32]]],
        ],
        'xiaohou' => [
            [10, 6, 'down', 3, '巫祝弟子', [['xingxing', 34], ['bifang', 34]]],
        ],
        'shenqun' => [
            [10, 6, 'down', 4, '采玉人', [['ling', 32], ['she', 32], ['yelu', 32]]],
        ],
        'xiushan' => [
            [10, 6, 'down', 3, '渔人', [['luomu', 35], ['huanshu', 35]]],
        ],
        'baima' => [
            [10, 6, 'down', 3, '牧人', [['bailang', 37]]],
        ],
        'gaoshi' => [
            [10, 6, 'down', 2, '巫祝弟子', [['baidi', 34], ['qiongqi', 36]]],
        ],
        'qianshanbei' => [
            [10, 6, 'down', 2, '采玉人', [['heluo', 32], ['chigui', 32], ['baiyou', 35]]],
        ],
        'sudu' => [
            [10, 6, 'down', 4, '牧人', [['yelu', 30], ['bailang', 30], ['longgui', 30]]],
        ],
        'leishan' => [
            [10, 6, 'down', 1, '采药人', [['boma', 34]]],
        ],
        'xunzhuang' => [
            [10, 6, 'down', 3, '行商', [['jingwei', 32], ['dashe', 32]]],
        ],
        'boqi' => [
            [10, 6, 'down', 4, '樵夫', [['ganyu', 28], ['tiaoyong', 30], ['tianma', 32]]],
        ],
        'fantiao' => [
            [10, 6, 'down', 3, '采药人', [['luomu', 30], ['jishe', 28], ['banmao', 32]]],
        ],
        'guer' => [
            [10, 6, 'down', 3, '巫祝弟子', [['congcong', 34]]],
        ],
        'gaoshidong' => [
            [10, 6, 'down', 2, '采玉人', [['baidi', 32], ['fuchong', 33], ['mengji', 34]]],
        ],
        'yueshan' => [
            [10, 6, 'down', 2, '樵夫', [['zhuhuai', 37]]],
        ],
        'chaishan' => [
            [10, 6, 'down', 2, '采药人', [['baiyuan', 32], ['meng', 32], ['guxi', 35]]],
        ],
        'dushan' => [
            [10, 6, 'down', 1, '行商', [['aoye', 35], ['sanqingniao', 35]]],
        ],
        'taishan' => [
            [10, 6, 'down', 1, '采玉人', [['huanshou', 35], ['qitu', 35]]],
        ],
        'kongsangdong' => [
            [7, 6, 'down', 1, '牧人', [['haozhi', 28], ['maoniu', 31], ['xiao', 31]]],
            [13, 8, 'up', 1, '行商', [['baiyuan', 32], ['shijiu', 32]]],
        ],
        'caoxi' => [
            [10, 6, 'down', 5, '樵夫', [['fuchong', 25], ['jiyu', 25], ['zhu', 31], ['dae', 31]]],
        ],
        'yigao' => [
            [10, 6, 'down', 3, '采药人', [['zheng', 30], ['bifang', 30], ['aoye', 30]]],
        ],
        'geshanwei' => [
            [10, 6, 'down', 2, '猎户', [['zhuhuai', 29, 'baigao'], ['zhujian', 29, 'baigao'], ['ranyi', 29, 'baigao']]],
        ],
        'geshanshou' => [
            [10, 6, 'down', 2, '巫祝弟子', [['shijiu', 23, 'baigao'], ['she', 23, 'baigao'], ['luomu', 23, 'baigao'], ['chigui', 23, 'baigao'], ['shanyu', 23, 'baigao'], ['huyu', 23, 'baigao']]],
        ],
        'yue' => [
            [10, 6, 'down', 3, '行商', [['min', 34]]],
        ],
        'dufu' => [
            [10, 6, 'down', 4, '采玉人', [['ling', 32], ['xiao', 32]]],
        ],
        'gengshan' => [
            [10, 6, 'down', 4, '采药人', [['zuzi', 27], ['guxi', 29], ['zheng', 31], ['shiyu', 29]]],
        ],
        'luqi' => [
            [10, 6, 'down', 3, '猎户', [['fan', 29, 'baigao'], ['chigui', 29, 'baigao'], ['baiyou', 29, 'baigao']]],
        ],
        'gushe' => [
            [10, 6, 'down', 3, '樵夫', [['shuima', 29], ['yongyong', 35]]],
        ],
        'beigushe' => [
            [10, 6, 'down', 3, '渔人', [['huyu', 33, 'baigao']]],
        ],
        'nangushe' => [
            [10, 6, 'down', 4, '牧人', [['meng', 36]]],
        ],
        'bishan' => [
            [10, 6, 'down', 3, '巫祝弟子', [['min', 32], ['puyi', 36]]],
        ],
        'goushi' => [
            [10, 6, 'down', 3, '樵夫', [['ganyu', 27], ['kanyu', 27], ['xingxing', 31], ['shuyu', 31]]],
        ],
        'gufeng' => [
            [10, 6, 'down', 3, '牧人', [['tianma', 32], ['yayu', 34]]],
        ],
        'fuli' => [
            [10, 6, 'down', 4, '行商', [['meng', 29], ['mengbao', 31], ['lingling', 33]]],
        ],
        'yinshandong' => [
            [10, 6, 'down', 3, '渔人', [['fuchong', 27], ['jiyu', 27], ['shusi', 27], ['baiyuan', 35]]],
        ],
        'shihu' => [
            [7, 6, 'down', 3, '采药人', [['paoxiao', 33], ['gudiao', 31]]],
            [13, 8, 'up', 3, '猎户', [['yelu', 32], ['baidi', 32]]],
        ],
        'qishan' => [
            [7, 6, 'down', 5, '樵夫', [['baihu', 32], ['xiao', 29], ['dae', 29]]],
            [13, 8, 'up', 1, '采药人', [['li', 30], ['yuan', 34]]],
        ],
        'zhugou' => [
            [7, 6, 'down', 2, '行商', [['baihao', 31], ['zhuo', 31], ['mi', 31]]],
            [13, 8, 'up', 4, '樵夫', [['zaoyu', 34], ['huayu', 32]]],
        ],
        'zhongfu' => [
            [7, 6, 'down', 3, '樵夫', [['feiyishe', 35]]],
            [13, 8, 'up', 3, '牧人', [['yongyong', 33, 'baigao']]],
        ],
        'hushe' => [
            [7, 6, 'down', 3, '牧人', [['fan', 35]]],
            [13, 8, 'up', 3, '行商', [['yongyong', 30], ['luoyu', 34]]],
        ],
        'mengzi' => [
            [7, 6, 'down', 1, '猎户', [['congcong', 33], ['bibi', 33]]],
            [13, 8, 'up', 3, '牧人', [['zuoniu', 28], ['min', 31], ['jiao', 31]]],
        ],
        'qizhong' => [
            [7, 6, 'down', 2, '牧人', [['zhujian', 34]]],
            [13, 8, 'up', 1, '猎户', [['shijiu', 29], ['ziluo', 29], ['zhan', 32]]],
        ],
        'muyu' => [
            [7, 6, 'down', 1, '猎户', [['yingwu', 36]]],
            [13, 8, 'up', 3, '樵夫', [['zhuhuai', 37]]],
        ],
        'wugao' => [
            [7, 6, 'down', 1, '渔人', [['dongdong', 35], ['congcong', 35]]],
            [13, 8, 'up', 3, '行商', [['baiyuan', 32], ['biluo', 32], ['zhi', 35]]],
        ],
        'beihao' => [
            [7, 6, 'down', 2, '采玉人', [['zheng', 29], ['zhuhuai', 32], ['aoye', 29]]],
            [13, 8, 'up', 3, '渔人', [['zhu', 35]]],
        ],
        'maoshandong' => [
            [10, 6, 'down', 1, '牧人', [['shuima', 33], ['zhi', 33]]],
        ],
        'dongshi' => [
            [7, 6, 'down', 3, '巫祝弟子', [['ling', 32], ['jiyu', 32]]],
            [13, 8, 'up', 3, '采玉人', [['huoshi', 12], ['tuofei', 34]]],
        ],
        'nvzheng' => [
            [10, 6, 'down', 4, '采玉人', [['zuoniu', 34]]],
        ],
        'qinshan' => [
            [10, 6, 'down', 4, '渔人', [['huangniao', 31], ['qique', 33]]],
        ],
        'zitong' => [
            [7, 6, 'down', 5, '采药人', [['lu', 32], ['lei', 32]]],
            [13, 8, 'up', 3, '采玉人', [['boyi', 30], ['changfu', 30], ['chiru', 30]]],
        ],
        'shanshan' => [
            [10, 6, 'down', 3, '采玉人', [['li', 34]]],
        ],
        'taishandong' => [
            [10, 6, 'down', 3, '樵夫', [['huahuai', 37]]],
        ],
        'ganzao' => [
            [10, 6, 'down', 0, '渔人', [['youyou', 34]]],
        ],
        'lier' => [
            [10, 6, 'down', 3, '采药人', [['wenbei', 32], ['gudiao', 30]]],
        ],
        'quzhu' => [
            [10, 6, 'down', 3, '采玉人', [['tuanyu', 26], ['baidi', 28], ['jingwei', 30], ['liu', 28]]],
        ],
        'conglong' => [
            [10, 6, 'down', 0, '渔人', [['meng', 30], ['bibi', 34]]],
        ],
        'tuohu' => [
            [10, 6, 'down', 3, '巫祝弟子', [['meibei', 29], ['fuchong', 29], ['dae', 32]]],
        ],
        'jinxing' => [
            [10, 6, 'down', 3, '猎户', [['zheng', 32], ['bifang', 32]]],
        ],
        'taiwei' => [
            [10, 6, 'down', 3, '采玉人', [['fuchong', 34]]],
        ],
        'jianggu' => [
            [10, 6, 'down', 3, '牧人', [['xibian', 29], ['paoxiao', 29], ['hu', 32]]],
        ],
        'niushou' => [
            [10, 6, 'down', 4, '猎户', [['yelu', 28], ['luomu', 28], ['jishe', 28], ['hui', 28]]],
        ],
        'huoshan' => [
            [10, 6, 'down', 1, '采玉人', [['jingwei', 34]]],
        ],
        'hegu' => [
            [10, 6, 'down', 4, '牧人', [['xuangui', 31], ['chaishou', 31], ['bibi', 31]]],
        ],
        'yinshanzhong' => [
            [10, 6, 'down', 2, '巫祝弟子', [['guaishe', 38], ['zhuyan', 38]]],
        ],
        'aoan' => [
            [10, 6, 'down', 3, '行商', [['guaishe', 31]]],
        ],
        'qingyao' => [
            [10, 6, 'down', 1, '采玉人', [['baiye', 35], ['nafu', 36]]],
        ],
        'guishanzhong' => [
            [10, 6, 'down', 3, '牧人', [['haozhi', 39], ['xiao', 40], ['pi', 41]]],
        ],
        'yisu' => [
            [10, 6, 'down', 2, '巫祝弟子', [['dangkang', 44], ['feifei', 45]]],
        ],
        'heshan' => [
            [10, 6, 'down', 3, '行商', [['renmianxiao', 50]]],
        ],
        'luti' => [
            [10, 6, 'down', 3, '猎户', [['juji', 33]]],
        ],
        'fuzhushan' => [
            [10, 6, 'down', 1, '樵夫', [['huayu', 34], ['bailu', 35]]],
        ],
        'lishanzhong' => [
            [10, 6, 'down', 3, '采药人', [['nafu', 37], ['zaoyu', 38], ['yiyu', 39]]],
        ],
        'jiweizhong' => [
            [10, 6, 'down', 2, '行商', [['youyan', 39], ['nafu', 40]]],
        ],
        'bingshan' => [
            [10, 6, 'down', 3, '猎户', [['feiyutun', 43]]],
        ],
        'baibian' => [
            [10, 6, 'down', 4, '樵夫', [['zuzi', 44], ['changshe', 45]]],
        ],
        'xionger' => [
            [10, 6, 'down', 3, '采药人', [['xiao', 47], ['tuofei', 48], ['xibian', 49]]],
        ],
        'mushan' => [
            [10, 6, 'down', 1, '行商', [['congcong', 49], ['chaishou', 50]]],
        ],
        'huanju' => [
            [10, 6, 'down', 2, '猎户', [['tianma', 53]]],
        ],
        'goulin' => [
            [10, 6, 'down', 3, '采玉人', [['shiyu', 35]]],
        ],
        'shoushan' => [
            [10, 6, 'down', 1, '渔人', [['huangniao', 36], ['shiyu', 37]]],
        ],
        'xianzhu' => [
            [10, 6, 'down', 3, '巫祝弟子', [['conglong', 37], ['bangyu', 38], ['mengbao', 39]]],
        ],
        'conglongwu' => [
            [10, 6, 'down', 2, '猎户', [['yaoniao', 39], ['yinmo', 40]]],
        ],
        'tiaogu' => [
            [10, 6, 'down', 3, '采玉人', [['xiang', 41]]],
        ],
        'chaoshan' => [
            [10, 6, 'down', 4, '渔人', [['chi', 42, 'baigao'], ['bailu', 43, 'baigao']]],
        ],
        'chenghou' => [
            [10, 6, 'down', 3, '巫祝弟子', [['haozhi', 44], ['xiao', 45], ['xiong', 46]]],
        ],
        'zhaoge' => [
            [10, 6, 'down', 1, '猎户', [['manman', 45], ['baihu', 46]]],
        ],
        'huaishan' => [
            [10, 6, 'down', 2, '采玉人', [['lihu', 48]]],
        ],
        'lishanwu' => [
            [10, 6, 'down', 3, '渔人', [['feiyutun', 49], ['dainiao', 50]]],
        ],
        'shishanwu' => [
            [10, 6, 'down', 3, '巫祝弟子', [['huanshou', 50], ['qitu', 51], ['shenchi', 52]]],
        ],
        'liangyu' => [
            [10, 6, 'down', 1, '猎户', [['xiangshe', 52], ['suanyu', 53]]],
        ],
        'guwei' => [
            [10, 6, 'down', 3, '采玉人', [['jueru', 54]]],
        ],
        'shengshan' => [
            [10, 6, 'down', 2, '渔人', [['luoluo', 55], ['junniao', 56]]],
        ],
        'yanghua' => [
            [10, 6, 'down', 3, '牧人', [['baihao', 38]]],
        ],
        'kuafu' => [
            [10, 6, 'down', 1, '巫祝弟子', [['quru', 38], ['zuoniu', 39]]],
        ],
        'changzheng' => [
            [10, 6, 'down', 3, '采玉人', [['huayub', 40], ['yuniao', 41], ['ershu', 42]]],
        ],
        'tuoshan' => [
            [10, 6, 'down', 2, '渔人', [['chunniao', 42], ['huayu', 43]]],
        ],
        'fushanliu' => [
            [10, 6, 'down', 3, '牧人', [['ciyu', 45]]],
        ],
        'changshi' => [
            [10, 6, 'down', 4, '巫祝弟子', [['xigui', 46], ['ciyu', 47]]],
        ],
        'mishanliu' => [
            [10, 6, 'down', 3, '采玉人', [['xie', 48], ['dainiao', 49], ['zhu', 50]]],
        ],
        'gushan' => [
            [10, 6, 'down', 1, '渔人', [['shuima', 50], ['yongyong', 51]]],
        ],
        'baishi' => [
            [10, 6, 'down', 2, '牧人', [['lihu', 52]]],
        ],
        'louzhuo' => [
            [10, 6, 'down', 3, '巫祝弟子', [['yao', 53], ['banmao', 54]]],
        ],
        'zhanzhu' => [
            [10, 6, 'down', 3, '采玉人', [['fuzhu', 55], ['lingyao', 56], ['lushu', 57]]],
        ],
        'guishanliu' => [
            [10, 6, 'down', 1, '渔人', [['luoyu', 57, 'baigao'], ['saoyu', 58, 'baigao']]],
        ],
        'gaodi' => [
            [10, 6, 'down', 3, '牧人', [['renmianxiao', 60]]],
        ],
        'xiuyushan' => [
            [10, 6, 'down', 3, '采药人', [['danghu', 40]]],
        ],
        'guzhong' => [
            [10, 6, 'down', 1, '行商', [['xianfu', 40], ['guxi', 41]]],
        ],
        'guyao' => [
            [10, 6, 'down', 3, '樵夫', [['shusi', 41], ['yingwu', 42], ['maoniu', 43]]],
        ],
        'kushan' => [
            [10, 6, 'down', 2, '牧人', [['huayub', 43], ['tuotuo', 44]]],
        ],
        'dushanqi' => [
            [10, 6, 'down', 3, '采药人', [['lyu', 45]]],
        ],
        'fanggao' => [
            [10, 6, 'down', 4, '行商', [['ma', 45], ['lyu', 46]]],
        ],
        'dakushan' => [
            [10, 6, 'down', 3, '樵夫', [['hu', 47], ['bao', 48], ['yingzhan', 49]]],
        ],
        'banshi' => [
            [10, 6, 'down', 1, '牧人', [['yaoniao', 48], ['xie', 49]]],
        ],
        'shaoshi' => [
            [10, 6, 'down', 2, '采药人', [['yingzhan', 51]]],
        ],
        'taishi' => [
            [10, 6, 'down', 3, '行商', [['tongtong', 51], ['zhubie', 52]]],
        ],
        'jiangshan' => [
            [10, 6, 'down', 3, '樵夫', [['xie', 52], ['dainiao', 53], ['sanzugui', 54]]],
        ],
        'yingliang' => [
            [10, 6, 'down', 1, '牧人', [['xie', 54], ['dainiao', 55]]],
        ],
        'fuxi' => [
            [10, 6, 'down', 3, '采药人', [['luoyu', 56]]],
        ],
        'shaoxing' => [
            [10, 6, 'down', 2, '行商', [['zhuru', 57], ['lihu', 58]]],
        ],
        'taishanqi' => [
            [10, 6, 'down', 3, '樵夫', [['huanshou', 58], ['qitu', 59], ['shenchi', 60]]],
        ],
        'moshan' => [
            [10, 6, 'down', 4, '牧人', [['yao', 59], ['boma', 60]]],
        ],
        'yishanqi' => [
            [10, 6, 'down', 3, '采药人', [['dongdong', 62]]],
        ],
        'minshanqi' => [
            [10, 6, 'down', 1, '行商', [['chaishou', 62, 'baigao'], ['tiaoyong', 63, 'baigao']]],
        ],
        'jingshanba' => [
            [10, 6, 'down', 3, '猎户', [['yinmo', 42]]],
        ],
        'jingshanjing' => [
            [10, 6, 'down', 1, '樵夫', [['zhenniao', 42], ['zhulu', 43]]],
        ],
        'jiaoshanba' => [
            [10, 6, 'down', 3, '采药人', [['yuniao', 43], ['ershu', 44], ['baiye', 45]]],
        ],
        'nvji' => [
            [10, 6, 'down', 2, '行商', [['shiyu', 44], ['pijiu', 45]]],
        ],
        'yizhu' => [
            [10, 6, 'down', 3, '猎户', [['huangniao', 46]]],
        ],
        'lunshanba' => [
            [10, 6, 'down', 4, '樵夫', [['na', 46], ['feiyu', 47]]],
        ],
        'lugui' => [
            [10, 6, 'down', 3, '采药人', [['huangniao', 48], ['pijiu', 49], ['jiegou', 50]]],
        ],
        'guangshan' => [
            [10, 6, 'down', 1, '行商', [['bailu', 49], ['bailang', 50]]],
        ],
        'qishanba' => [
            [10, 6, 'down', 2, '猎户', [['mengbao', 51]]],
        ],
        'tongshan' => [
            [10, 6, 'down', 3, '樵夫', [['ciyu', 51], ['yaoniao', 52]]],
        ],
        'meishan' => [
            [10, 6, 'down', 3, '采药人', [['ma', 52], ['yongyong', 53], ['zishu', 54]]],
        ],
        'dayao' => [
            [10, 6, 'down', 1, '行商', [['tongtong', 54], ['zhubie', 55]]],
        ],
        'lingshan' => [
            [10, 6, 'down', 3, '猎户', [['wanhu', 56]]],
        ],
        'longshan' => [
            [10, 6, 'down', 2, '樵夫', [['tuanyu', 56], ['yong', 57]]],
        ],
        'hengshan' => [
            [10, 6, 'down', 3, '采药人', [['youyou', 57], ['wanhu', 58], ['ciyu', 59]]],
        ],
        'shishanba' => [
            [10, 6, 'down', 4, '行商', [['xibian', 58], ['minniu', 59]]],
        ],
        'ruoshan' => [
            [10, 6, 'down', 3, '猎户', [['huanshou', 60]]],
        ],
        'zhishan' => [
            [10, 6, 'down', 1, '樵夫', [['lushu', 61], ['xuangui', 62]]],
        ],
        'yushanba' => [
            [10, 6, 'down', 2, '采药人', [['lingling', 62], ['zhuru', 63], ['youyou', 64]]],
        ],
        'huanshanba' => [
            [10, 6, 'down', 3, '行商', [['qinyuan', 63, 'baigao'], ['jiao', 64, 'baigao']]],
        ],
        'renju' => [
            [10, 6, 'down', 3, '猎户', [['renmianxiao', 65]]],
        ],
        'shimei' => [
            [10, 6, 'down', 1, '樵夫', [['shanhui', 65], ['zhuhuai', 66]]],
        ],
        'nvjijiu' => [
            [10, 6, 'down', 3, '渔人', [['li', 44]]],
        ],
        'minshan' => [
            [10, 6, 'down', 1, '采药人', [['baihao', 44], ['zhuo', 45]]],
        ],
        'laishanjiu' => [
            [10, 6, 'down', 3, '猎户', [['xiong', 46], ['shusi', 47], ['yingwu', 48]]],
        ],
        'jushan' => [
            [10, 6, 'down', 2, '樵夫', [['chibi', 48], ['conglong', 49]]],
        ],
        'gaoliang' => [
            [10, 6, 'down', 3, '渔人', [['yaoniao', 51]]],
        ],
        'sheshan' => [
            [10, 6, 'down', 4, '采药人', [['nafu', 52], ['yiyu', 53]]],
        ],
        'lishanjiu' => [
            [10, 6, 'down', 3, '猎户', [['xie', 54], ['sanzugui', 55], ['jiaoyu', 56]]],
        ],
        'yuyang' => [
            [10, 6, 'down', 1, '樵夫', [['baihu', 56], ['shuima', 57]]],
        ],
        'qishanjiu' => [
            [10, 6, 'down', 2, '渔人', [['sanzugui', 58]]],
        ],
        'goumi' => [
            [10, 6, 'down', 3, '采药人', [['rupi', 59], ['shuyu', 60]]],
        ],
        'fengyu' => [
            [10, 6, 'down', 3, '猎户', [['boyu', 61], ['feifei', 62], ['feiyutun', 63]]],
        ],
        'yushanjiu' => [
            [10, 6, 'down', 1, '樵夫', [['sanqingniao', 63], ['huanshou', 64]]],
        ],
        'xiongshan' => [
            [10, 6, 'down', 3, '渔人', [['yilang', 66]]],
        ],
        'guishanjiu' => [
            [10, 6, 'down', 2, '采药人', [['changshe', 67], ['shanhui', 68]]],
        ],
        'geshan' => [
            [10, 6, 'down', 3, '猎户', [['xiaoque', 69], ['hui', 70], ['tianma', 71]]],
        ],
        'shouyang' => [
            [10, 6, 'down', 3, '采玉人', [['haoyu', 46]]],
        ],
        'huwei' => [
            [10, 6, 'down', 1, '渔人', [['conglong', 48], ['bangyu', 49]]],
        ],
        'fanhui' => [
            [10, 6, 'down', 3, '巫祝弟子', [['ershu', 52, 'baigao'], ['youyan', 53, 'baigao'], ['baiye', 54, 'baigao']]],
        ],
        'yongshi' => [
            [10, 6, 'down', 2, '猎户', [['pijiu', 55], ['yongyong', 56]]],
        ],
        'fuzhou' => [
            [10, 6, 'down', 3, '采玉人', [['xibian', 60]]],
        ],
        'chushan' => [
            [10, 6, 'down', 4, '渔人', [['congcong', 62], ['zishu', 63]]],
        ],
        'youyuan' => [
            [10, 6, 'down', 3, '巫祝弟子', [['xuangui', 66], ['guanguan', 67], ['lili', 68]]],
        ],
        'zhuoshan' => [
            [10, 6, 'down', 1, '猎户', [['heluo', 69], ['menghuai', 70]]],
        ],
        'bingshanshi' => [
            [10, 6, 'down', 2, '采玉人', [['dashe', 74]]],
        ],
        'yiwangzhong' => [
            [10, 6, 'down', 3, '樵夫', [['zuoniu', 48]]],
        ],
        'zhaogesy' => [
            [10, 6, 'down', 1, '牧人', [['tengyu', 48], ['zhenniao', 49]]],
        ],
        'diqun' => [
            [10, 6, 'down', 3, '行商', [['zhulu', 49], ['lianggui', 50], ['weihou', 51]]],
        ],
        'qianshansy' => [
            [10, 6, 'down', 2, '采玉人', [['guxi', 50], ['huangniao', 51]]],
        ],
        'fengshan' => [
            [10, 6, 'down', 3, '樵夫', [['shiyu', 51]]],
        ],
        'tuchuang' => [
            [10, 6, 'down', 4, '牧人', [['shiyu', 51], ['pijiu', 52]]],
        ],
        'yaobi' => [
            [10, 6, 'down', 3, '行商', [['gui', 52], ['huayub', 53], ['tuotuo', 54]]],
        ],
        'gongli' => [
            [10, 6, 'down', 1, '采玉人', [['yuniao', 53], ['ershu', 54]]],
        ],
        'zhidiao' => [
            [10, 6, 'down', 2, '樵夫', [['mengbao', 55]]],
        ],
        'yigu' => [
            [10, 6, 'down', 3, '牧人', [['xiong', 55], ['pi', 56]]],
        ],
        'jigu' => [
            [10, 6, 'down', 3, '行商', [['yiyu', 55], ['ma', 56], ['lyu', 57]]],
        ],
        'gaoqian' => [
            [10, 6, 'down', 1, '采玉人', [['xianfu', 56], ['huangniao', 57]]],
        ],
        'youxi' => [
            [10, 6, 'down', 3, '樵夫', [['zhu', 58, 'baigao']]],
        ],
        'congshan' => [
            [10, 6, 'down', 2, '牧人', [['jiaolong', 58], ['xuanbao', 59]]],
        ],
        'bishansy' => [
            [10, 6, 'down', 3, '行商', [['xigui', 59], ['ciyu', 60], ['yaoniao', 61]]],
        ],
        'lema' => [
            [10, 6, 'down', 4, '采玉人', [['bailang', 60], ['baihu', 61]]],
        ],
        'zhenshan' => [
            [10, 6, 'down', 3, '樵夫', [['shuima', 62]]],
        ],
        'hushou' => [
            [10, 6, 'down', 1, '牧人', [['yingzhan', 61], ['chunniao', 62]]],
        ],
        'yinghou' => [
            [10, 6, 'down', 2, '行商', [['manman', 62], ['baihu', 63], ['rupi', 64]]],
        ],
        'beishansy' => [
            [10, 6, 'down', 3, '采玉人', [['minniu', 63], ['fuxi', 64]]],
        ],
        'yidi' => [
            [10, 6, 'down', 3, '樵夫', [['sanzubie', 65]]],
        ],
        'nishan' => [
            [10, 6, 'down', 1, '牧人', [['juru', 65], ['zhitu', 66]]],
        ],
        'xuanshan' => [
            [10, 6, 'down', 3, '行商', [['feifei', 66], ['feiyutun', 67], ['xie', 68]]],
        ],
        'hengshansy' => [
            [10, 6, 'down', 2, '采玉人', [['fuxi', 66], ['luoluo', 67]]],
        ],
        'fengshaner' => [
            [10, 6, 'down', 3, '樵夫', [['jufu', 68]]],
        ],
        'xianshansy' => [
            [10, 6, 'down', 4, '牧人', [['zhi', 68], ['gudiao', 69]]],
        ],
        'gaoshansy' => [
            [10, 6, 'down', 3, '行商', [['tuanyu', 69], ['renyu', 70], ['xiao', 71]]],
        ],
        'ouwu' => [
            [10, 6, 'down', 1, '采玉人', [['lingling', 70], ['zhuru', 71]]],
        ],
        'shengxiong' => [
            [10, 6, 'down', 2, '樵夫', [['youyou', 72]]],
        ],
        'daguisy' => [
            [10, 6, 'down', 3, '牧人', [['qiyu', 72], ['shuyu', 73]]],
        ],
        'lishi' => [
            [10, 6, 'down', 3, '行商', [['huayudong', 72], ['mingshe', 73], ['longchi', 74]]],
        ],
        'qiushan' => [
            [10, 6, 'down', 1, '采玉人', [['fuzhu', 73], ['lingyao', 74]]],
        ],
        'chouyang' => [
            [10, 6, 'down', 3, '樵夫', [['tiyu', 75, 'baigao']]],
        ],
        'fushansy' => [
            [10, 6, 'down', 2, '牧人', [['qiezhi', 75], ['yilang', 76]]],
        ],
        'yaoshan' => [
            [10, 6, 'down', 3, '行商', [['qizhongniao', 76], ['yonghe', 77], ['linquan', 78]]],
        ],
        'pianyu' => [
            [10, 6, 'down', 3, '猎户', [['gui', 50]]],
        ],
        'yunshan' => [
            [10, 6, 'down', 1, '樵夫', [['jiegou', 51], ['zhan', 52]]],
        ],
        'guishanse' => [
            [10, 6, 'down', 3, '采药人', [['songsi', 54], ['beibei', 55], ['zaoyu', 56]]],
        ],
        'bingshanse' => [
            [10, 6, 'down', 2, '行商', [['ma', 56], ['lyu', 57]]],
        ],
        'fengbo' => [
            [10, 6, 'down', 3, '猎户', [['xigui', 59]]],
        ],
        'fufu' => [
            [10, 6, 'down', 4, '樵夫', [['yongyong', 61], ['zhubie', 62]]],
        ],
        'dongting' => [
            [10, 6, 'down', 3, '采药人', [['bailang', 63], ['baihu', 64], ['shuima', 65]]],
        ],
        'baoshan' => [
            [10, 6, 'down', 1, '行商', [['feifei', 65], ['feiyutun', 66]]],
        ],
        'jigong' => [
            [10, 6, 'down', 2, '猎户', [['yong', 68]]],
        ],
        'yaoshanyao' => [
            [10, 6, 'down', 3, '樵夫', [['lushu', 70], ['xuangui', 71]]],
        ],
        'jiangfu' => [
            [10, 6, 'down', 3, '采药人', [['linghu', 72], ['xiangshe', 73], ['dongdong', 74]]],
        ],
        'zhenling' => [
            [10, 6, 'down', 1, '行商', [['youyou', 74], ['gege', 75]]],
        ],
        'yangdi' => [
            [10, 6, 'down', 3, '猎户', [['luoluo', 78]]],
        ],
        'chaisang' => [
            [10, 6, 'down', 2, '樵夫', [['shengyu', 79], ['zheng', 80]]],
        ],
    ],
];
