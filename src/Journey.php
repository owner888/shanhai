<?php
declare(strict_types=1);

namespace Shanhai;

use RuntimeException;
use Shanhai\Battle\Formulas;
use Shanhai\Battle\Fighter;
use Shanhai\World\{Controls, Dir, Map, Pen, Tile, World};

/**
 * A player with no hands: plays a game from its start through every trial there is, by the same World::step and the
 * same Controls a person's keys make, so that what it meets is what a person would. It walks the roads, takes the
 * first of what it is offered (but what a question must be answered to be taken somewhere or to rest), fights those
 * on the road who see it and every wild beast that comes, with the move that would do most, and goes to a trial when
 * its beasts' mean level is what it wants: 0 at first, so it tries at once, and two more than it had each time it
 * fails. Before a trial it has its beasts made whole at the nearest place that does so.
 *
 * It keeps a party as a person does, of $size beasts (six, all there is room for): with the seals it is given and
 * buys it goes looking for wild beasts of kinds it has not that a seal has one chance in ten or better to hold, and
 * binds them, wearing each down first, and buys more seals when it has none; in the wild and on the roads it
 * sends its weakest first, and calls it back for its strongest when the other is of a higher level, so that both
 * grow; in a trial its strongest goes first. A party of one is the player it was before.
 *
 * With six it goes on looking, for wild beasts worth more than its weakest (worth()): one bound goes to the pen, and
 * at the nearest way to the pen it leaves its weakest there and takes the new one.
 *
 * It is for knowing that the game can be played through and what that takes (tools/journey.php), not for playing well:
 * it uses no move that does no damage, and never flees.
 */
final class Journey
{
    /** More updates than any journey should take: one that goes on past this is stuck. */
    private const LIMIT = 40_000_000;

    public int $updates = 0;
    public int $wild = 0;
    public int $lost = 0;
    /** How often it has left a beast in the pen for a better one. */
    public int $swapped = 0;
    /**
     * A line a trial passed: the mean level its beasts went into it at, the lowest and the highest of them and how many
     * they were, counting from the start how often trials were tried, wild battles, battles lost and updates.
     * @var list<array{range: int, who: string, tries: int, level: float, low: int, high: int, size: int, wild: int, lost: int, updates: int}>
     */
    public array $passed = [];
    /** @var array<int, int> by range: the mean level its beasts are to be at before the trial is tried */
    private array $want = [];
    /** @var array<int, int> by range: how often its trial was tried */
    private array $tries = [];
    private ?int $trying = null;
    private bool $roadLost = false;
    /** @var list<array{range: int, map: string, npc: string, flag: string, who: string}> the trials, in the ranges' order */
    private array $trials = [];

    /**
     * @param array<string, Map> $maps
     * @param array<string, array> $moves data/moves.php
     * @param int $starter which of the three it takes
     * @param int $size how many beasts it keeps: 1 binds none
     */
    public function __construct(public readonly World $w, private readonly array $maps, private readonly array $moves, private readonly array $gods, private readonly int $starter = 0, private readonly int $size = World::PARTY)
    {
        foreach ($maps as $map) {
            foreach ($map->npcs as $npc) {
                $flag = null;
                foreach ($npc[5] as $i => $cmd) {
                    if ($cmd[0] === 'trial') {
                        $flag = $npc[5][$i + 1][0] === 'setFlag' ? $npc[5][$i + 1][1] : throw new RuntimeException("{$map->id}: the trial sets no flag after it");
                        $this->trials[] = ['range' => $gods[end($cmd[2])[0]]['range'], 'map' => $map->id, 'npc' => $npc[0], 'flag' => $flag, 'who' => $cmd[1]];
                    }
                }
            }
        }
        usort($this->trials, fn(array $a, array $b) => $a['range'] <=> $b['range']);
    }

