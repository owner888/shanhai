<?php
// The data files, checked as written: maps, their scripts, the beasts.
declare(strict_types=1);

namespace Shanhai\Tests;

use Shanhai\{Data, Elements, Text};
use Shanhai\Render\{Arena, Stage};
use Shanhai\World\Tile;
use Shanhai\Script\Vm;
use Shanhai\World\Dir;

$maps = Data::maps();
$beasts = Data::beasts();
$elements = Data::elements();
$tiers = Data::tiers();
$items = Data::items();
$gods = Data::gods();
// docs/numbers.md 18, 19: what a thing does held, and the number or kind it does it by.
$holds = ['share' => null, 'flee' => null, 'calm' => null, 'crit' => null, 'coin' => null, 'leftovers' => 'int', 'first' => 'int', 'endure' => 'int', 'evade' => 'int', 'flinch' => 'int', 'heal' => 'int', 'pp' => 'int',
    'boost' => [...array_keys($elements), 'none'], 'cure' => ['poison', 'burn', 'paralyze', 'sleep', 'confuse', 'any']];
$boosted = [];
foreach ($items as $id => $item) {
    if (isset($item['hold'])) {
        $by = $holds[$item['hold']] ?? null;
        check(array_key_exists($item['hold'], $holds) && match (true) { $by === null => !isset($item['by']), $by === 'int' => is_int($item['by'] ?? null) && $item['by'] >= 1 && $item['by'] <= 255, default => in_array($item['by'] ?? null, $by, true) },
            "the thing $id, held, does what a held thing can, by a number or a kind that fits it");
        $boosted[] = $item['hold'] === 'boost' ? $item['by'] : null;
        $found = array_filter($maps ?? Data::maps(), fn($map) => str_contains(json_encode([$map->signs, $map->npcs]), "\"give\",\"$id\""));
        check(isset($item['price']) || $found !== [], "the thing $id, held, is sold or is given somewhere");
        // docs/numbers.md 20: where the classic has it is a mountain there is a map of; what is found, is found there
        $there = array_filter($maps ?? Data::maps(), fn($map) => isset($item['grows']) && str_starts_with($map->name, $item['grows']));
        check(!isset($item['grows']) || $there !== [], "the thing $id grows on a mountain there is a map of");
        check(!isset($item['grows']) || isset($item['price']) || array_intersect_key($found, $there) !== [], "the thing $id is found on the mountain it grows on");
    }
    check(($item['name'] ?? '') !== '' && ($item['from'] ?? '') !== '', "the thing $id has a name and where it comes from");
    check(array_diff(array_keys($item), ['name', 'use', 'hold', 'by', 'grows', 'price', 'bitter', 'from']) === [] && in_array($item['use'] ?? null, [null, 'catch', 'rite', ...\Shanhai\Battle\Medicine::USES], true), "the thing $id has only the fields there are, and a use there is");
    check(!isset($item['price']) || (is_int($item['price']) && $item['price'] >= 1), "the thing $id, if it is sold, has a price of one or more");
    check(!isset($item['bitter']) || (\Shanhai\Battle\Medicine::is($item) && isset(\Shanhai\Battle\Formulas::BOND_CHANGES[$item['bitter']]) && \Shanhai\Battle\Formulas::BOND_CHANGES[$item['bitter']][0] < 0), "the thing $id, if bitter, is a medicine that leaves a beast less close");
    // docs/numbers.md 21, 22: what a thing used on a beast does it by; a seal's hundredths of the catch rate
    $by = $item['by'] ?? null;
    if (!isset($item['hold'])) {
        check(match ($item['use'] ?? null) { 'heal' => $by === 'all' || (is_int($by) && $by >= 1), 'cure' => in_array($by, ['poison', 'burn', 'paralyze', 'sleep', 'confuse', 'any'], true),
            'revive' => $by === null || $by === 'all',
            'catch' => $by === null || (is_int($by) && $by > 100) || in_array($by, ['level', 'water', 'bond', 'fast', 'heavy', 'mate', 'sure'], true),
            'rite' => isset(\Shanhai\Battle\Formulas::RITES[$by]) && \Shanhai\Battle\Formulas::RITES[$by]['thing'] === $id, default => $by === null }, "the thing $id is used by a number or a kind that fits its use");
    } else {
        check(!isset($item['use']) || $item['use'] === $item['hold'], "the thing $id, used from the bag, does what it does held");
    }
}
sort($boosted);
$every = [...array_keys($elements), 'none'];
sort($every);
same($every, array_values(array_filter($boosted)), 'each element, and the moves of none, has one thing that strengthens it');
if (true) {
}

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
        $taken["$x,$y"] = true;
    }
    foreach ($map->stops as [$x, $y, $flag, $script]) {
        check($map->walkable($x, $y) && !isset($taken["$x,$y"]), "{$map->id}: what turns the player back at $x,$y can be stepped on, and has the tile to itself");
        check($flag !== '', "{$map->id}: what turns the player back at $x,$y ends with a flag");
        $last = end($script);
        check($last[0] === 'move' && $last[1] === 'player' && $last[3] === 1, "{$map->id}: what turns the player back at $x,$y ends by walking the player a tile off it");
        $off = Dir::tryFrom((string)($last[2] ?? ''));
        check($off !== null && $map->walkable($x + $off->dx(), $y + $off->dy()) && $map->stopAt($x + $off->dx(), $y + $off->dy()) === null,
            "{$map->id}: what turns the player back at $x,$y walks them onto a tile that can be walked, and turns no one back");
        $scripts["{$map->id}: what turns the player back at $x,$y"] = $script;
        $taken["$x,$y"] = true;
    }
}
// Exits: walking off an edge leads onto the other map's opposite edge, and walking back leads back.
foreach ($maps as $map) {
    foreach ($map->exits as $way => [$to, $offset]) {
        $dir = Dir::tryFrom($way);
        check($dir !== null && isset($maps[$to]) && is_int($offset), "{$map->id}: its exit '$way' is a direction, to a map there is, with a whole offset");
        if ($dir === null || !isset($maps[$to])) {
            continue;
        }
        same([$map->id, -$offset], $maps[$to]->exits[$dir->opposite()->value] ?? null, "{$map->id}: its exit $way to $to is met by one back");
        $crossings = 0;
        $along = $dir->dx() !== 0 ? $map->height : $map->width;
        for ($i = 0; $i < $along; $i++) {
            [$x, $y] = $dir->dx() !== 0 ? [$dir === Dir::Right ? $map->width - 1 : 0, $i] : [$i, $dir === Dir::Down ? $map->height - 1 : 0];
            [$tx, $ty] = $dir->dx() !== 0 ? [$dir === Dir::Right ? 0 : $maps[$to]->width - 1, $i + $offset] : [$i + $offset, $dir === Dir::Down ? 0 : $maps[$to]->height - 1];
            $crossings += $map->walkable($x, $y) && $maps[$to]->walkable($tx, $ty) ? 1 : 0;
        }
        check($crossings >= 1, "{$map->id}: its exit $way can be walked through: $crossings tiles of its edge lead onto $to");
    }
}

