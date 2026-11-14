<?php
// The data files, checked as written: maps, their scripts, the beasts.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\{Data, Elements, Text};
use Shanhai\Render\Stage;
use Shanhai\World\Tile;
use Shanhai\Script\Vm;
use Shanhai\World\Dir;

$maps = Data::maps();
$beasts = Data::beasts();
$elements = Data::elements();
$tiers = Data::tiers();

$scripts = [];
foreach ($maps as $map) {
    foreach ($map->warps as [$x, $y, $to, $tx, $ty, $dir]) {
        check($map->walkable($x, $y), "{$map->id}: the warp at $x,$y is on a tile that can be walked onto");
        check(isset($maps[$to]) && $maps[$to]->walkable($tx, $ty), "{$map->id}: the warp at $x,$y leads to a walkable tile of a map");
        check(Dir::tryFrom($dir) !== null, "{$map->id}: the warp at $x,$y faces a direction");
        check(isset($maps[$to]) && $maps[$to]->warpAt($tx, $ty) === null, "{$map->id}: the warp at $x,$y does not land on another warp");
    }
    $taken = [];
    foreach ($map->npcs as [$id, $x, $y, $dir, $look, $script]) {
        check($map->walkable($x, $y), "{$map->id}: $id stands on a walkable tile");
        check(Dir::tryFrom($dir) !== null, "{$map->id}: $id faces a direction");
        check(isset(Stage::LOOKS[$look]), "{$map->id}: $id has a look the stage draws: $look");
        check(!isset($taken["$x,$y"]), "{$map->id}: $id has the tile to itself");
        $taken["$x,$y"] = true;
        $scripts["{$map->id}: $id"] = $script;
    }
    foreach ($map->signs as [$x, $y, $script]) {
        check($map->walkable($x, $y) && !isset($taken["$x,$y"]), "{$map->id}: the sign at $x,$y stands on a free walkable tile");
        $taken["$x,$y"] = true;
        $scripts["{$map->id}: the sign at $x,$y"] = $script;
    }
    foreach ($map->triggers as [$x, $y, $flag, $script]) {
        check($map->walkable($x, $y) && !isset($taken["$x,$y"]), "{$map->id}: the trigger at $x,$y can be stepped on");
        check($flag !== '', "{$map->id}: the trigger at $x,$y has a flag");
        $scripts["{$map->id}: the trigger at $x,$y"] = $script;
    }
}
foreach ($scripts as $where => $script) {
    check(!throws(fn() => Vm::check($script)), "$where: its script is well formed");
    foreach ($script as $cmd) {
        if ($cmd[0] === 'say') {
            check(count(Text::wrap($cmd[1], Text::columns())) <= Text::LINES, "$where: the line fits the dialog box: {$cmd[1]}");
        }
        if ($cmd[0] === 'warp') {
            check(isset($maps[$cmd[1]]) && $maps[$cmd[1]]->walkable($cmd[2], $cmd[3]), "$where: its warp leads to a walkable tile of a map");
        }
        if ($cmd[0] === 'giveBeast') {
            check(isset($beasts[$cmd[1]]), "$where: it gives a beast there is");
        }
    }
}
$start = (require Data::DIR . '/game.php')['start'];
check(isset($maps[$start['map']]) && $maps[$start['map']]->walkable($start['x'], $start['y']), 'a new game starts on a walkable tile');

foreach (Tile::KINDS as [$kind]) {
    check(isset(Stage::GROUND[$kind]), "the stage has a ground color for $kind");
}
check(isset(Stage::LOOKS['player']), 'the stage draws the player');