    /** Plays until every trial is passed. */
    public function play(): void
    {
        while (true) {
            try {
                $this->settle();
                if (!$this->advance()) {
                    return;
                }
            } catch (Woke) {
                // a battle was lost: the player is where the game wakes them, and what was being done is begun again
                if ($this->trying !== null) {
                    $this->want[$this->trying] = min(Formulas::MAX_LEVEL, (int)$this->level() + 2);
                    $this->trying = null;
                } elseif ($this->roadLost) {
                    // to one met on the road, who will be there again: a level more first, in the wild of the range being walked
                    foreach ($this->trials as $t) {
                        if (!isset($this->w->flags[$t['flag']])) {
                            $this->want[$t['range']] = min(Formulas::MAX_LEVEL, max($this->want[$t['range']] ?? 0, (int)$this->level() + 1));
                            break;
                        }
                    }
                }
                $this->roadLost = false;
            }
        }
    }

    /** The next thing to do; false when there is nothing left. */
    private function advance(): bool
    {
        $w = $this->w;
        if ($w->party === []) {
            $this->travel('zhaoyao_hut');
            $this->talk($w->actor('shaman')->x, $w->actor('shaman')->y, [$this->starter, 0]);
            return true;
        }
        if ($this->boxed() !== null) {
            $this->mend();
            if (!$this->visit('pen')) {
                throw new RuntimeException('no way to the pen can be come to');
            }
            return true;
        }
        $near = $this->near();
        if ($near !== null && ($w->items['seal'] ?? 0) === 0 && $this->supply()) {
            return true;
        }
        if ($near !== null) {
            $this->seek($near);
            return true;
        }
        $next = null;
        foreach ($this->trials as $t) {
            if (!isset($w->flags[$t['flag']])) {
                $next = $t;
                break;
            }
        }
        if ($next === null) {
            return false;
        }
        $r = $next['range'];
        if ($this->level() < ($this->want[$r] ?? 0)) {
            if ($this->spent()) {
                $this->mend();
            }
            $this->grind($r);
            return true;
        }
        $this->mend();
        $this->travel($next['map']);
        $this->arrange(false);
        $god = $w->actor($next['npc']);
        $levels = array_map(fn($f) => $f->level, $w->party);
        [$this->trying, $this->tries[$r], $level] = [$r, ($this->tries[$r] ?? 0) + 1, $this->level()];
        $this->talk($god->x, $god->y, []);
        $this->trying = null;
        if (!isset($w->flags[$next['flag']])) {
            throw new RuntimeException("the trial of range $r was talked through and neither passed nor lost");
        }
        $this->passed[] = ['range' => $r, 'who' => $next['who'], 'tries' => $this->tries[$r], 'level' => $level, 'low' => min($levels), 'high' => max($levels), 'size' => count($levels), 'wild' => $this->wild, 'lost' => $this->lost, 'updates' => $this->updates, 'money' => $this->w->money];
        return true;
    }

    /** The mean level of the party. */
    private function level(): float
    {
        return array_sum(array_map(fn($f) => $f->level, $this->w->party)) / count($this->w->party);
    }

    /** Whether the party is too worn to go on in the wild: its strongest has fallen, or fewer than half can fight. */
    private function spent(): bool
    {
        $able = array_filter($this->w->party, fn($f) => !$f->fainted());
        $top = max(array_map(fn($f) => $f->level, $this->w->party));
        return 2 * count($able) < count($this->w->party) || array_filter($able, fn($f) => $f->level === $top) === [];
    }

    /**
     * Puts the party in order by the menu's own screen, as a person would: those that can fight before those that
     * cannot, and among them the weakest first, or the strongest.
     */
    private function arrange(bool $weakFirst): void
    {
        $w = $this->w;
        $want = $w->party;
        usort($want, fn($a, $b) => [$a->fainted(), $weakFirst ? $a->level : -$a->level] <=> [$b->fainted(), $weakFirst ? $b->level : -$b->level]);
        if ($want === $w->party) {
            return;
        }
        $this->tick(menu: true);
        $menu = $w->menu ?? throw new RuntimeException('the menu button opened no menu');
        $this->cursorTo(array_search(\Shanhai\World\Menu::ENTRIES['party'], $menu->items($w->party, $w->items, []), true), fn() => $w->menu->at());
        $this->tick(confirm: true);
        foreach ($want as $i => $f) {
            $j = array_search($f, $w->party, true);
            if ($j !== $i) {
                $this->cursorTo($i, fn() => $w->menu->at());
                $this->tick(confirm: true);
                $this->cursorTo($j, fn() => $w->menu->at());
                $this->tick(confirm: true);
            }
        }
        if ($want !== $w->party) {
            throw new RuntimeException('the party was put in order and is not in it');
        }
        $this->tick(cancel: true);
        $this->tick(menu: true);
        if ($w->menu !== null) {
            throw new RuntimeException('the menu was closed and is still open');
        }
    }