// Every map can be walked to from where a new game starts: over walkable tiles, through warps and exits.
// $walk is where the player can go from there, never onto the tiles of $wall ("map x,y").
$game = require Data::DIR . '/game.php';
$walk = function (array $wall) use ($maps, $game): array {
$seen = $sent = [];
$queue = [[$game['start']['map'], $game['start']['x'], $game['start']['y']]];
while ($queue !== []) {
    [$id, $x, $y] = array_pop($queue);
    if (isset($seen[$id][$y][$x]) || !$maps[$id]->walkable($x, $y) || isset($wall["$id $x,$y"])) {
        continue;
    }
    $seen[$id][$y][$x] = true;
    $map = $maps[$id];
    if (($warp = $map->warpAt($x, $y)) !== null) {
        $queue[] = [$warp[2], $warp[3], $warp[4]];
    }
    if (!isset($sent[$id])) { // a map reached: where those on it send the player
        $sent[$id] = true;
        foreach ([...$map->npcs, ...$map->signs] as $who) {
            foreach (end($who) as $cmd) {
                if ($cmd[0] === 'warp') {
                    $queue[] = [$cmd[1], $cmd[2], $cmd[3]];
                }
            }
        }
    }
    foreach (Dir::cases() as $dir) {
        [$nx, $ny] = [$x + $dir->dx(), $y + $dir->dy()];
        if ($map->inside($nx, $ny)) {
            if ($map->signAt($nx, $ny) === null) {
                $queue[] = [$id, $nx, $ny];
            }
        } elseif (isset($map->exits[$dir->value])) {
            [$to, $offset] = $map->exits[$dir->value];
            $queue[] = $dir->dx() !== 0 ? [$to, $dir === Dir::Right ? 0 : $maps[$to]->width - 1, $y + $offset] : [$to, $x + $offset, $dir === Dir::Down ? 0 : $maps[$to]->height - 1];
        }
    }
}
return $seen;
};
$seen = $walk([]);

// As the reference turns the player back from leaving town with no beast: until a script has given the first one
// (and set its flag), what turns the player back keeps them from every ground of wild beasts and from every other map.
$given = [];
foreach ($maps as $map) {
    foreach ([...$map->npcs, ...$map->signs, ...$map->triggers] as $who) {
        $script = end($who);
        foreach ($script as $i => $cmd) {
            if ($cmd[0] === 'giveBeast') {
                foreach (array_slice($script, $i) as $later) {
                    if ($later[0] === 'setFlag') {
                        $given[$later[1]] = true;
                    }
                }
            }
        }
    }
}
$wall = [];
foreach ($maps as $map) {
    foreach ($map->stops as [$x, $y, $flag]) {
        if (isset($given[$flag])) {
            $wall["{$map->id} $x,$y"] = true;
        }
    }
}
$before = $walk($wall);
same([], array_values(array_filter(array_keys($before), fn(string $id) => $id !== $game['start']['map'] && !$maps[$id]->indoor)),
    'with no beast yet, the player stays on the map where the game starts, and the rooms of it');
