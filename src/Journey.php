<?php
declare(strict_types=1);

namespace Shanhai;

use RuntimeException;
use Shanhai\World\{Controls, Dir, Map, Tile, World};

/**
 * A player with no hands: plays a game from its start through every trial there is, by the same World::step and the
 * same Controls a person's keys make, so that what it meets is what a person would. It walks the roads, takes the
 * first of what it is offered (but what a question must be answered to be taken somewhere or to rest), fights every wild beast that comes with the move that would do most, and goes to a
 * trial when its first beast is at the level it wants: 0 at first, so it tries at once, and two more than it had each
 * time it fails. Before a trial it has its beasts made whole at the nearest place that does so.
 *
 * It is for knowing that the game can be played through and what that takes (tools/journey.php), not for playing well:
 * it binds no beast, uses no move that does no damage, and never flees.
 */
final class Journey
{
    /** More updates than any journey should take: one that goes on past this is stuck. */
    private const LIMIT = 40_000_000;

    public int $updates = 0;
    public int $wild = 0;
    public int $lost = 0;
    /**
     * A line a trial passed: the level its first beast went into it at, counting from the start how often trials were
     * tried, wild battles, battles lost and updates.
     * @var list<array{range: int, who: string, tries: int, level: int, wild: int, lost: int, updates: int}>
     */
    public array $passed = [];
    /** @var array<int, int> by range: the level its first beast is to be at before the trial is tried */
    private array $want = [];
    /** @var array<int, int> by range: how often its trial was tried */
    private array $tries = [];
    private ?int $trying = null;
    /** @var list<array{range: int, map: string, npc: string, flag: string, who: string}> the trials, in the ranges' order */
    private array $trials = [];

    /**
     * @param array<string, Map> $maps
     * @param array<string, array> $moves data/moves.php
     * @param int $starter which of the three it takes
     */
    public function __construct(public readonly World $w, private readonly array $maps, private readonly array $moves, private readonly array $gods, private readonly int $starter = 0)
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
                    $this->want[$this->trying] = $this->w->party[0]->level + 2;
                    $this->trying = null;
                }
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
        if ($w->party[0]->level < ($this->want[$r] ?? 0)) {
            $this->grind($r);
            return true;
        }
        $this->mend();
        $this->travel($next['map']);
        $god = $w->actor($next['npc']);
        [$this->trying, $this->tries[$r], $level] = [$r, ($this->tries[$r] ?? 0) + 1, $w->party[0]->level];
        $this->talk($god->x, $god->y, []);
        $this->trying = null;
        if (!isset($w->flags[$next['flag']])) {
            throw new RuntimeException("the trial of range $r was talked through and neither passed nor lost");
        }
        $this->passed[] = ['range' => $r, 'who' => $next['who'], 'tries' => $this->tries[$r], 'level' => $level, 'wild' => $this->wild, 'lost' => $this->lost, 'updates' => $this->updates];
        return true;
    }

    /** Fights in the wild of range $r until the first beast is at the level wanted. */
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
        $this->goTile($pair[0], $pair[1], false);
        while ($this->w->party[0]->level < $this->want[$r]) {
            $this->stepDir($this->w->player->x === $pair[0] ? Dir::Right : Dir::Left);
            if ($this->w->map !== $map) {
                throw new RuntimeException('left the map while walking between two of its tiles');
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
        // who rests the party when talked to as the game stands: [map, x, y]
        $places = [];
        foreach ($this->maps as $map) {
            foreach ([...array_map(fn(array $n) => [$n[1], $n[2], $n[5]], $map->npcs), ...array_map(fn(array $s) => [$s[0], $s[1], $s[2]], $map->signs)] as [$x, $y, $script]) {
                if (($rest = $this->reaches($script, 'rest')) !== null && ($route = $this->route($map->id)) !== null) {
                    $places[] = [count($route), $map->id, $x, $y, $rest[1]];
                }
            }
        }
        if ($places === []) {
            throw new RuntimeException('nowhere that can be come to makes the party whole');
        }
        sort($places);
        [, $map, $x, $y, $answers] = $places[0];
        $this->travel($map);
        $this->talk($x, $y, $answers);
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
            $set = isset($this->w->flags[$cmd[1] ?? '']);
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
        if ($s->phase === 'moves') {
            [$me, $foe, $pick] = [$b->fighter(0), $b->fighter(1), null];
            foreach ($b->usable(0) as $i) {
                if ($pick === null || $b->estimate($me, $foe, $i) > $b->estimate($me, $foe, $pick)) {
                    $pick = $i;
                }
            }
            $this->cursorTo($pick ?? 0, fn() => $s->cursor->at);
        } elseif ($s->phase === 'party') {
            foreach ($b->parties[0] as $i => $f) {
                if (!$f->fainted() && $i !== $b->active[0]) {
                    $this->cursorTo($i, fn() => $s->cursor->at);
                    break;
                }
            }
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
        } elseif ($s->phase === 'bag') {
            throw new RuntimeException('the bag was opened in a battle, which this player never does');
        }
        $this->tick(confirm: true);
        if ($this->w->battle === null) {
            $this->wild += $trial ? 0 : 1;
            if ($this->w->lastBattle === 'lost') {
                $this->lost++;
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

    private function tick(?Dir $dir = null, bool $confirm = false): void
    {
        if (++$this->updates > self::LIMIT) {
            throw new RuntimeException('the journey goes on past any it should: at ' . $this->w->map->id);
        }
        $this->w->step(new Controls($dir, $confirm));
    }
}

/** A battle was lost and the player woke elsewhere. */
final class Woke extends RuntimeException
{
}