    /** Fights in the wild of range $r until the party's mean level is the level wanted, or it is too worn to go on. */
    private function grind(int $r): void
    {
        $best = null;
        foreach ($this->maps as $map) {
            if ($map->range === $r && $map->wild !== null && $this->route($map->id) !== null) {
                $mean = array_sum(array_column($map->wild['slots'], 0));
                if ($best === null || $mean > $best[1]) {
                    $best = [$map->id, $mean];
                }
            }
        }
        if ($best === null) {
            throw new RuntimeException("range $r has no wild beasts that can be come to");
        }
        $this->travel($best[0]);
        $this->pace(fn() => $this->level() < $this->want[$r]);
    }

    /** What a beast of $level is worth beside another: its level times what its kind's base stats add up to, as its stats go by the two. */
    private function worth(int $level, array $species): int
    {
        return $level * array_sum($species['stats']);
    }

    /** Where in the party its weakest is, by worth. */
    private function weakest(): int
    {
        $worths = array_map(fn(Fighter $f) => $this->worth($f->level, $f->species), $this->w->party);
        return array_search(min($worths), $worths, true);
    }

    /**
     * Whether a wild beast of $level and kind $id is one to bind: of a kind not in the party, and, the party having its
     * $size already, only with all six there are room for, the pen's box not full, and a worth over its weakest's.
     */
    private function wants(int $level, string $id): bool
    {
        $w = $this->w;
        if (in_array($id, array_map(fn(Fighter $f) => $f->id, $w->party), true)) {
            return false;
        }
        if (count($w->party) < $this->size) {
            return true;
        }
        $weak = $w->party[$this->weakest()];
        return $this->size === World::PARTY && count($w->boxes[$w->box]) < Pen::BOX
            && $this->worth($level, $this->beasts()[$id]) > $this->worth($weak->level, $weak->species);
    }

    /** @var ?array<string, array> */
    private ?array $beasts = null;

    private function beasts(): array
    {
        return $this->beasts ??= Data::beasts();
    }

    /** Where in the pen's current box the beast worth most is, when it is of a kind not in the party and worth more than the party's weakest; else null. */
    private function boxed(): ?int
    {
        $w = $this->w;
        if (count($w->party) < World::PARTY) {
            return null;
        }
        $best = null;
        foreach ($w->boxes[$w->box] as $i => $f) {
            if ($this->wants($f->level, $f->id) && ($best === null || $this->worth($f->level, $f->species) > $this->worth($w->boxes[$w->box][$best]->level, $w->boxes[$w->box][$best]->species))) {
                $best = $i;
            }
        }
        return $best;
    }

    /**
     * The pen is open: while its current box holds a beast to have instead of the party's weakest, the weakest is left
     * and that one taken; a box nearly full is changed for the next; then it leaves.
     */
    private function pen(): void
    {
        $w = $this->w;
        $at = fn() => $w->pen->cursor->at;
        $pick = function (int $entry, int $which, string $said) use ($w, $at): void {
            $this->cursorTo($entry, $at);
            $this->tick(confirm: true);
            $this->cursorTo($which, $at);
            $this->tick(confirm: true);
            if (!str_contains($w->pen->note ?? '', $said)) {
                throw new RuntimeException('the pen: ' . ($w->pen->note ?? 'nothing was said'));
            }
            $this->tick(confirm: true);
            $this->tick(cancel: true);
        };
        $entries = array_keys(Pen::ENTRIES);
        while (($i = $this->boxed()) !== null) {
            $pick(array_search('leave', $entries, true), $this->weakest(), '寄在了');
            $pick(array_search('take', $entries, true), $i, '带回了');
            $this->swapped++;
        }
        if (count($w->boxes[$w->box]) > Pen::BOX - 2) {
            if ($w->box + 1 >= Pen::BOXES) {
                throw new RuntimeException('every box of the pen is full');
            }
            $this->cursorTo(array_search('box', $entries, true), $at);
            $this->tick(confirm: true);
            $this->cursorTo($w->box + 1, $at);
            $this->tick(confirm: true);
        }
        $this->tick(cancel: true);
    }

