<?php
// The moves each beast learns, and at what level: [level, move of data/moves.php], in the order learned.
// A beast knows the last four it has learned. Ours; a beast's moves are those the classic's words about it suggest,
// then those of its elements. Most also learn one move of another element, by their shape or their mountain's waters
// (a bird's beak, a horse's dust, the river that rises on the hill): what lets the worse side of a matchup answer.
declare(strict_types=1);

return [
    'xingxing' => [[1, 'ram'], [1, 'breeze'], [6, 'swift'], [11, 'feathers'], [16, 'grapple'], [22, 'cassia'], [26, 'splash'], [29, 'selfcall'], [37, 'gale']],
    'baiyuan' => [[1, 'ram'], [1, 'breeze'], [8, 'grapple'], [14, 'feathers'], [21, 'cassia'], [30, 'gale']],
    'fuchong' => [[1, 'ram'], [1, 'pebble'], [7, 'sting'], [13, 'bite'], [19, 'dust'], [22, 'fang'], [26, 'burrow'], [34, 'venom']],
    'lushu' => [[1, 'ram'], [1, 'spark'], [6, 'chant'], [11, 'redtail'], [16, 'stripes'], [21, 'flame'], [24, 'dust'], [27, 'drywind'], [34, 'blaze'], [42, 'wildfire'], [50, 'scorch']],
    'xuangui' => [[1, 'ram'], [1, 'splash'], [6, 'woodsplit'], [11, 'foam'], [16, 'undeaf'], [21, 'surge'], [24, 'peck'], [27, 'whirl'], [34, 'darkshell'], [42, 'billow'], [50, 'deluge']],
    'lu' => [[1, 'ram'], [1, 'pebble'], [7, 'lowing'], [13, 'dust'], [19, 'revive'], [22, 'gore'], [25, 'hillcharge'], [32, 'trample'], [40, 'cliffbreak']],
    'lei' => [[1, 'ram'], [1, 'spark'], [8, 'tailwhip'], [14, 'redtail'], [20, 'bite'], [23, 'claw'], [26, 'flame'], [33, 'drywind'], [41, 'blaze']],
    'boyi' => [[1, 'ram'], [1, 'spark'], [7, 'fearless'], [13, 'backeyes'], [19, 'flame'], [23, 'hillcharge'], [26, 'tailwhip'], [33, 'drywind'], [41, 'blaze']],
    'changfu' => [[1, 'ram'], [1, 'breeze'], [7, 'sleepless'], [13, 'feathers'], [19, 'cassia'], [22, 'peck'], [25, 'selfcall'], [32, 'gale'], [40, 'southwind']],
    'jiuweihu' => [[1, 'bite'], [1, 'spark'], [1, 'infantcry'], [10, 'venom'], [18, 'flame'], [26, 'devour'], [34, 'ninetails'], [42, 'blaze'], [50, 'scorch']],
    'guanguan' => [[1, 'ram'], [1, 'breeze'], [6, 'bewilder'], [11, 'feathers'], [16, 'rebuke'], [21, 'cassia'], [24, 'surge'], [27, 'selfcall'], [34, 'gale'], [42, 'southwind'], [50, 'tempest']],
    'chiru' => [[1, 'ram'], [1, 'splash'], [8, 'foam'], [14, 'tailwhip'], [20, 'surge'], [27, 'whirl'], [35, 'billow']],
    'lili' => [[1, 'ram'], [1, 'pebble'], [7, 'spur'], [13, 'dust'], [19, 'burrow'], [26, 'bite'], [33, 'rockfall'], [41, 'landslide']],
    'zhu' => [[1, 'ram'], [1, 'breeze'], [7, 'bewilder'], [13, 'feathers'], [19, 'cassia'], [22, 'surge'], [25, 'selfcall'], [32, 'gale'], [40, 'southwind'], [48, 'tempest']],
    'changyou' => [[1, 'ram'], [1, 'splash'], [8, 'grapple'], [14, 'foam'], [20, 'surge'], [26, 'flood'], [33, 'billow'], [41, 'seatide'], [49, 'deluge']],
    'huahuai' => [[1, 'ram'], [1, 'pebble'], [8, 'slumber'], [14, 'burrow'], [20, 'grapple'], [24, 'fang'], [27, 'hillcharge'], [34, 'rockfall'], [42, 'landslide']],
    'zhi' => [[1, 'bite'], [1, 'claw'], [8, 'tailwhip'], [14, 'fang'], [20, 'devour'], [24, 'rockfall'], [27, 'gore'], [35, 'pounce'], [43, 'onehorn']],
    'jiyu' => [[1, 'ram'], [1, 'splash'], [9, 'foam'], [17, 'surge'], [26, 'whirl']],
    'huan' => [[1, 'ram'], [1, 'spark'], [8, 'fearless'], [15, 'flame'], [22, 'revive'], [26, 'hillcharge'], [30, 'drywind'], [38, 'blaze']],
    'biluo' => [[1, 'ram'], [1, 'splash'], [8, 'fearless'], [16, 'foam'], [25, 'surge']],
    'gudiao' => [[1, 'ram'], [1, 'splash'], [7, 'infantcry'], [13, 'peck'], [19, 'surge'], [25, 'gore'], [32, 'devour'], [40, 'billow'], [48, 'onehorn']],
    'xi' => [[1, 'ram'], [1, 'claw'], [9, 'fearless'], [15, 'spur'], [22, 'gore'], [26, 'hillcharge'], [30, 'trample'], [39, 'onehorn']],
    'si' => [[1, 'ram'], [1, 'claw'], [9, 'tailwhip'], [15, 'spur'], [22, 'gore'], [26, 'hillcharge'], [30, 'trample'], [38, 'onehorn']],
    'xiang' => [[1, 'ram'], [1, 'claw'], [10, 'fearless'], [18, 'trample'], [22, 'hillcharge'], [26, 'gore'], [35, 'pounce'], [44, 'tusk']],
    'quru' => [[1, 'ram'], [1, 'breeze'], [7, 'peck'], [13, 'feathers'], [19, 'selfcall'], [23, 'surge'], [26, 'cassia'], [33, 'gale'], [41, 'southwind']],
    'hujiao' => [[1, 'bite'], [1, 'splash'], [8, 'tailwhip'], [14, 'foam'], [20, 'surge'], [24, 'fang'], [27, 'whirl'], [34, 'billow'], [42, 'seatide'], [50, 'deluge']],
    'fenghuang' => [[1, 'breeze'], [1, 'dance'], [1, 'cassia'], [15, 'selfcall'], [20, 'flame'], [25, 'gale'], [35, 'fivehues'], [45, 'southwind'], [55, 'tempest']],
    'tuanyu' => [[1, 'ram'], [1, 'spark'], [7, 'splash'], [13, 'redtail'], [19, 'flame'], [25, 'drought'], [32, 'surge'], [40, 'blaze'], [48, 'scorch']],
    'yong' => [[1, 'ram'], [1, 'spark'], [7, 'breeze'], [13, 'selfcall'], [19, 'flame'], [25, 'drought'], [32, 'gale'], [40, 'blaze'], [48, 'scorch']],
    'yuanchu' => [[1, 'breeze'], [1, 'feathers'], [10, 'bewilder'], [18, 'cassia'], [22, 'flame'], [26, 'selfcall'], [34, 'gale'], [42, 'southwind'], [50, 'tempest']],
];