same(30, count($beasts), 'the beasts of the Southern Mountains: 12, 9 and 9 over its three ranges');
foreach ([1 => 12, 2 => 9, 3 => 9] as $range => $count) {
    same($count, count(array_filter($beasts, fn(array $b) => $b['range'] === $range)), "range $range");
}
same(['guanguan', 'lushu', 'xuangui'], (function () use ($beasts) {
    $ids = array_keys(array_filter($beasts, fn(array $b) => $b['starter'] ?? false));
    sort($ids);
    return $ids;
})(), 'the three to choose from at the start');
$names = [];
foreach ($beasts as $id => $b) {
    check(preg_match('/^[a-z]+$/', $id) === 1, "$id is a plain id");
    check(!isset($names[$b['name']]), "$id's name is its alone: {$b['name']}");
    $names[$b['name']] = true;
    check(in_array($b['kind'], ['strange', 'common'], true), "$id is strange or common");
    check($b['mountain'] !== '' && $b['looks'] !== '', "$id has a mountain and its looks");
    check(array_diff(array_keys($b), ['name', 'kind', 'range', 'mountain', 'elements', 'why', 'tier', 'stats', 'catch', 'exp', 'growth', 'looks', 'note', 'sound', 'omen', 'lore', 'unchecked', 'starter']) === [], "$id has only the fields there are");
    check(in_array(count($b['elements']), [1, 2], true) && count(array_unique($b['elements'])) === count($b['elements']), "$id has one element, or two that differ");
    foreach ($b['elements'] as $e) {
        check(isset($elements[$e]), "$id's element is one there is: $e");
    }
    check(($b['why'] ?? '') !== '', "$id says what in the text settles its elements");
    // Its numbers, inside its tier's bands (docs/numbers.md 11).
    $tier = $tiers[$b['tier']] ?? null;
    check($tier !== null, "$id is in a tier there is");
    same(['hp', 'atk', 'def', 'spa', 'spd', 'spe'], array_keys($b['stats']), "$id has the six stats, in order");
    $total = array_sum($b['stats']);
    check($tier !== null && $total >= $tier['total'][0] && $total <= $tier['total'][1], "$id's stats add up inside its tier's band: $total");
    check(min($b['stats']) >= 20 && max($b['stats']) <= 130, "$id's stats are each from 20 to 130");
    $share = max($b['stats']) / $total;
    check($share >= 0.18 && $share <= 0.32, "$id's highest stat is 18% to 32% of the total: " . round($share, 2));
    check($tier !== null && in_array($b['catch'], $tier['catch'], true), "$id's catch rate is one of its tier's");
    check($tier !== null && $b['exp'] >= $tier['exp'][0] && $b['exp'] <= $tier['exp'][1], "$id's base experience is inside its tier's band");
    check($tier !== null && in_array($b['growth'], $tier['growth'], true), "$id's growth rate is one of its tier's");
    // A beast hits hardest with moves of its own element, so its better attacking stat is that element's.
    $special = $elements[$b['elements'][0]]['category'] === 'special';
    check($special ? $b['stats']['spa'] >= $b['stats']['atk'] : $b['stats']['atk'] >= $b['stats']['spa'],
        "$id's better attacking stat is its main element's: " . ($special ? 'special attack' : 'attack'));
    if ($b['kind'] === 'common') {
        check($b['tier'] <= 2, "$id, an ordinary animal, is in the first two tiers");
    }
    foreach (['looks', 'note', 'sound', 'omen', 'lore', 'unchecked'] as $field) {
        check(($b[$field] ?? 'x') !== '', "$id's $field, when given, says something");
    }
}

// The five elements: each overcomes one and feeds one, in two rings of five (docs/elements.md).
same(['wood', 'fire', 'earth', 'metal', 'water'], array_keys($elements), 'the five elements');
foreach (['overcomes', 'feeds'] as $relation) {
    $at = 'wood'; $ring = [];
    for ($i = 0; $i < 5; $i++) {
        $ring[] = $at;
        $at = $elements[$at][$relation];
    }
    check($at === 'wood' && count(array_unique($ring)) === 5, "what each $relation goes round all five");
}
foreach ($elements as $id => $e) {
    check($e['overcomes'] !== $e['feeds'], "$id overcomes one and feeds another");
    check(in_array($e['category'], ['physical', 'special'], true), "$id is physical or special");
}
$chart = new Elements($elements);
same(Elements::DOUBLE, $chart->factor('water', 'fire'), 'water overcomes fire: double');
same(Elements::HALF, $chart->factor('water', 'wood'), 'water feeds wood: half');
same(Elements::EVEN, $chart->factor('fire', 'water'), 'what is overcome hits back evenly');
same(Elements::EVEN, $chart->factor('fire', 'fire'), 'an element hits itself evenly');
same([Elements::DOUBLE, Elements::HALF], $chart->factors('earth', ['water', 'metal']), 'two elements multiply in turn');
same([], $chart->factors('wood', ['water', 'metal']), 'and none when both are even');
$doubles = $halves = 0;
foreach ($elements as $a => $_) {
    foreach ($elements as $d => $_) {
        $doubles += $chart->factor($a, $d) === Elements::DOUBLE ? 1 : 0;
        $halves += $chart->factor($a, $d) === Elements::HALF ? 1 : 0;
    }
}
same([5, 5], [$doubles, $halves], 'five cells of 25 double, five halve');

same([1, 2, 3, 4, 5], array_keys($tiers), 'five tiers');
$top = 0;
foreach ($tiers as $n => $t) {
    check($t['total'][0] > $top && $t['total'][1] >= $t['total'][0], "tier $n's band is above the one before");
    $top = $t['total'][1];
}
$starters = array_filter($beasts, fn(array $b) => $b['starter'] ?? false);
same(1, count(array_unique(array_map(fn(array $b) => array_sum($b['stats']), $starters))), 'the three to choose from have the same total');
same(1, count(array_unique(array_column($starters, 'tier'))), 'and are of one tier');

// The three to choose from press one another round: each has the better of one and the worse of one.
// What a beast's own element does against another beast, as a ratio: 2, 1 or a half, multiplied over the other's elements.
$against = function (string $a, string $b) use ($beasts, $chart): float {
    $ratio = 1.0;
    foreach ($chart->factors($beasts[$a]['elements'][0], $beasts[$b]['elements']) as $f) {
        $ratio *= $f / Elements::EVEN;
    }
    return $ratio;
};
foreach ([['xuangui', 'lushu'], ['lushu', 'guanguan'], ['guanguan', 'xuangui']] as [$a, $b]) {
    check($against($a, $b) > $against($b, $a), "$a has the better of $b");
}

