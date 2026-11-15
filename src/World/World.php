<?php
declare(strict_types=1);

namespace Shanhai\World;

use InvalidArgumentException;
use LogicException;
use Shanhai\Battle\{Battle, Fighter, Rng, Screen};
use Shanhai\Script\Vm;

/**
 * The game's state, advanced one update at a time (Play runs 60 a second), with no drawing: the map the player
 * is on, who stands where, the flags, the script or dialog that holds the controls, and the battle a script began,
 * which holds them until it is over.
 */
final class World
{
    /** Updates one step between two tiles takes. */
    public const WALK_STEPS = 16;
    /** Updates a direction is held, facing another way, before the player steps: a tap only turns. */
    public const TURN_STEPS = 4;

    public Map $map;
    public Actor $player;
    /** @var array<string, Actor> by id, those of the map the player is on */
    public array $npcs = [];
    /** @var array<string, true> */
    public array $flags = [];
    /** @var array<string, int> item id => how many */
    public array $items = [];
    /** @var list<array{id: string, level: int}> */
    public array $party = [];
    /** The line the dialog box shows, which confirm closes; null when there is none. */
    public ?string $dialog = null;
    /** The battle going on, which a script's 'battle' began; null when there is none. */
    public ?Screen $battle = null;
    /** won, lost or fled: how the last battle ended. */
    public ?string $lastBattle = null;
    /** Counts the maps entered, for what draws the world to notice a new one. */
    public int $mapChanges = 0;

    private ?Vm $vm = null;
    private int $turnWait = 0;

    /**
     * @param array<string, Map> $maps by id
     * @param array{beasts: array, learnsets: array, moves: array, elements: array}|null $book data/'s tables, which a battle needs
     * @param ?Rng $rng the battles' dice
     */
    public function __construct(private readonly array $maps, string $map, int $x, int $y, Dir $dir, private readonly ?array $book = null, private readonly ?Rng $rng = null)
    {
        $this->player = new Actor('player', $x, $y, $dir, 'player');
        $this->enter($map, $x, $y, $dir);
    }

    public function busy(): bool
    {
        return $this->vm !== null;
    }

    public function actor(string $id): Actor
    {
        if ($id === 'player') {
            return $this->player;
        }
        return $this->npcs[$id] ?? throw new InvalidArgumentException("no actor '$id' on map {$this->map->id}");
    }

    public function step(Controls $c): void
    {
        if ($this->battle !== null) {
            $this->battle->step($c);
            if (!$this->battle->done) {
                return;
            }
            $this->lastBattle = $this->battle->outcome;
            $this->battle = null;
            $c = new Controls(); // the press that closed the battle's last line does nothing else
        }
        if ($this->vm !== null) {
            if ($this->dialog !== null && $c->confirm) {
                $this->dialog = null;
            }
            $this->vm->run($this);
            if ($this->vm->done) {
                $this->vm = null;
            }
        } elseif (!$this->player->moving) {
            if ($c->confirm) {
                $this->interact();
            } elseif ($c->dir === null) {
                $this->turnWait = 0;
            } elseif ($c->dir !== $this->player->dir) {
                $this->player->dir = $c->dir;
                $this->turnWait = self::TURN_STEPS;
            } elseif ($this->turnWait > 0) {
                $this->turnWait--;
            } else {
                $this->walk($this->player, $c->dir);
            }
        }
        // Those walking go on, a step begun in this update counting it: a tile takes WALK_STEPS updates, begun standing or walking.
        foreach ([$this->player, ...array_values($this->npcs)] as $a) {
            if ($a->moving && ++$a->progress >= self::WALK_STEPS) {
                [$a->x, $a->y, $a->moving, $a->progress] = [$a->toX, $a->toY, false, 0];
                if ($a === $this->player && $this->vm === null) {
                    $this->arrived();
                }
            }
        }
    }

