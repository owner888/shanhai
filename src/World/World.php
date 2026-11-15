<?php
declare(strict_types=1);

namespace Shanhai\World;

use InvalidArgumentException;
use LogicException;
use Shanhai\Battle\{Battle, Fighter, Formulas, Rng, Screen};
use Shanhai\{Cursor, Save};
use Shanhai\Script\Vm;

/**
 * The game's state, advanced one update at a time (Play runs 60 a second), with no drawing: the map the player
 * is on, who stands where, the flags, the party, the script or dialog that holds the controls, the battle a script
 * began, which holds them until it is over, and the party's screen, which the menu button opens.
 */
final class World
{
    /** Updates one step between two tiles takes. */
    public const WALK_STEPS = 16;
    /** Updates a direction is held, facing another way, before the player steps: a tap only turns. */
    public const TURN_STEPS = 4;
    /** The most beasts a party holds. */
    public const PARTY = 6;

    public Map $map;
    public Actor $player;
    /** @var array<string, Actor> by id, those of the map the player is on */
    public array $npcs = [];
    /** @var array<string, true> */
    public array $flags = [];
    /** @var array<string, int> item id => how many */
    public array $items = [];
    /** @var list<Fighter> the beasts with the player, as their battles have left them; the first begins a battle */
    public array $party = [];
    /** The menu the menu button opens; null when it is not open. */
    public ?Menu $menu = null;
    /** The line the dialog box shows, which confirm closes; null when there is none. */
    public ?string $dialog = null;
    /** @var ?list<string> what a script asks the player to choose among, shown with the dialog's line; null when it asks nothing */
    public ?array $choices = null;
    /** Where the cursor is among $choices. */
    public ?Cursor $choiceCursor = null;
    /** Which of the choices the player last took: the script that asked reads it. */
    public ?int $chosen = null;
    /** The battle going on, which a script's 'battle' began; null when there is none. */
    public ?Screen $battle = null;
    /** won, lost or fled: how the last battle ended. */
    public ?string $lastBattle = null;
    /** Counts the maps entered, for what draws the world to notice a new one. */
    public int $mapChanges = 0;

    private ?Vm $vm = null;
    private int $turnWait = 0;
    /** Steps still to take before one can meet a wild beast (docs/numbers.md 13). */
    private int $wildWait = 0;