$wild = 0;
foreach ($before as $id => $rows) {
    foreach ($rows as $y => $row) {
        foreach ($row as $x => $_) {
            $wild += Tile::wild($maps[$id]->tile($x, $y)) ? 1 : 0;
        }
    }
}
same(0, $wild, 'with no beast yet, no ground of wild beasts can be walked onto');

foreach ($maps as $map) {
    check(isset($seen[$map->id]), "{$map->id} can be walked to from where the game starts");
    $onWild = array_filter(str_split(implode('', $map->rows)), Tile::wild(...)) !== [];
    check(!$onWild || $map->wild !== null, "{$map->id}: where there is ground for wild beasts, the map says which");
    if ($map->wild !== null) {
        $met = false;
        foreach ($seen[$map->id] ?? [] as $y => $row) {
            foreach ($row as $x => $_) {
                $met = $met || Tile::wild($map->tile($x, $y));
            }
        }
        check($met, "{$map->id}: its wild beasts' ground can be walked to");
    }
}

// What a map's water holds (docs/numbers.md 13): a rate, three beasts of the water element with their levels, each of the map's own mountain.
foreach ($maps as $map) {
    if ($map->water === null) {
        continue;
    }
    check(str_contains(implode('', $map->rows), '~'), "{$map->id}: a map with beasts in its water has water");
    check(is_int($map->water['rate']) && $map->water['rate'] >= 1 && $map->water['rate'] <= 100, "{$map->id}: its water's rate is 1 to 100 percent");
    same(count(\Shanhai\Battle\Formulas::WATER_SLOTS), count($map->water['slots']), "{$map->id}: its water holds three");
    foreach ($map->water['slots'] as [$level, $id]) {
        check(isset($beasts[$id]) && is_int($level) && $level >= 1 && $level <= 96, "{$map->id}: $id is a beast there is, at a level that four more leave at 100 or under");
        check(in_array('water', $beasts[$id]['elements'] ?? [], true), "{$map->id}: $id, met in water, is of water");
        $mountains = isset($beasts[$id]) ? explode('、', $beasts[$id]['mountain']) : [];
        check(array_filter($mountains, fn(string $m) => str_contains($map->name, $m)) !== [], "{$map->id}: $id lives on this map's mountain, as the classic has it");
    }
}