    /**
     * The nearest map to look on for a beast to bind (wants()): one whose wild holds such a kind that it has not found
     * a seal unlikely to hold, and on which it has not yet fought three times for each of its seven places. Null when
     * there is nowhere to look.
     */
    private function near(): ?string
    {
        $near = null;
        foreach ($this->maps as $map) {
            $kinds = array_filter($map->wild['slots'] ?? [], fn(array $slot) => !in_array($slot[1], $this->passedOver, true) && $this->wants($slot[0], $slot[1]));
            if ($kinds !== [] && ($this->sought[$map->id] ?? 0) < 3 * count($map->wild['slots']) && ($route = $this->route($map->id)) !== null && ($near === null || count($route) < $near[1])) {
                $near = [$map->id, count($route)];
            }
        }
        return $near[0] ?? null;
    }

    /** Goes to $map looking for a beast to bind, and through one battle there. */
    private function seek(string $map): void
    {
        if ($this->spent()) {
            $this->mend();
        }
        $this->travel($map);
        $fought = $this->wild;
        $this->pace(fn() => $this->wild === $fought);
        $this->sought[$map] = ($this->sought[$map] ?? 0) + 1;
    }

    /** Walks between two tiles of the wild of the map it is on, its weakest first, while $goes says to and the party is not too worn. */
    private function pace(callable $goes): void
    {
        $map = $this->w->map;
        // two tiles side by side where wild beasts are, to walk between
        $pair = null;
        for ($y = 0; $y < $map->height && $pair === null; $y++) {
            for ($x = 0; $x + 1 < $map->width; $x++) {
                if (Tile::wild($map->tile($x, $y)) && Tile::wild($map->tile($x + 1, $y)) && $this->path($x, $y, false) !== null) {
                    $pair = [$x, $y];
                    break;
                }
            }
        }
        if ($pair === null) {
            throw new RuntimeException("{$map->id}: no two tiles of its wild side by side can be come to");
        }
        $this->arrange(true);
        $this->goTile($pair[0], $pair[1], false);
        while ($goes() && !$this->spent()) {
            $fought = $this->wild;
            $this->stepDir($this->w->player->x === $pair[0] ? Dir::Right : Dir::Left);
            if ($this->w->map !== $map) {
                throw new RuntimeException('left the map while walking between two of its tiles');
            }
            if ($this->wild !== $fought) {
                $this->arrange(true);
            }
        }
    }

    /** Has the party made whole at the nearest place that does it, when it is not. */
    private function mend(): void
    {
        $whole = true;
        foreach ($this->w->party as $f) {
            $whole = $whole && $f->hp === $f->maxHp && $f->status === null && array_filter($f->moves, fn(array $m) => $m['pp'] < $this->moves[$m['id']]['pp']) === [];
        }
        if ($whole) {
            return;
        }
        if (!$this->visit('rest')) {
            throw new RuntimeException('nowhere that can be come to makes the party whole');
        }
    }

    /** How many seals it buys at once, when it can pay for them. */
    private const SEALS = 10;

    /** Goes for seals to the nearest who sells them; false when it cannot pay for one, or no one that can be come to sells. */
    private function supply(): bool
    {
        return $this->w->money >= $this->price() && $this->visit('shop');
    }

    private ?int $price = null;

    private function price(): int
    {
        return $this->price ??= Data::items()['seal']['price'];
    }

