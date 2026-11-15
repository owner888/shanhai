<?php
// The moves each beast learns, and at what level: [level, move of data/moves.php], in the order learned.
// A beast knows the last four it has learned. Ours; a beast's moves are those the classic's words about it suggest,
// then those of its elements. Most also learn one move of another element, by their shape or their mountain's waters
// (a bird's beak, a horse's dust, the river that rises on the hill): what lets the worse side of a matchup answer.
// Moves are shared: one the classic's words give to one beast is learned by others it suits, and about four in ten
// of what a beast learns does no damage, at every stage of its growth, as the reference's learnsets measure
// (docs/numbers.md 10.5). Each beast comes to them at levels of its own: where it begins, how late it ends and
// whether it learns quickly at first or late differ from beast to beast, as they do from species to species there.
declare(strict_types=1);

return [
    'xingxing' => [[1, 'ram'], [1, 'breeze'], [5, 'swift'], [7, 'feathers'], [10, 'chant'], [14, 'grapple'], [18, 'cassia'], [22, 'backeyes'], [26, 'splash'], [31, 'selfcall'], [36, 'gale']],
    'baiyuan' => [[1, 'ram'], [1, 'breeze'], [4, 'chant'], [5, 'grapple'], [8, 'feathers'], [13, 'swift'], [18, 'cassia'], [23, 'backeyes'], [30, 'gale']],
    'fuchong' => [[1, 'ram'], [1, 'pebble'], [7, 'sting'], [13, 'bite'], [18, 'slumber'], [22, 'dust'], [26, 'fang'], [29, 'burrow'], [33, 'venom']],
    'lushu' => [[1, 'ram'], [1, 'spark'], [7, 'chant'], [9, 'redtail'], [13, 'stripes'], [16, 'flame'], [20, 'dust'], [25, 'drywind'], [29, 'swift'], [34, 'blaze'], [39, 'wildfire'], [44, 'sleepless'], [49, 'scorch']],
    'xuangui' => [[1, 'ram'], [1, 'splash'], [5, 'woodsplit'], [9, 'foam'], [13, 'undeaf'], [17, 'surge'], [21, 'peck'], [25, 'whirl'], [30, 'slumber'], [34, 'darkshell'], [38, 'revive'], [42, 'billow'], [46, 'flood'], [50, 'deluge']],
    'lu' => [[1, 'ram'], [1, 'pebble'], [8, 'lowing'], [12, 'dust'], [16, 'slumber'], [19, 'revive'], [23, 'gore'], [27, 'hillcharge'], [31, 'sting'], [34, 'trample'], [38, 'fearless'], [42, 'cliffbreak']],
    'lei' => [[1, 'ram'], [1, 'spark'], [5, 'infantcry'], [6, 'tailwhip'], [8, 'redtail'], [11, 'lowing'], [14, 'bite'], [18, 'claw'], [22, 'flame'], [26, 'sleepless'], [30, 'drywind'], [35, 'swift'], [40, 'blaze']],
    'boyi' => [[1, 'ram'], [1, 'spark'], [6, 'fearless'], [13, 'backeyes'], [18, 'chant'], [22, 'flame'], [26, 'hillcharge'], [30, 'tailwhip'], [33, 'undeaf'], [37, 'drywind'], [40, 'sleepless'], [43, 'blaze']],
    'changfu' => [[1, 'ram'], [1, 'breeze'], [7, 'sleepless'], [9, 'rebuke'], [12, 'feathers'], [16, 'bewilder'], [19, 'cassia'], [22, 'peck'], [26, 'selfcall'], [30, 'swift'], [33, 'gale'], [37, 'undeaf'], [41, 'southwind']],
    'jiuweihu' => [[1, 'bite'], [1, 'spark'], [1, 'infantcry'], [9, 'venom'], [14, 'bewilder'], [18, 'flame'], [23, 'sleepless'], [27, 'devour'], [32, 'swift'], [36, 'ninetails'], [41, 'blaze'], [45, 'undeaf'], [50, 'scorch']],
    'guanguan' => [[1, 'ram'], [1, 'breeze'], [6, 'bewilder'], [12, 'feathers'], [17, 'rebuke'], [21, 'cassia'], [25, 'surge'], [28, 'selfcall'], [32, 'backeyes'], [35, 'gale'], [39, 'sleepless'], [42, 'southwind'], [45, 'duststorm'], [48, 'tempest']],
    'chiru' => [[1, 'ram'], [1, 'splash'], [4, 'chant'], [6, 'foam'], [9, 'woodsplit'], [12, 'tailwhip'], [15, 'lowing'], [19, 'surge'], [23, 'undeaf'], [27, 'whirl'], [31, 'sleepless'], [36, 'billow']],
    'lili' => [[1, 'ram'], [1, 'pebble'], [6, 'spur'], [9, 'rebuke'], [13, 'dust'], [17, 'fearless'], [21, 'burrow'], [25, 'stripes'], [30, 'bite'], [34, 'rockfall'], [39, 'swift'], [44, 'landslide']],
    'zhu' => [[1, 'ram'], [1, 'breeze'], [5, 'bewilder'], [10, 'rebuke'], [14, 'feathers'], [18, 'woodsplit'], [21, 'cassia'], [25, 'surge'], [29, 'selfcall'], [32, 'swift'], [36, 'gale'], [39, 'duststorm'], [42, 'southwind'], [46, 'sleepless'], [49, 'tempest']],
    'changyou' => [[1, 'ram'], [1, 'splash'], [6, 'chant'], [7, 'grapple'], [9, 'lowing'], [12, 'foam'], [15, 'woodsplit'], [18, 'surge'], [23, 'flood'], [27, 'undeaf'], [32, 'billow'], [38, 'sleepless'], [44, 'seatide'], [50, 'deluge']],
    'huahuai' => [[1, 'ram'], [1, 'pebble'], [8, 'slumber'], [14, 'fearless'], [18, 'burrow'], [23, 'rebuke'], [27, 'grapple'], [30, 'fang'], [34, 'hillcharge'], [38, 'stripes'], [41, 'rockfall'], [45, 'landslide']],
    'zhi' => [[1, 'bite'], [1, 'claw'], [4, 'rebuke'], [5, 'tailwhip'], [8, 'stripes'], [11, 'fang'], [15, 'infantcry'], [19, 'devour'], [24, 'rockfall'], [29, 'gore'], [34, 'swift'], [40, 'pounce'], [46, 'onehorn']],
    'jiyu' => [[1, 'ram'], [1, 'splash'], [5, 'lowing'], [10, 'foam'], [14, 'swift'], [19, 'surge'], [23, 'undeaf'], [28, 'whirl']],
    'huan' => [[1, 'ram'], [1, 'spark'], [9, 'fearless'], [15, 'undeaf'], [19, 'flame'], [23, 'lowing'], [26, 'revive'], [30, 'hillcharge'], [33, 'drywind'], [36, 'chant'], [39, 'blaze']],
    'biluo' => [[1, 'ram'], [1, 'splash'], [8, 'fearless'], [11, 'undeaf'], [15, 'foam'], [20, 'revive'], [25, 'surge'], [31, 'slumber']],
    'gudiao' => [[1, 'ram'], [1, 'splash'], [7, 'infantcry'], [9, 'rebuke'], [12, 'peck'], [16, 'sleepless'], [20, 'surge'], [24, 'venom'], [29, 'gore'], [34, 'devour'], [39, 'swift'], [44, 'billow'], [50, 'onehorn']],
    'xi' => [[1, 'ram'], [1, 'claw'], [6, 'lowing'], [11, 'fearless'], [15, 'stripes'], [19, 'spur'], [23, 'undeaf'], [27, 'gore'], [30, 'hillcharge'], [34, 'trample'], [37, 'rebuke'], [41, 'onehorn']],
    'si' => [[1, 'ram'], [1, 'claw'], [5, 'lowing'], [7, 'tailwhip'], [10, 'stripes'], [14, 'spur'], [17, 'fearless'], [21, 'gore'], [25, 'hillcharge'], [29, 'trample'], [34, 'rebuke'], [38, 'onehorn']],
    'xiang' => [[1, 'ram'], [1, 'claw'], [7, 'lowing'], [15, 'fearless'], [20, 'stripes'], [25, 'trample'], [29, 'hillcharge'], [33, 'gore'], [37, 'undeaf'], [40, 'pounce'], [44, 'revive'], [47, 'tusk']],
    'quru' => [[1, 'ram'], [1, 'breeze'], [4, 'chant'], [5, 'peck'], [8, 'bewilder'], [11, 'feathers'], [14, 'swift'], [18, 'selfcall'], [23, 'surge'], [27, 'cassia'], [32, 'sleepless'], [37, 'gale'], [43, 'southwind']],
    'hujiao' => [[1, 'bite'], [1, 'splash'], [5, 'chant'], [8, 'tailwhip'], [11, 'sting'], [14, 'foam'], [18, 'sleepless'], [21, 'surge'], [25, 'fang'], [29, 'whirl'], [33, 'swift'], [37, 'billow'], [41, 'undeaf'], [46, 'seatide'], [50, 'deluge']],
    'fenghuang' => [[1, 'breeze'], [1, 'dance'], [1, 'cassia'], [9, 'chant'], [15, 'selfcall'], [21, 'flame'], [26, 'gale'], [31, 'revive'], [36, 'fivehues'], [41, 'sleepless'], [45, 'southwind'], [50, 'undeaf'], [60, 'tempest']],
    'tuanyu' => [[1, 'ram'], [1, 'spark'], [4, 'lowing'], [6, 'splash'], [9, 'woodsplit'], [12, 'redtail'], [16, 'chant'], [21, 'flame'], [25, 'drought'], [30, 'surge'], [34, 'undeaf'], [39, 'blaze'], [45, 'sleepless'], [50, 'scorch']],
    'yong' => [[1, 'ram'], [1, 'spark'], [5, 'rebuke'], [6, 'breeze'], [8, 'chant'], [11, 'selfcall'], [15, 'sleepless'], [20, 'flame'], [24, 'drought'], [30, 'gale'], [36, 'swift'], [42, 'blaze'], [49, 'scorch']],
    'yuanchu' => [[1, 'breeze'], [1, 'feathers'], [6, 'chant'], [12, 'bewilder'], [17, 'sleepless'], [22, 'cassia'], [26, 'flame'], [30, 'selfcall'], [35, 'swift'], [38, 'gale'], [42, 'backeyes'], [46, 'southwind'], [50, 'tempest']],
];