// What a map's tall grass holds (docs/numbers.md 13, 28): a rate, seven beasts with their levels. A strange beast is of
// the map's own mountain; a common one of any mountain of the range, or of the range this one has its common beasts from.
// Where both are met the common come first and the strange last, in three places (one in ten) unless the mountain has more kinds of them.
$named = [];
foreach ($maps as $map) {
    $named[$map->range][] = $map->name;
}
$roams = fn(int $range) => [...$named[$range], ...(isset(Data::ranges()[$range]['commons']) ? $named[Data::ranges()[$range]['commons']] : [])];
foreach (Data::ranges() as $n => $r) {
    check(!isset($r['commons']) || (isset(Data::ranges()[$r['commons']]) && Data::ranges()[$r['commons']]['book'] === $r['book'] && !isset(Data::ranges()[$r['commons']]['commons'])), "range $n has its common beasts from a range of its own book that has its own");
}
foreach ($maps as $map) {
    if ($map->wild === null) {
        continue;
    }
    check(array_filter(str_split(implode('', $map->rows)), Tile::wild(...)) !== [], "{$map->id}: a map with wild beasts has tall grass or scree for them");
    check(is_int($map->wild['rate']) && $map->wild['rate'] >= 1 && $map->wild['rate'] <= 100, "{$map->id}: its rate is 1 to 100 percent");
    same(count(\Shanhai\Battle\Formulas::WILD_SLOTS), count($map->wild['slots']), "{$map->id}: its grass holds seven");
    foreach ($map->wild['slots'] as [$level, $id]) {
        check(isset($beasts[$id]) && is_int($level) && $level >= 1 && $level <= 100, "{$map->id}: $id is a beast there is, at a level of 1 to 100");
        $mountains = isset($beasts[$id]) ? explode('、', $beasts[$id]['mountain']) : [];
        if (($beasts[$id]['kind'] ?? null) === 'common') {
            check(array_filter($mountains, fn(string $m) => array_filter($roams($map->range), fn(string $name) => str_contains($name, $m)) !== []) !== [], "{$map->id}: $id, a common beast, lives on a mountain of this range, as the classic has it");
        } else {
            check(array_filter($mountains, fn(string $m) => str_contains($map->name, $m)) !== [], "{$map->id}: $id lives on this map's mountain, as the classic has it");
        }
    }
    $kinds = array_map(fn(array $slot) => $beasts[$slot[1]]['kind'] ?? null, $map->wild['slots']);
    $strange = array_keys($kinds, 'strange', true);
    if ($strange !== [] && count($strange) < count($kinds)) {
        same(range(count($kinds) - count($strange), count($kinds) - 1), $strange, "{$map->id}: the common beasts come first and the strange last");
        check(count($strange) <= max(3, count(array_unique(array_map(fn(int $i) => $map->wild['slots'][$i][1], $strange)))), "{$map->id}: the strange hold three places, or one each when they are more kinds");
    }
    $lends = array_filter($maps, fn($m) => in_array($m->range, [$map->range, Data::ranges()[$map->range]['commons'] ?? 0], true) && $m->wild !== null && array_filter($m->wild['slots'], fn(array $slot) => ($beasts[$slot[1]]['kind'] ?? null) === 'common') !== []);
    check($strange === [] || count($strange) < count($kinds) || $lends === [], "{$map->id}: where the range has common beasts, a mountain's strange ones are not all that is met");
}
$scripts['data/game.php: waking after a battle lost'] = (require Data::DIR . '/game.php')['rest']['script'];
foreach ($scripts as $where => $script) {
    check(!throws(fn() => Vm::check($script)), "$where: its script is well formed");
    foreach ($script as $cmd) {
        if ($cmd[0] === 'say' || $cmd[0] === 'ask') {
            check(count(Text::wrap($cmd[1], Text::columns())) <= Text::LINES, "$where: the line fits the dialog box: {$cmd[1]}");
        }
        if ($cmd[0] === 'ask') {
            foreach ($cmd[2] as [$choice]) {
                check(mb_strwidth($choice) <= 12, "$where: the choice fits its list: $choice");
            }
        }
        if ($cmd[0] === 'warp') {
            check(isset($maps[$cmd[1]]) && $maps[$cmd[1]]->walkable($cmd[2], $cmd[3]), "$where: its warp leads to a walkable tile of a map");
        }
        if ($cmd[0] === 'shop') {
            foreach ($cmd[1] as $id) {
                check(is_int($items[$id]['price'] ?? null) && $items[$id]['price'] >= 1, "$where: it sells a thing there is, at a price: $id");
            }
            check($cmd[1][0] === 'seal', "$where: the plain seal is sold wherever anything is");
        }
        if ($cmd[0] === 'give') {
            check(isset($items[$cmd[1]]) && is_int($cmd[2]) && $cmd[2] >= 1, "$where: it gives a thing there is, one or more: {$cmd[1]}");
        }
        if (in_array($cmd[0], ['trial', 'league', 'duel'], true)) {
            check(count($cmd[2]) <= 6, "$where: a trial is against six beasts at most");
            foreach ($cmd[2] as [$id, $level]) {
                check((isset($beasts[$id]) || isset($gods[$id])) && $level >= 1 && $level <= 100, "$where: its trial's $id is a beast or a god there is, at a level of 1 to 100");
            }
        }
        if ($cmd[0] === 'giveBeast' || $cmd[0] === 'battle') {
            check(isset($beasts[$cmd[1]]), "$where: its beast is one there is: {$cmd[1]}");
            check(is_int($cmd[2]) && $cmd[2] >= 1 && $cmd[2] <= 100, "$where: its beast's level is 1 to 100");
        }
    }
}
$game = require Data::DIR . '/game.php';
foreach (['start' => 'a new game starts', 'rest' => 'the player wakes after losing'] as $key => $what) {
    $at = $game[$key];
    check(isset($maps[$at['map']]) && $maps[$at['map']]->walkable($at['x'], $at['y']), "$what on a walkable tile");
    check(Dir::tryFrom($at['dir']) !== null, "$what facing a direction");
}
check($maps[$game['rest']['map']]->warpAt($game['rest']['x'], $game['rest']['y']) === null, 'not on a warp');

foreach (Tile::KINDS as [$kind]) {
    check(isset(Stage::GROUND[$kind]), "the stage has a ground color for $kind");
}
check(isset(Stage::LOOKS['player']), 'the stage draws the player');
same(array_keys($elements), array_keys(Arena::ELEMENTS), 'the arena has a color for a beast of each element');