    /** Buying is open: it buys the seals it can pay for, SEALS at most, and leaves. */
    private function buy(): void
    {
        $shop = $this->w->shop;
        $n = min(self::SEALS, intdiv($this->w->money, $this->price()));
        if ($shop->note !== null || $n === 0 || ($this->w->items['seal'] ?? 0) > 0) {
            $this->tick(confirm: $shop->note !== null, cancel: $shop->note === null);
            return;
        }
        $this->tick(confirm: true);
        for ($i = 1; $i < $n; $i++) {
            $this->tick(Dir::Up);
            $this->tick();
        }
        $this->tick(confirm: true);
    }

    /** Goes to the nearest person or sign whose script, as the game stands, comes to a command of $kind, and talks it there. */
    private function visit(string $kind): bool
    {
        $places = [];
        foreach ($this->maps as $map) {
            foreach ([...array_map(fn(array $n) => [$n[1], $n[2], $n[5]], $map->npcs), ...array_map(fn(array $s) => [$s[0], $s[1], $s[2]], $map->signs)] as [$x, $y, $script]) {
                if (($found = $this->reaches($script, $kind)) !== null && ($route = $this->route($map->id)) !== null) {
                    $places[] = [count($route), $map->id, $x, $y, $found[1]];
                }
            }
        }
        if ($places === []) {
            return false;
        }
        sort($places);
        [, $map, $x, $y, $answers] = $places[0];
        $this->travel($map);
        $this->talk($x, $y, $answers);
        return true;
    }

    // ---- the maps between ----

    /**
     * How a script comes to a command of $kind as the flags stand: the choices to make at its questions, the first
     * that lead there; null when none do before it ends or begins a battle.
     * @return ?array{0: array, 1: list<int>} the command, and the answers
     */
    private function reaches(array $script, string $kind, int $pc = 0, array $answers = [], int $depth = 0): ?array
    {
        $labels = [];
        foreach ($script as $i => $cmd) {
            if ($cmd[0] === 'label') {
                $labels[$cmd[1]] = $i;
            }
        }
        for ($n = 0; $pc < count($script) && $n < 200; $pc++, $n++) {
            $cmd = $script[$pc];
            if ($cmd[0] === $kind) {
                return [$cmd, $answers];
            }
            if ($cmd[0] === 'ask') {
                foreach ($depth < 6 ? $cmd[2] : [] as $i => [, $label]) {
                    if (($found = $this->reaches($script, $kind, $labels[$label], [...$answers, $i], $depth + 1)) !== null) {
                        return $found;
                    }
                }
                return null;
            }
            $set = in_array($cmd[0], ['ifFlag', 'ifNotFlag'], true) && isset($this->w->flags[$cmd[1]]);
            $pc = match (true) {
                $cmd[0] === 'ifFlag' && $set, $cmd[0] === 'ifNotFlag' && !$set => $labels[$cmd[2]],
                $cmd[0] === 'goto' => $labels[$cmd[1]],
                in_array($cmd[0], ['end', 'trial', 'battle', 'warp'], true) => count($script),
                default => $pc,
            };
        }
        return null;
    }

    /** The ways out of a map as the flags stand. @return list<array> [to, how, ...]: an exit's direction; a warp's or a person's place, and for a person the answers to give */
    private function ways(Map $map): array
    {
        $out = [];
        foreach ($map->exits as $dir => [$to]) {
            $out[] = [$to, 'exit', Dir::from($dir)];
        }
        foreach ($map->warps as $warp) {
            $out[] = [$warp[2], 'warp', $warp[0], $warp[1]];
        }
        foreach ($map->npcs as $npc) {
            if (($warp = $this->reaches($npc[5], 'warp')) !== null) {
                $out[] = [$warp[0][1], 'npc', $npc[1], $npc[2], $warp[1]];
            }
        }
        return $out;
    }