// The moves, inside the bands measured from the reference (docs/numbers.md 10.4).
$moves = Data::moves();
$statuses = ['burn', 'poison', 'paralyze', 'sleep', 'confuse', 'flinch', 'rain', 'sun', 'sandstorm', 'crit', 'heal', 'drain'];
$without = 0;
$perElement = [];
$names = [];
foreach ($moves as $id => $m) {
    check(preg_match('/^[a-z]+$/', $id) === 1, "the move $id has a plain id");
    check(!isset($names[$m['name']]), "the move $id's name is its alone: {$m['name']}");
    $names[$m['name']] = true;
    check(array_diff(array_keys($m), ['name', 'element', 'power', 'accuracy', 'pp', 'effect', 'only', 'from']) === [], "the move $id has only the fields there are");
    check($m['element'] === null || isset($elements[$m['element']]), "the move $id is of an element there is, or of none");
    check(($m['from'] ?? '') !== '', "the move $id says what in the classic it comes from");
    check(in_array($m['accuracy'], [100, 95, 90, 85, 80, 75, 70], true), "the move $id's accuracy is one of those in use");
    check($m['pp'] % 5 === 0 && $m['pp'] >= 5 && $m['pp'] <= 40, "the move $id's uses are a multiple of five from 5 to 40");
    if (isset($m['effect'])) {
        [$what, $chance] = $m['effect'];
        check(in_array($what, $statuses, true) || preg_match('/^(up|down):(atk|def|spa|spd|spe|acc|eva):[12]$/', $what) === 1, "the move $id's effect is one there is: $what");
        check(in_array($chance, [10, 20, 30, 50, 100], true), "the move $id's effect has a chance in use");
    }
    check(!isset($m['only']) || isset($beasts[$m['only']]), "the move $id is only a beast's there is");
    if ($m['power'] === 0) {
        check(isset($m['effect']) && $m['effect'][1] === 100, "the move $id, with no power, has an effect that is the move");
        $without++;
        continue;
    }
    $perElement[$m['element'] ?? 'none'][] = $m['power'];
    // The stronger a move, the fewer its uses; the strongest also miss.
    [$least, $most] = match (true) {
        $m['power'] <= 40 => [25, 40],
        $m['power'] <= 60 => [20, 30],
        $m['power'] <= 80 => [15, 25],
        $m['power'] <= 100 => [10, 15],
        default => [5, 10],
    };
    check($m['pp'] >= $least && $m['pp'] <= $most, "the move $id, of power {$m['power']}, has $least to $most uses");
    check($m['power'] <= 100 || $m['accuracy'] <= 90, "the move $id, of power over 100, can miss");
    check($m['power'] <= 120, "the move $id is of power 120 at most");
}
check($without / count($moves) >= 0.3, 'three moves in ten or more do no damage: ' . $without . ' of ' . count($moves));
foreach (array_keys($elements) as $e) {
    $powers = $perElement[$e] ?? [];
    sort($powers);
    check(count($powers) >= 7 && $powers[0] === 40 && end($powers) >= 110, "$e has seven moves or more that do damage, from 40 to 110 or more: " . implode(' ', $powers));
}

// What each beast learns (data/learnsets.php).
$learnsets = Data::learnsets();
same(array_keys($beasts), array_keys($learnsets), 'every beast has its moves to learn, in the beasts\' order');
$learnedBy = [];
foreach ($learnsets as $id => $set) {
    $at = 0; $seen = []; $first = []; $own = false;
    $main = $beasts[$id]['elements'][0];
    foreach ($set as [$level, $move]) {
        check(isset($moves[$move]), "$id learns a move there is: $move");
        check($level >= $at && $level >= 1 && $level <= 60, "$id learns $move in its turn, from level 1 to 60");
        check(!isset($seen[$move]), "$id learns $move once");
        [$at, $seen[$move]] = [$level, true];
        $learnedBy[$move][] = $id;
        $m = $moves[$move] ?? ['power' => 0, 'element' => null];
        if ($level === 1) {
            $first[] = $m['power'];
        }
        $own = $own || $m['power'] > 0 && $m['element'] === $main;
        check(!isset($m['only']) || $m['only'] === $id, "$id learns no move that is only another's: $move");
    }
    check(count($set) >= 5, "$id learns five moves or more");
    check(count($first) >= 2 && max($first) > 0, "$id starts with two moves or more, one that does damage");
    check($own, "$id learns a move of its main element that does damage");
}
foreach ($moves as $id => $m) {
    if (isset($m['only'])) {
        same([$m['only']], $learnedBy[$id] ?? [], "the move $id is learned by the beast it is only for");
    }
}

same(['你好，', '世界'], Text::wrap('你好，世界', 6), 'a CJK character takes two columns');
same(['abc', 'def'], Text::wrap("abc\ndef", 10), 'a newline breaks');