same(275, count($beasts), 'the beasts: 12, 9 and 9 over the Southern Mountains\' three ranges, 28, 11, 22 and 18 over the Western\'s four, 32, 10 and 21 in the Northern\'s three, 11, 10, 7 and 11 in the Eastern\'s four, 4, 5, 6, 3, 2, 2, 6, 8, 7, 2, 14 and 5 in the Central\'s twelve');
foreach ([1 => 12, 2 => 9, 3 => 9, 4 => 28, 5 => 11, 6 => 22, 7 => 18, 8 => 32, 9 => 10, 10 => 21, 11 => 11, 12 => 10, 13 => 7, 14 => 11, 15 => 4, 16 => 5, 17 => 6, 18 => 3, 19 => 2, 20 => 2, 21 => 6, 22 => 8, 23 => 7, 24 => 2, 25 => 14, 26 => 5] as $range => $count) {
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
    check(array_diff(array_keys($b), ['name', 'kind', 'range', 'mountain', 'elements', 'why', 'tier', 'stats', 'catch', 'exp', 'growth', 'looks', 'note', 'sound', 'omen', 'lore', 'unchecked', 'starter', 'gender', 'flees', 'weight']) === [], "$id has only the fields there are");
    check(!isset($b['gender']) || $b['gender'] === 'none' || (is_int($b['gender']) && $b['gender'] >= 0 && $b['gender'] <= \Shanhai\Battle\Formulas::GENDER_ALL), "$id's gender is a share of females or none");
    check(!isset($b['flees']) || $b['flees'] === 'always' || isset(\Shanhai\Battle\Formulas::FLEES[$b['flees']]), "$id flees as a wild beast can");
    check(!isset($b['weight']) || (isset(\Shanhai\Battle\Formulas::WEIGHT[$b['weight']]) && $b['weight'] > 0), "$id's weight is a class there is, the lightest being unwritten");
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
    check(array_diff(array_keys($m), ['name', 'element', 'power', 'accuracy', 'pp', 'effect', 'only', 'bond', 'from']) === [], "the move $id has only the fields there are");
    check($m['element'] === null || isset($elements[$m['element']]), "the move $id is of an element there is, or of none");
    check(($m['from'] ?? '') !== '', "the move $id says what in the classic it comes from");
    check(in_array($m['accuracy'], [100, 95, 90, 85, 80, 75, 70], true), "the move $id's accuracy is one of those in use");
    check($m['pp'] % 5 === 0 && $m['pp'] >= 5 && $m['pp'] <= 40, "the move $id's uses are a multiple of five from 5 to 40");
    if (isset($m['effect'])) {
        [$what, $chance] = $m['effect'];
        check(in_array($what, $statuses, true) || $what === 'charm' || preg_match('/^(up|down):(atk|def|spa|spd|spe|acc|eva):[12]$/', $what) === 1, "the move $id's effect is one there is: $what");
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
// A trial is as strong as the reference's gym leaders are against what comes before them (docs/numbers.md 14): the
// one who sets it sends two to six, at the levels of the reference's leader of the gym its range is measured from; the
// last is the strongest, the others at its level or up to six under; in the reference's first eight gyms the last is
// 1.65 to 2.55 times the mean level of the wild beasts of its range (in its second half the wild are far lower).
// What the game shows is written in characters its font has: none beyond the basic plane (docs/bestiary.md, 生僻字的替换).
foreach ([...glob(Data::DIR . '/*.php'), ...glob(Data::DIR . '/maps/*.php')] as $file) {
    preg_match_all('/[\x{10000}-\x{10FFFF}]/u', file_get_contents($file), $rare);
    same([], array_values(array_unique($rare[0])), basename($file) . ' has no character the font cannot draw');
}
$ranges = Data::ranges();
same(range(1, count($ranges)), array_keys($ranges), 'the ranges are numbered from 1, in the classic\'s order');
foreach ($ranges as $n => $r) {
    check(in_array($r['toward'], ['left', 'right'], true), "range $n says which way the classic walks it");
}
foreach ($maps as $map) {
    check(isset($ranges[$map->range]), "{$map->id} says which range it is of, one there is");
}
foreach ($gods as $id => $g) {
    check(isset($ranges[$g['range']]), "the god $id keeps a range there is");
}
// docs/atlas.md is what tools/atlas.php writes from the data as it is now.
same(\Shanhai\Atlas::render(), file_get_contents(Data::DIR . '/../docs/atlas.md'), 'docs/atlas.md is up to date: php tools/atlas.php writes it');
$leaders = [1 => [7, 9], 2 => [14, 14, 16], 3 => [18, 20], 4 => [21, 21, 23, 25], 5 => [27, 30], 6 => [30, 30, 35], 7 => [27, 29, 31], 8 => [37, 37, 37, 40],
    9 => [40, 40, 40, 44, 46], 10 => [46, 46, 48], 11 => [41, 42, 46, 46], 12 => [33, 36, 36, 36, 39], 13 => [42, 42, 44, 47], 14 => [41, 41, 42, 42, 44], 15 => [45, 45, 50], 16 => [54, 56, 56, 58, 58, 58]];
$ours = [17 => 60, 18 => 63, 19 => 65, 20 => 67, 21 => 70, 22 => 72, 23 => 74, 24 => 77, 25 => 79, 26 => 81];
$trials = 0;
foreach ($scripts as $where => $script) {
    foreach ($script as $cmd) {
        if ($cmd[0] !== 'trial') {
            continue;
        }
        $trials++;
        [$aceId, $ace] = end($cmd[2]);
        check(isset($gods[$aceId]), "$where: the trial's last is a god: $aceId");
        $means = [];
        foreach ($maps as $map) {
            if ($map->range === ($gods[$aceId]['range'] ?? null) && $map->wild !== null) {
                $means[] = array_sum(array_column($map->wild['slots'], 0)) / count($map->wild['slots']);
            }
        }
        $mean = array_sum($means) / count($means);
        check(count($cmd[2]) >= 2 && count($cmd[2]) <= 6, "$where: a trial is against two to six");
        // the levels are the reference's gym leader's own, for the sixteen gyms there are
        $gym = $ranges[$gods[$aceId]['range'] ?? 0]['gym'] ?? null;
        $theirs = array_column($cmd[2], 1);
        sort($theirs);
        if (isset($leaders[$gym])) {
            same($leaders[$gym], $theirs, "$where: its levels are those of the reference's gym $gym");
        }
        // and in the reference's first half the last is 1.65 to 2.55 times the mean level of the wild beasts before it
        // past the sixteenth the levels are ours (docs/numbers.md 14): the last by a table, and the same measure kept
        if ($gym > 16) {
            same($ours[$gym] ?? null, $ace, "$where: its last is at the level set for range $gym");
        }
        if ($gym <= 8 || $gym > 16) {
            check($ace / $mean >= 1.65 && $ace / $mean <= 2.55, sprintf('%s: its last, at level %d, is 1.65 to 2.55 times the range\'s wild beasts\' mean of %.1f: %.2f', $where, $ace, $mean, $ace / $mean));
        }
        foreach (array_slice($cmd[2], 0, -1) as [$id, $level]) {
            check($level <= $ace && $level >= $ace - 6, "$where: $id, at level $level, is at the last one's level or up to six under");
        }
    }
}
same(count($gods), $trials, 'each god sets one trial');
// Those met on the roads (data/opponents.php): how many a range has and how many beasts they send are the reference's
// before the gym of its number (docs/numbers.md 15); each stands on ground of a map under the sky, beside its road
// and looking across it, on no one's and nothing's tile.
$foes = Data::opponents();
$raw = [];
foreach (glob(Data::DIR . '/maps/*.php') as $file) {
    $a = require $file;
    $raw[$a['id']] = $a;
}
$counts = [];
foreach ($foes['at'] as $id => $list) {
    $map = $maps[$id] ?? null;
    check($map !== null && !$map->indoor && $id !== $game['start']['map'], "opponents are on a map there is, under the sky, not the one the game starts on: $id");
    $taken = [];
    foreach (['signs', 'npcs', 'warps', 'triggers'] as $key) {
        foreach ($raw[$id][$key] ?? [] as $e) {
            $taken[] = $key === 'npcs' ? [$e[1], $e[2]] : [$e[0], $e[1]];
        }
    }
    foreach ($list as $i => [$x, $y, $dir, $sight, $who, $team]) {
        $where = "data/opponents.php: $id, the " . ($i + 1) . 'th';
        $d = Dir::tryFrom($dir);
        check($map->walkable($x, $y) && Tile::name($map->tile($x, $y)) !== 'path' && !Tile::wild($map->tile($x, $y)) && !in_array([$x, $y], $taken, true), "$where stands on open ground that is no one's");
        $taken[] = [$x, $y];
        check($d !== null && $map->inside($x + $d->dx(), $y + $d->dy()) && Tile::name($map->tile($x + $d->dx(), $y + $d->dy())) === 'path', "$where looks across the road beside them");
        check(is_int($sight) && $sight >= 0 && $sight <= 5 && $who !== '' && count($team) >= 1 && count($team) <= 6, "$where sees 0 to 5 tiles, has a name, and sends one to six");
        foreach ($team as [$beast, $level]) {
            check(isset($beasts[$beast]) && $beasts[$beast]['range'] <= $map->range && $level >= 1 && $level <= 100, "$where sends a beast of this range or an earlier: $beast");
        }
        $counts[$map->range][0] = ($counts[$map->range][0] ?? 0) + 1;
        $counts[$map->range][1] = ($counts[$map->range][1] ?? 0) + count($team);
    }
}
ksort($counts);
same([1 => [12, 25], 2 => [23, 43], 3 => [22, 44], 4 => [9, 20], 5 => [35, 72], 7 => [23, 46], 8 => [12, 22], 9 => [21, 45], 10 => [9, 20], 11 => [11, 24], 12 => [18, 50], 13 => [18, 35], 14 => [11, 20], 15 => [12, 28], 17 => [5, 9], 18 => [9, 17], 19 => [14, 27], 20 => [13, 25], 21 => [18, 35], 22 => [22, 43], 23 => [15, 30], 24 => [9, 17], 25 => [35, 70], 26 => [14, 27]], $counts, 'by range, how many are met and how many beasts they send: the reference\'s before each gym');
foreach ($foes['says'] as $says) {
    check(count($says) === 3 && !in_array('', $says, true), 'what an opponent says is three lines: before, beaten, met again');
}
// Each range has somewhere the party is made whole, as the reference has a place to heal in each town, and the pen
// there, as the reference has its storage where it heals.
$rests = $pens = $shops = [];
foreach ($maps as $map) {
    foreach ([...$map->npcs, ...$map->signs] as $who) {
        foreach (end($who) as $cmd) {
            $rests[$map->range] = ($rests[$map->range] ?? false) || $cmd[0] === 'rest';
            $pens[$map->range] = ($pens[$map->range] ?? false) || $cmd[0] === 'pen';
            $shops[$map->range] = ($shops[$map->range] ?? false) || $cmd[0] === 'shop';
        }
    }
}
foreach (array_keys($ranges) as $n) {
    check($rests[$n] ?? false, "range $n has someone or something that makes the party whole");
    check($pens[$n] ?? false, "range $n has the pen");
    check($shops[$n] ?? false, "range $n has someone who sells seals");
}
// A range after the first is come to from the one before it, and gone back from: someone on a map of each sends the
// player to a map of the other.
$sent = [];
foreach ($maps as $map) {
    foreach ($map->npcs as $npc) {
        foreach (end($npc) as $cmd) {
            if ($cmd[0] === 'warp') {
                $sent[$map->range][$maps[$cmd[1]]->range] = true;
            }
        }
    }
}
foreach (array_slice(array_keys($ranges), 1) as $n) {
    check(isset($sent[$n - 1][$n]), "range $n is come to from range " . ($n - 1));
    check(isset($sent[$n][$n - 1]), "range $n has a way back to range " . ($n - 1));
}
// Water may be crossed after the fourth range's trial, as after the reference's fourth gym: the flag that lets it is the one that trial sets.
$sets = [];
foreach ($scripts as $script) {
    $range = null;
    foreach ($script as $cmd) {
        $range = $cmd[0] === 'trial' ? $gods[end($cmd[2])[0]]['range'] : $range;
        if ($cmd[0] === 'setFlag' && $range !== null) {
            $sets[$cmd[1]] = $range;
        }
    }
}
check(!in_array(4, $sets, true) || ($sets[$game['float']] ?? null) === 4, 'the flag that lets water be crossed is set by the fourth range\'s trial, once there is one');
check(!isset($sets[$game['float']]) || $sets[$game['float']] === 4, 'and by no other');

// The gods (data/gods.php): what a beast's entry has to fight with, moves there are, and the classic's line.
foreach ($gods as $id => $g) {
    check(!isset($beasts[$id]), "the god $id is not also a beast");
    foreach (['name', 'looks', 'why', 'from'] as $field) {
        check(($g[$field] ?? '') !== '', "the god $id has its $field");
    }
    check($g['elements'] !== [] && array_diff($g['elements'], array_keys($elements)) === [], "the god $id's elements are elements there are");
    same(['hp', 'atk', 'def', 'spa', 'spd', 'spe'], array_keys($g['stats']), "the god $id has the six stats");
    $total = array_sum($g['stats']);
    check($total >= $tiers[4]['total'][0] && $total <= $tiers[5]['total'][1], "the god $id's stats are of the two highest tiers: $total");
    check(is_int($g['exp']) && $g['exp'] > 0 && in_array($g['growth'], [...$tiers[4]['growth'], ...$tiers[5]['growth']], true), "the god $id has a base experience, and a growth rate of those tiers");
    check(count($g['moves']) >= 1 && count($g['moves']) <= 4 && array_diff($g['moves'], array_keys($moves)) === [], "the god $id knows one to four moves there are");
}

// Each beast comes to its moves at levels of its own, as the reference's species do (docs/numbers.md 10.5).
$schedules = [];
$levelsOf = [];
foreach ($learnsets as $id => $set) {
    $schedules[$id] = implode(' ', array_column(array_filter($set, fn(array $e) => $e[0] > 1), 0));
    foreach ($set as [$level, $move]) {
        if ($level > 1) { // what a beast begins with, it begins with: those are level 1 for all
            $levelsOf[$move][] = $level;
        }
    }
}
same(count($learnsets), count(array_unique($schedules)), 'no two beasts learn at the same levels');
foreach ($levelsOf as $move => $levels) {
    if (count($levels) >= 5) {
        check(count(array_unique($levels)) >= 3, "the move $move, which " . count($levels) . ' beasts come to as they grow, is learned at three levels or more: ' . implode(' ', $levels));
    }
}

// Moves are shared, and what does no damage is a good part of what is learned, as measured from the reference (docs/numbers.md 10.5).
$entries = $without = 0;
$late = 0;
foreach ($learnsets as $id => $set) {
    $own = 0;
    foreach ($set as [$level, $move]) {
        $entries++;
        if ($moves[$move]['power'] === 0) {
            $without++;
            $own++;
            $late += $level >= 25 ? 1 : 0;
        }
    }
    check($own >= 2, "$id learns two moves or more that do no damage");
}
check($without / $entries >= 0.33 && $without / $entries <= 0.5, 'a third to a half of what is learned does no damage: ' . round(100 * $without / $entries) . '%');
check($late / $without >= 0.25, 'a quarter or more of it from level 25 on: ' . round(100 * $late / $without) . '%');
$learners = function (array $effects) use ($moves, $learnedBy, $beasts): float {
    $who = [];
    foreach ($moves as $id => $m) {
        if ($m['power'] === 0 && in_array($m['effect'][0], $effects, true)) {
            $who = array_merge($who, $learnedBy[$id] ?? []);
        }
    }
    return count(array_unique($who)) / count($beasts);
};
foreach (['sleep' => ['sleep'], 'confusion' => ['confuse'], 'paralysis' => ['paralyze'], 'poison' => ['poison'], 'healing' => ['heal'], 'the weather' => ['rain', 'sun', 'sandstorm']] as $what => $effects) {
    $share = $learners($effects);
    check($share >= 0.08 && $share <= 0.25, "$what is learned by one beast in twelve to one in four: " . round(100 * $share) . '%');
}
foreach ($moves as $id => $m) {
    if ($m['power'] === 0 && !isset($m['only'])) {
        check(count($learnedBy[$id] ?? []) >= 2, "the move $id, which does no damage, is shared: " . count($learnedBy[$id] ?? []) . ' learn it');
    }
}

same(['你好，', '世界'], Text::wrap('你好，世界', 6), 'a CJK character takes two columns');
same(['abc', 'def'], Text::wrap("abc\ndef", 10), 'a newline breaks');

// docs/numbers.md 26: the gods have the things the reference's leaders have, gym for gym (the twelfth's has one of
// ours' none, so its god has nothing); from the seventeenth range on, what the sixteenth's has.
$uses = [];
foreach ($scripts as $script) {
    foreach ($script as $cmd) {
        if ($cmd[0] === 'trial') {
            $uses[$gods[end($cmd[2])[0]]['range']] = $cmd[3] ?? [];
        }
    }
}
ksort($uses);
same([1 => [], 2 => [], 3 => [], 4 => [], 5 => ['yupei'], 6 => ['licao'], 7 => ['licao'], 8 => ['yupei', 'licao'], 9 => ['licao'], 10 => ['licao'], 11 => ['licao'], 12 => [], 13 => ['yupei'], 14 => ['licao'],
    15 => ['youmu', 'yupei'], 16 => ['dixiu', 'dixiu']] + array_fill(17, 10, ['dixiu', 'dixiu']), $uses, 'what each god has to use in its trial');

// docs/numbers.md 32: the Lower Capital's five, at the levels and with the things of the reference's four and its champion.
$five = array_values(array_filter(Data::maps()['kunlun']->npcs[0][5], fn(array $c) => $c[0] === 'league'));
same(['英招', '长乘', '蓐收', '西王母', '陆吾'], array_column($five, 1), 'who they are, in order');
same([[40, 41, 41, 41, 42], [40, 41, 43, 42, 44], [42, 42, 42, 43, 46], [42, 42, 45, 44, 47], [44, 47, 47, 46, 46, 50]], array_map(fn(array $c) => array_column($c[2], 1), $five), "the reference's levels");
same([['youmu'], ['yupei', 'dixiu'], ['youmu'], ['yupei', 'youmu'], ['yupei', 'dixiu']], array_column($five, 3), "and its things, by what each does");
foreach ($five as $c) {
    foreach ($c[2] as [$id]) {
        check(($beasts[$id]['range'] ?? null) === 6, "{$c[1]}: $id is a beast of 西次三经, the range the place is in");
    }
}
$script = Data::maps()['kunlun']->npcs[0][5];
$at = fn(string $name) => array_keys(array_filter($script, fn(array $c) => $c[0] === $name));
check(max($at('league')) + 1 === $at('setFlag')[0] && $script[$at('setFlag')[0]][1] === 'passedLeague', 'the flag is set after the last of the five and nowhere before: to lose to any is to begin again');
check(['ifFlag', 'passedBeishan', 'ready'] === $script[2], 'and none of it begins before the first of the northern ranges is passed');
$north = Data::maps()['tishan']->npcs;
$gate = array_merge(...array_map(fn(array $n) => $n[5], $north));
$i = array_search(['label', 'passed'], $gate, true);
same(['ifFlag', 'passedLeague', 'open'], $gate[$i + 1], 'the road north of 隄山 opens when the five are passed');
check($gate[$i + 2][0] === 'ask' && !in_array('send', array_column($gate[$i + 2][2], 1), true), 'and till then its god offers no way on');