    /** The ways to take from the map the player is on to map $to; null when there is none. @return ?list<array> */
    private function route(string $to): ?array
    {
        $from = [$this->w->map->id => null];
        for ($queue = [$this->w->map->id]; $queue !== [];) {
            $at = array_shift($queue);
            if ($at === $to) {
                $route = [];
                for (; $from[$at] !== null; $at = $from[$at][0]) {
                    array_unshift($route, $from[$at][1]);
                }
                return $route;
            }
            foreach ($this->ways($this->maps[$at]) as $way) {
                if (!array_key_exists($way[0], $from)) {
                    $from[$way[0]] = [$at, $way];
                    $queue[] = $way[0];
                }
            }
        }
        return null;
    }

    /** Whether map $to can be come to from where the player is, as the flags stand. */
    public function reach(string $to): bool
    {
        return $this->route($to) !== null;
    }

    private function travel(string $to): void
    {
        if ($this->w->party !== [] && $this->w->map->id !== $to) {
            $this->arrange(true); // those on the road are met as the wild are, so that all the party grows by them
        }
        while ($this->w->map->id !== $to) {
            $route = $this->route($to) ?? throw new RuntimeException("no way from {$this->w->map->id} to $to");
            [$next, $how] = $route[0];
            $map = $this->w->map;
            if ($how === 'npc') {
                $this->talk($route[0][2], $route[0][3], $route[0][4]);
            } elseif ($how === 'warp') {
                $this->goTile($route[0][2], $route[0][3], true);
            } else {
                $this->leave($route[0][2], $this->maps[$next]);
            }
            if ($this->w->map === $map) {
                throw new RuntimeException("{$map->id}: its way to $next was taken and the player is still on it");
            }
        }
    }

    /** Walks off the map's edge to the map beside it, by a tile of the edge that can be come to and has ground beyond. */
    private function leave(Dir $dir, Map $to): void
    {
        $map = $this->w->map;
        [, $offset] = $map->exits[$dir->value];
        $along = $dir->dx() !== 0 ? $map->height : $map->width;
        for ($i = 0; $i < $along; $i++) {
            [$x, $y] = $dir->dx() !== 0 ? [$dir === Dir::Right ? $map->width - 1 : 0, $i] : [$i, $dir === Dir::Down ? $map->height - 1 : 0];
            [$tx, $ty] = $dir->dx() !== 0 ? [$dir === Dir::Right ? 0 : $to->width - 1, $i + $offset] : [$i + $offset, $dir === Dir::Down ? 0 : $to->height - 1];
            if ($to->walkable($tx, $ty) && $this->path($x, $y, true) !== null) {
                $this->goTile($x, $y, true);
                $this->stepDir($dir);
                return;
            }
        }
        throw new RuntimeException("{$map->id}: no tile of its {$dir->value} edge can be come to and crossed");
    }

    // ---- on a map ----

    /**
     * The steps from where the player stands to $x,$y; null when there are none. $shy keeps off the tiles where wild
     * beasts are, when there is a way that does.
     * @return ?list<Dir>
     */
    private function path(int $x, int $y, bool $shy): ?array
    {
        $w = $this->w;
        $map = $w->map;
        foreach ($shy ? [true, false] : [false] as $off) {
            $start = [$w->player->x, $w->player->y];
            $from = [$start[0] . ',' . $start[1] => null];
            for ($queue = [$start]; $queue !== [];) {
                [$ax, $ay] = array_shift($queue);
                if ($ax === $x && $ay === $y) {
                    $steps = [];
                    for ($k = "$ax,$ay"; $from[$k] !== null; $k = $from[$k][0]) {
                        array_unshift($steps, $from[$k][1]);
                    }
                    return $steps;
                }
                foreach (Dir::cases() as $d) {
                    [$nx, $ny] = [$ax + $d->dx(), $ay + $d->dy()];
                    $wild = $map->wild !== null && $map->inside($nx, $ny) && Tile::wild($map->tile($nx, $ny));
                    if (!array_key_exists("$nx,$ny", $from) && $w->free($nx, $ny) && !($off && $wild) && ($map->warpAt($nx, $ny) === null || ($nx === $x && $ny === $y))) {
                        $from["$nx,$ny"] = ["$ax,$ay", $d];
                        $queue[] = [$nx, $ny];
                    }
                }
            }
        }
        return null;
    }

