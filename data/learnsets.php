<?php
// The moves each beast learns, and at what level: [level, move of data/moves.php], in the order learned.
// A beast knows the last four it has learned. Ours; a beast's moves are those the classic's words about it suggest,
// then those of its elements. Most also learn one move of another element, by their shape or their mountain's waters
// (a bird's beak, a horse's dust, the river that rises on the hill): what lets the worse side of a matchup answer.
// Moves are shared: one the classic's words give to one beast is learned by others it suits, and about four in ten
// of what a beast learns does no damage, at every stage of its growth, as the reference's learnsets measure
// (docs/numbers.md 10.5).
declare(strict_types=1);

return [
    'xingxing' => [[1, 'ram'], [1, 'breeze'], [6, 'swift'], [11, 'feathers'], [13, 'chant'], [16, 'grapple'], [22, 'cassia'], [24, 'backeyes'], [26, 'splash'], [29, 'selfcall'], [37, 'gale']],
    'baiyuan' => [[1, 'ram'], [1, 'breeze'], [5, 'chant'], [8, 'grapple'], [14, 'feathers'], [18, 'swift'], [21, 'cassia'], [26, 'backeyes'], [30, 'gale']],
    'fuchong' => [[1, 'ram'], [1, 'pebble'], [7, 'sting'], [13, 'bite'], [16, 'slumber'], [19, 'dust'], [22, 'fang'], [26, 'burrow'], [34, 'venom']],
    'lushu' => [[1, 'ram'], [1, 'spark'], [6, 'chant'], [11, 'redtail'], [16, 'stripes'], [21, 'flame'], [24, 'dust'], [27, 'drywind'], [30, 'swift'], [34, 'blaze'], [42, 'wildfire'], [46, 'sleepless'], [50, 'scorch']],
    'xuangui' => [[1, 'ram'], [1, 'splash'], [6, 'woodsplit'], [11, 'foam'], [16, 'undeaf'], [21, 'surge'], [24, 'peck'], [27, 'whirl'], [30, 'slumber'], [34, 'darkshell'], [38, 'revive'], [42, 'billow'], [46, 'flood'], [50, 'deluge']],
    'lu' => [[1, 'ram'], [1, 'pebble'], [7, 'lowing'], [13, 'dust'], [16, 'slumber'], [19, 'revive'], [22, 'gore'], [25, 'hillcharge'], [30, 'sting'], [32, 'trample'], [36, 'fearless'], [40, 'cliffbreak']],
    'lei' => [[1, 'ram'], [1, 'spark'], [5, 'infantcry'], [8, 'tailwhip'], [14, 'redtail'], [17, 'lowing'], [20, 'bite'], [23, 'claw'], [26, 'flame'], [30, 'sleepless'], [33, 'drywind'], [37, 'swift'], [41, 'blaze']],
    'boyi' => [[1, 'ram'], [1, 'spark'], [7, 'fearless'], [13, 'backeyes'], [16, 'chant'], [19, 'flame'], [23, 'hillcharge'], [26, 'tailwhip'], [30, 'undeaf'], [33, 'drywind'], [37, 'sleepless'], [41, 'blaze']],
    'changfu' => [[1, 'ram'], [1, 'breeze'], [7, 'sleepless'], [10, 'rebuke'], [13, 'feathers'], [16, 'bewilder'], [19, 'cassia'], [22, 'peck'], [25, 'selfcall'], [29, 'swift'], [32, 'gale'], [36, 'undeaf'], [40, 'southwind']],
    'jiuweihu' => [[1, 'bite'], [1, 'spark'], [1, 'infantcry'], [10, 'venom'], [14, 'bewilder'], [18, 'flame'], [22, 'sleepless'], [26, 'devour'], [30, 'swift'], [34, 'ninetails'], [42, 'blaze'], [46, 'undeaf'], [50, 'scorch']],
    'guanguan' => [[1, 'ram'], [1, 'breeze'], [6, 'bewilder'], [11, 'feathers'], [16, 'rebuke'], [21, 'cassia'], [24, 'surge'], [27, 'selfcall'], [30, 'backeyes'], [34, 'gale'], [38, 'sleepless'], [42, 'southwind'], [46, 'duststorm'], [50, 'tempest']],
    'chiru' => [[1, 'ram'], [1, 'splash'], [5, 'chant'], [8, 'foam'], [11, 'woodsplit'], [14, 'tailwhip'], [17, 'lowing'], [20, 'surge'], [24, 'undeaf'], [27, 'whirl'], [31, 'sleepless'], [35, 'billow']],
    'lili' => [[1, 'ram'], [1, 'pebble'], [7, 'spur'], [10, 'rebuke'], [13, 'dust'], [16, 'fearless'], [19, 'burrow'], [23, 'stripes'], [26, 'bite'], [33, 'rockfall'], [37, 'swift'], [41, 'landslide']],
    'zhu' => [[1, 'ram'], [1, 'breeze'], [7, 'bewilder'], [10, 'rebuke'], [13, 'feathers'], [16, 'woodsplit'], [19, 'cassia'], [22, 'surge'], [25, 'selfcall'], [29, 'swift'], [32, 'gale'], [36, 'duststorm'], [40, 'southwind'], [44, 'sleepless'], [48, 'tempest']],
    'changyou' => [[1, 'ram'], [1, 'splash'], [5, 'chant'], [8, 'grapple'], [11, 'lowing'], [14, 'foam'], [17, 'woodsplit'], [20, 'surge'], [26, 'flood'], [30, 'undeaf'], [33, 'billow'], [37, 'sleepless'], [41, 'seatide'], [49, 'deluge']],
    'huahuai' => [[1, 'ram'], [1, 'pebble'], [8, 'slumber'], [11, 'fearless'], [14, 'burrow'], [17, 'rebuke'], [20, 'grapple'], [24, 'fang'], [27, 'hillcharge'], [30, 'stripes'], [34, 'rockfall'], [42, 'landslide']],
    'zhi' => [[1, 'bite'], [1, 'claw'], [5, 'rebuke'], [8, 'tailwhip'], [11, 'stripes'], [14, 'fang'], [17, 'infantcry'], [20, 'devour'], [24, 'rockfall'], [27, 'gore'], [31, 'swift'], [35, 'pounce'], [43, 'onehorn']],
    'jiyu' => [[1, 'ram'], [1, 'splash'], [5, 'lowing'], [9, 'foam'], [13, 'swift'], [17, 'surge'], [21, 'undeaf'], [26, 'whirl']],
    'huan' => [[1, 'ram'], [1, 'spark'], [8, 'fearless'], [12, 'undeaf'], [15, 'flame'], [19, 'lowing'], [22, 'revive'], [26, 'hillcharge'], [30, 'drywind'], [34, 'chant'], [38, 'blaze']],
    'biluo' => [[1, 'ram'], [1, 'splash'], [8, 'fearless'], [12, 'undeaf'], [16, 'foam'], [20, 'revive'], [25, 'surge'], [29, 'slumber']],
    'gudiao' => [[1, 'ram'], [1, 'splash'], [7, 'infantcry'], [10, 'rebuke'], [13, 'peck'], [16, 'sleepless'], [19, 'surge'], [22, 'venom'], [25, 'gore'], [32, 'devour'], [36, 'swift'], [40, 'billow'], [48, 'onehorn']],
    'xi' => [[1, 'ram'], [1, 'claw'], [5, 'lowing'], [9, 'fearless'], [12, 'stripes'], [15, 'spur'], [19, 'undeaf'], [22, 'gore'], [26, 'hillcharge'], [30, 'trample'], [34, 'rebuke'], [39, 'onehorn']],
    'si' => [[1, 'ram'], [1, 'claw'], [5, 'lowing'], [9, 'tailwhip'], [12, 'stripes'], [15, 'spur'], [19, 'fearless'], [22, 'gore'], [26, 'hillcharge'], [30, 'trample'], [34, 'rebuke'], [38, 'onehorn']],
    'xiang' => [[1, 'ram'], [1, 'claw'], [5, 'lowing'], [10, 'fearless'], [14, 'stripes'], [18, 'trample'], [22, 'hillcharge'], [26, 'gore'], [30, 'undeaf'], [35, 'pounce'], [40, 'revive'], [44, 'tusk']],
    'quru' => [[1, 'ram'], [1, 'breeze'], [4, 'chant'], [7, 'peck'], [10, 'bewilder'], [13, 'feathers'], [16, 'swift'], [19, 'selfcall'], [23, 'surge'], [26, 'cassia'], [29, 'sleepless'], [33, 'gale'], [41, 'southwind']],
    'hujiao' => [[1, 'bite'], [1, 'splash'], [5, 'chant'], [8, 'tailwhip'], [11, 'sting'], [14, 'foam'], [17, 'sleepless'], [20, 'surge'], [24, 'fang'], [27, 'whirl'], [30, 'swift'], [34, 'billow'], [38, 'undeaf'], [42, 'seatide'], [50, 'deluge']],
    'fenghuang' => [[1, 'breeze'], [1, 'dance'], [1, 'cassia'], [8, 'chant'], [15, 'selfcall'], [20, 'flame'], [25, 'gale'], [30, 'revive'], [35, 'fivehues'], [40, 'sleepless'], [45, 'southwind'], [50, 'undeaf'], [55, 'tempest']],
    'tuanyu' => [[1, 'ram'], [1, 'spark'], [4, 'lowing'], [7, 'splash'], [10, 'woodsplit'], [13, 'redtail'], [16, 'chant'], [19, 'flame'], [25, 'drought'], [32, 'surge'], [36, 'undeaf'], [40, 'blaze'], [44, 'sleepless'], [48, 'scorch']],
    'yong' => [[1, 'ram'], [1, 'spark'], [4, 'rebuke'], [7, 'breeze'], [10, 'chant'], [13, 'selfcall'], [16, 'sleepless'], [19, 'flame'], [25, 'drought'], [32, 'gale'], [36, 'swift'], [40, 'blaze'], [48, 'scorch']],
    'yuanchu' => [[1, 'breeze'], [1, 'feathers'], [5, 'chant'], [10, 'bewilder'], [14, 'sleepless'], [18, 'cassia'], [22, 'flame'], [26, 'selfcall'], [30, 'swift'], [34, 'gale'], [38, 'backeyes'], [42, 'southwind'], [50, 'tempest']],
];