    /** Turns $a to $dir and starts its step there; false, the actor only turned, when the tile cannot be entered. */
    public function walk(Actor $a, Dir $dir): bool
    {
        $a->dir = $dir;
        [$x, $y] = [$a->x + $dir->dx(), $a->y + $dir->dy()];
        if (!$this->free($x, $y)) {
            return false;
        }
        [$a->toX, $a->toY, $a->moving, $a->progress] = [$x, $y, true, 0];
        return true;
    }

    /** Whether an actor can step onto the tile: walkable, with no one and no sign on it. */
    public function free(int $x, int $y): bool
    {
        if (!$this->map->walkable($x, $y) || $this->map->signAt($x, $y) !== null) {
            return false;
        }
        foreach ([$this->player, ...array_values($this->npcs)] as $a) {
            if ($a->occupies($x, $y)) {
                return false;
            }
        }
        return true;
    }

    /** Puts the player on another map, or elsewhere on this one. */
    public function enter(string $map, int $x, int $y, Dir $dir): void
    {
        $this->map = $this->maps[$map] ?? throw new InvalidArgumentException("no map '$map'");
        [$this->player->x, $this->player->y, $this->player->dir] = [$x, $y, $dir];
        [$this->player->moving, $this->player->progress] = [false, 0];
        $this->npcs = [];
        foreach ($this->map->npcs as [$id, $nx, $ny, $ndir, $look]) {
            $this->npcs[$id] = new Actor($id, $nx, $ny, Dir::from($ndir), $look);
        }
        $this->mapChanges++;
    }

    /**
     * Begins a battle with a wild $beast of $level. The party's beasts come to it as their level makes them, whole:
     * what a battle leaves of them is kept from the party's screen on (roadmap phase 2).
     */
    public function fight(string $beast, int $level): void
    {
        if ($this->book === null || $this->rng === null) {
            throw new LogicException('this world was made without the tables and dice a battle needs');
        }
        if ($this->party === []) {
            throw new LogicException('a battle with no beast in the party');
        }
        $b = $this->book;
        $fighter = fn(string $id, int $level) => new Fighter($id, $b['beasts'][$id] ?? throw new InvalidArgumentException("no beast '$id'"), $level, $b['learnsets'][$id], $b['moves']);
        $mine = array_map(fn(array $p) => $fighter($p['id'], $p['level']), $this->party);
        $this->battle = new Screen(new Battle([$mine, [$fighter($beast, $level)]], $b['elements'], $b['moves'], $this->rng), $b['moves'], $this->rng);
    }

    /** Runs a script, which holds the controls until it ends. */
    public function run(array $script): void
    {
        $this->vm = new Vm($script);
        $this->vm->run($this);
        if ($this->vm->done) {
            $this->vm = null;
        }
    }

    /** The player has stepped onto a tile: its warp, else its trigger. */
    private function arrived(): void
    {
        [$x, $y] = [$this->player->x, $this->player->y];
        if (($w = $this->map->warpAt($x, $y)) !== null) {
            $this->enter($w[2], $w[3], $w[4], Dir::from($w[5]));
            return;
        }
        if (($t = $this->map->triggerAt($x, $y)) !== null && !isset($this->flags[$t[2]])) {
            $this->flags[$t[2]] = true;
            $this->run($t[3]);
        }
    }

    /** Confirm, standing still: talks to who, or reads what, the player faces. */
    private function interact(): void
    {
        $p = $this->player;
        [$x, $y] = [$p->x + $p->dir->dx(), $p->y + $p->dir->dy()];
        foreach ($this->npcs as $npc) {
            if (!$npc->moving && $npc->x === $x && $npc->y === $y) {
                $npc->dir = $p->dir->opposite();
                foreach ($this->map->npcs as $def) {
                    if ($def[0] === $npc->id) {
                        $this->run($def[5]);
                    }
                }
                return;
            }
        }
        if (($s = $this->map->signAt($x, $y)) !== null) {
            $this->run($s[2]);
        }
    }
}