    private function goTile(int $x, int $y, bool $shy): void
    {
        $map = $this->w->map;
        while ($this->w->map === $map && ($this->w->player->x !== $x || $this->w->player->y !== $y)) {
            $steps = $this->path($x, $y, $shy) ?? throw new RuntimeException("{$map->id}: no way to $x,$y from {$this->w->player->x},{$this->w->player->y}");
            $this->stepDir($steps[0]);
        }
    }

    /** One step that way, and whatever it sets off. */
    private function stepDir(Dir $dir): void
    {
        $w = $this->w;
        [$map, $x, $y] = [$w->map, $w->player->x, $w->player->y];
        for ($i = 0; $w->map === $map && $w->player->x === $x && $w->player->y === $y && !$w->busy() && $w->battle === null; $i++) {
            if ($i > 4 * World::WALK_STEPS) {
                throw new RuntimeException("{$map->id}: held {$dir->value} at $x,$y and did not move");
            }
            $this->tick($dir);
        }
        $this->tick();
        $this->settle();
    }

    /** Stands beside what is at $x,$y, faces it and talks, answering with $answers and then with the first choice. */
    private function talk(int $x, int $y, array $answers): void
    {
        $w = $this->w;
        foreach (Dir::cases() as $d) {
            [$sx, $sy] = [$x - $d->dx(), $y - $d->dy()];
            $here = $w->player->x === $sx && $w->player->y === $sy;
            if ($here || ($w->free($sx, $sy) && $this->path($sx, $sy, true) !== null)) {
                $this->goTile($sx, $sy, true);
                if ($w->player->dir !== $d) {
                    $this->tick($d);
                    $this->tick();
                }
                $this->answers = $answers;
                $this->tick(confirm: true);
                if (!$w->busy() && $w->battle === null) {
                    throw new RuntimeException("{$w->map->id}: nothing at $x,$y answers");
                }
                $this->settle();
                return;
            }
        }
        throw new RuntimeException("{$w->map->id}: no tile beside $x,$y can be come to");
    }

    // ---- what holds the controls ----

    /** @var list<string> the kinds met that a seal would not likely hold even worn down: not looked for */
    private array $passedOver = [];
    /** @var array<string, int> by map: how often it has gone there looking for a beast to bind */
    private array $sought = [];
    /** The battle in which the beast first sent has been called back for the strongest already. */
    private ?object $relieved = null;

    /** @var list<int> the choices to make at the questions to come */
    private array $answers = [];

    /** Goes through whatever holds the controls: lines, questions, scripted walks, battles. */
    private function settle(): void
    {
        $w = $this->w;
        while ($w->battle !== null || $w->busy()) {
            if ($w->battle !== null) {
                $this->battle();
            } elseif ($w->choices !== null) {
                $this->cursorTo(array_shift($this->answers) ?? 0, fn() => $w->choiceCursor?->at);
                $this->tick(confirm: true);
            } elseif ($w->dialog !== null) {
                $this->tick(confirm: true);
            } elseif ($w->shop !== null) {
                $this->buy();
            } elseif ($w->pen !== null) {
                $this->pen();
            } else {
                $this->tick();
            }
        }
    }