    /**
     * @param array<string, Map> $maps by id
     * @param array{beasts: array, learnsets: array, moves: array, elements: array, items: array, rest: array}|null $book data/'s tables, which
     *        beasts and battles need, and where the player wakes after losing (data/game.php's rest)
     * @param ?Rng $rng the battles' dice
     * @param ?string $saveFile the file the game is saved to and loaded from; null for a game that cannot be saved
     */
    public function __construct(private readonly array $maps, string $map, int $x, int $y, Dir $dir, private readonly ?array $book = null, private readonly ?Rng $rng = null, private readonly ?string $saveFile = null)
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
            if ($this->battle->caught !== null) {
                $this->party[] = $this->battle->caught;
            }
            $this->battle = null;
            $this->wildWait = Formulas::WILD_WAIT;
            foreach ($this->party as $f) {
                $f->leave();
            }
            if ($this->lastBattle === 'lost') {
                $this->wake();
                return;
            }
            $c = new Controls(); // the press that closed the battle's last line does nothing else
        }
        if ($this->menu !== null) {
            $this->menu->step($c, $this->party, $this->items);
            if ($this->menu->done) {
                $saves = $this->menu->saves;
                $this->menu = null;
                if ($saves) {
                    $this->save();
                    $this->run([['say', '存档完成。']]);
                }
            }
            return;
        }
        if ($this->vm !== null) {
            if ($this->choices !== null) {
                // a question is answered by choosing: the line stays until then, and cancel does not close it
                if (!$this->choiceCursor->move($c, count($this->choices)) && $c->confirm) {
                    [$this->chosen, $this->choices, $this->choiceCursor, $this->dialog] = [$this->choiceCursor->at, null, null, null];
                }
            } elseif ($this->dialog !== null && $c->confirm) {
                $this->dialog = null;
            }
            $this->vm->run($this);
            if ($this->vm->done) {
                $this->vm = null;
            }
        } elseif (!$this->player->moving) {
            if ($c->menu) {
                $this->menu = new Menu($this->saveFile !== null);
            } elseif ($c->confirm) {
                $this->interact();
            } elseif ($c->dir === null) {
                $this->turnWait = 0;
            } elseif ($c->dir !== $this->player->dir) {
                $this->player->dir = $c->dir;
                $this->turnWait = self::TURN_STEPS;
            } elseif ($this->turnWait > 0) {
                $this->turnWait--;
            } elseif (!$this->cross($c->dir)) {
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

    /**
     * The player walks off the map's edge: when the map has an exit that way, and the tile it comes to on the other
     * map can be walked on, the player is there. False when there is nothing to cross to.
     */
    private function cross(Dir $dir): bool
    {
        $p = $this->player;
        [$x, $y] = [$p->x + $dir->dx(), $p->y + $dir->dy()];
        $exit = $this->map->exits[$dir->value] ?? null;
        if ($exit === null || $this->map->inside($x, $y)) {
            return false;
        }
        [$id, $offset] = $exit;
        $to = $this->maps[$id] ?? throw new InvalidArgumentException("no map '$id'");
        [$tx, $ty] = match ($dir) {
            Dir::Right => [0, $y + $offset],
            Dir::Left => [$to->width - 1, $y + $offset],
            Dir::Down => [$x + $offset, 0],
            Dir::Up => [$x + $offset, $to->height - 1],
        };
        if (!$to->walkable($tx, $ty)) {
            return false;
        }
        $this->enter($id, $tx, $ty, $dir);
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
        $this->wildWait = Formulas::WILD_WAIT;
    }

    /** A beast of $level as data/ makes it. */
    private function beast(string $id, int $level): Fighter
    {
        $b = $this->book ?? throw new LogicException('this world was made without the tables beasts and battles need');
        return new Fighter($id, $b['beasts'][$id] ?? throw new InvalidArgumentException("no beast '$id'"), $level, $b['learnsets'][$id], $b['moves']);
    }

    /** A beast of $level joins the party. */
    public function join(string $beast, int $level): void
    {
        if (count($this->party) >= self::PARTY) {
            throw new LogicException('the party is full');
        }
        $this->party[] = $this->beast($beast, $level);
    }

    /** The party rests: every beast whole again. */
    public function rest(): void
    {
        foreach ($this->party as $f) {
            $f->restore($this->book['moves']);
        }
    }

    /** Begins a battle with a wild $beast of $level: the party's beasts come to it as they are, and it leaves them as it ends. */
    public function fight(string $beast, int $level): void
    {
        if ($this->rng === null) {
            throw new LogicException('this world was made without the dice a battle needs');
        }
        if (array_filter($this->party, fn(Fighter $f) => !$f->fainted()) === []) {
            throw new LogicException('a battle with no beast in the party that can fight');
        }
        $wild = $this->beast($beast, $level);
        $b = $this->book;
        $this->battle = new Screen(new Battle([$this->party, [$wild]], $b['elements'], $b['moves'], $this->rng, [true, false]), $b['moves'], $this->rng, $b['items'], $this->items);
    }

    /** The battle was lost: the script that began it is dropped, and the player wakes where data/game.php rests them, the party whole. */
    private function wake(): void
    {
        $this->vm = null;
        [$this->dialog, $this->choices, $this->choiceCursor] = [null, null, null];
        $this->rest();
        $r = $this->book['rest'];
        $this->enter($r['map'], $r['x'], $r['y'], Dir::from($r['dir']));
        $this->run($r['script']);
    }

    /** What a saved game keeps: where the player is, the flags, the bag, the party. Asked for when nothing holds the controls. */
    public function state(): array
    {
        $p = $this->player;
        return ['map' => $this->map->id, 'x' => $p->x, 'y' => $p->y, 'dir' => $p->dir->value, 'flags' => array_keys($this->flags),
            'items' => $this->items, 'party' => array_map(fn(Fighter $f) => $f->state(), $this->party)];
    }

    /** Puts the world as a saved game kept it (state()). */
    public function restore(array $s): void
    {
        $b = $this->book ?? throw new LogicException('this world was made without the tables a saved game needs');
        $party = array_map(fn(array $f) => Fighter::fromState($f, $b['beasts'], $b['learnsets'], $b['moves']), $s['party']);
        $map = $this->maps[$s['map']] ?? throw new InvalidArgumentException("the saved game is on a map there is none of: '{$s['map']}'");
        if (count($party) > self::PARTY || !$map->walkable($s['x'], $s['y']) || array_diff_key($s['items'], $b['items']) !== []) {
            throw new InvalidArgumentException('the saved game is not as a game can be');
        }
        [$this->flags, $this->items, $this->party] = [array_fill_keys($s['flags'], true), $s['items'], $party];
        $this->enter($s['map'], $s['x'], $s['y'], Dir::from($s['dir']));
    }

    /** Saves the game to its file. */
    public function save(): void
    {
        Save::write($this->saveFile ?? throw new LogicException('this world has no file to save to'), $this->state());
    }

    /** Loads the game its file keeps. */
    public function load(): void
    {
        $file = $this->saveFile ?? throw new LogicException('this world has no file to load from');
        $this->restore(Save::read($file) ?? throw new LogicException("there is no saved game at $file"));
    }

    /** A script asks: $line shows, with $choices to choose among. */
    public function ask(string $line, array $choices): void
    {
        [$this->dialog, $this->choices, $this->choiceCursor, $this->chosen] = [$line, $choices, new Cursor(), null];
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

    /** The player has stepped onto a tile: its warp, else its trigger, else what its grass may hold. */
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
            return;
        }
        $this->grass();
    }

    /**
     * A step taken in tall grass may meet a wild beast (docs/numbers.md 13): not on the first steps after entering the
     * map or a battle; then a byte under the map's rate; then which of its seven. With no beast that can fight, none is met.
     */
    private function grass(): void
    {
        $wild = $this->map->wild;
        if ($wild === null || Tile::name($this->map->tile($this->player->x, $this->player->y)) !== 'tallGrass'
            || array_filter($this->party, fn(Fighter $f) => !$f->fainted()) === []) {
            return;
        }
        if ($this->wildWait > 0 && --$this->wildWait > 0) {
            return;
        }
        $rng = $this->rng ?? throw new LogicException('this world was made without the dice wild beasts need');
        if ($rng->byte() >= Formulas::wildLimit($wild['rate'])) {
            return;
        }
        [$level, $beast] = $wild['slots'][Formulas::wildSlot($rng)];
        $this->fight($beast, $level);
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