    private function battle(): void
    {
        $s = $this->w->battle;
        $b = $s->battle;
        $trial = $s->trial !== null;
        [$me, $foe] = [$b->fighter(0), $b->fighter(1)];
        // the strongest of the others that can fight, to send in
        $relief = null;
        foreach ($b->parties[0] as $i => $f) {
            if (!$f->fainted() && $i !== $b->active[0] && ($relief === null || $f->level > $b->parties[0][$relief]->level)) {
                $relief = $i;
            }
        }
        // a wild beast to bind: one it wants (wants()), while it has a seal, and one that worn down to a third a seal
        // has one chance in ten or better to hold (docs/numbers.md 8): the ten seals it buys at a time are then enough, more often than not
        $odds = fn(int $hp) => Formulas::catchPercent(Formulas::catchValue($foe->species['catch'], $foe->maxHp, $hp, $foe->status)) >= 10;
        $binds = !$trial && ($this->w->items['seal'] ?? 0) > 0 && $this->wants($foe->level, $foe->id) && $odds(max(1, intdiv($foe->maxHp, 3)));
        // the hardest blow that would leave it standing, to wear it down
        $gentle = null;
        foreach ($b->usable(0) as $i) {
            $e = $b->estimate($me, $foe, $i);
            if ($e > 0 && $e < $foe->hp && ($gentle === null || $e > $b->estimate($me, $foe, $gentle))) {
                $gentle = $i;
            }
        }
        if (!$trial && !$odds(max(1, intdiv($foe->maxHp, 3))) && !in_array($foe->id, $this->passedOver, true)) {
            $this->passedOver[] = $foe->id;
        }
        $throws = $binds && (3 * $foe->hp <= $foe->maxHp || $gentle === null); // one it cannot wear down without felling it, it tries as it is
        if ($s->phase === 'menu') {
            $keys = array_keys($s::MENU);
            if ($throws) {
                $this->cursorTo(array_search('bag', $keys, true), fn() => $s->cursor->at);
            } elseif (!$binds && $this->relieved !== $s && $relief !== null && $me->level < $foe->level && $b->parties[0][$relief]->level > $me->level) {
                // outmatched: it is called back for the strongest, and both will have fought
                $this->relieved = $s;
                $this->cursorTo(array_search('party', $keys, true), fn() => $s->cursor->at);
            }
        } elseif ($s->phase === 'moves') {
            $pick = $binds ? $gentle : null;
            foreach ($pick === null ? $b->usable(0) : [] as $i) {
                if ($pick === null || $b->estimate($me, $foe, $i) > $b->estimate($me, $foe, $pick)) {
                    $pick = $i;
                }
            }
            $this->cursorTo($pick ?? 0, fn() => $s->cursor->at);
        } elseif ($s->phase === 'party') {
            $this->cursorTo($relief ?? throw new RuntimeException('the party\'s list is open in a battle and no other beast can fight'), fn() => $s->cursor->at);
        } elseif ($s->phase === 'bag') {
            if (!$throws) {
                throw new RuntimeException('the bag was opened in a battle with nothing to bind');
            }
            $this->cursorTo(array_search('seal', array_keys($this->w->items), true), fn() => $s->cursor->at);
        } elseif ($s->phase === 'forget') {
            // the new move takes the place of the weakest known, when it is stronger than that
            $items = $s->items();
            $new = null;
            foreach ($this->moves as $m) {
                $new = '不学' . $m['name'] === end($items) ? $m['power'] : $new;
            }
            $known = array_slice($items, 0, -1);
            $powers = array_map(fn(string $name) => array_values(array_filter($this->moves, fn(array $m) => $m['name'] === $name))[0]['power'], $known);
            $weakest = array_search(min($powers), $powers, true);
            $this->cursorTo($new > $powers[$weakest] ? $weakest : count($known), fn() => $s->cursor->at);
        }
        $this->tick(confirm: true);
        if ($this->w->battle === null) {
            $this->wild += $trial ? 0 : 1;
            if ($this->w->lastBattle === 'lost') {
                $this->lost++;
                $this->roadLost = $trial && $this->trying === null;
                throw new Woke();
            }
        }
    }

    /** Presses up or down until the cursor $at reads is on $to. */
    private function cursorTo(int $to, callable $at): void
    {
        for ($i = 0; $at() !== null && $at() !== $to; $i++) {
            if ($i > 20) {
                throw new RuntimeException("the cursor does not come to $to");
            }
            $this->tick($at() < $to ? Dir::Down : Dir::Up);
            $this->tick();
        }
    }

    private function tick(?Dir $dir = null, bool $confirm = false, bool $cancel = false, bool $menu = false): void
    {
        if (++$this->updates > self::LIMIT) {
            throw new RuntimeException('the journey goes on past any it should: at ' . $this->w->map->id);
        }
        $this->w->step(new Controls($dir, $confirm, $cancel, $menu));
    }
}

/** A battle was lost and the player woke elsewhere. */
final class Woke extends RuntimeException
{
}
