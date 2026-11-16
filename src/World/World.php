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
    /** @var list<list<Fighter>> the pen's boxes: the beasts kept there, whole */
    public array $boxes;
    /** Which of the boxes is the current one: a beast bound with the party full goes there. */
    public int $box = 0;
    /** The record of the kinds of beast seen and bound (docs/numbers.md 24). */
    public Dex $dex;
    /** The money the player has, in cowries (贝). */
    public int $money = Formulas::START_MONEY;
    /** The one sold by, whom a script's 'shop' opens; null when nothing is being bought. */
    public ?Shop $shop = null;
    /** The pen's screen, which a script's 'pen' opens; null when it is not open. */
    public ?Pen $pen = null;
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
    /** Whether the player is on water, carried by a beast of the party (float()). */
    public bool $afloat = false;
    /** @var ?array{0: string, 1: int, 2: int, 3: string} where a script last made the party whole: map, place, facing; null before any */
    public ?array $restAt = null;
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
     * @param array{beasts: array, learnsets: array, moves: array, elements: array, items: array, gods: array, float: string, rest: array}|null $book data/'s tables, which
     *        beasts and battles need, and where the player wakes after losing (data/game.php's rest)
     * @param ?Rng $rng the battles' dice
     * @param ?string $saveFile the file the game is saved to and loaded from; null for a game that cannot be saved
     */
    public function __construct(private readonly array $maps, string $map, int $x, int $y, Dir $dir, private readonly ?array $book = null, private readonly ?Rng $rng = null, private readonly ?string $saveFile = null)
    {
        $this->player = new Actor('player', $x, $y, $dir, 'player');
        $this->boxes = Pen::emptied();
        $this->dex = new Dex();
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
            if ($this->lastBattle === 'won') {
                $this->money = min(Formulas::MAX_MONEY, $this->money + $this->battle->payout());
            }
            if (($caught = $this->battle->caught) !== null) {
                $this->dex->bind($caught->id);
                if ($this->battle->kin) { // docs/numbers.md 25: bound with the seal of kinship, it is close from the first
                    $caught->bond = Formulas::BOND_SEAL;
                }
                if (count($this->party) < self::PARTY) {
                    $this->party[] = $caught;
                } else { // to the pen's current box, first in it, whole as all kept there are (docs/numbers.md 16)
                    $caught->restore($this->book['moves']);
                    array_unshift($this->boxes[$this->box], $caught);
                }
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
        if ($this->pen !== null) {
            $this->pen->step($c, $this->party, $this->boxes, $this->box, $this->book['moves']);
            if ($this->pen->done) {
                $this->pen = null;
            }
            return;
        }
        if ($this->shop !== null) {
            $this->shop->step($c, $this->items, $this->money, $this->book['items']);
            if ($this->shop->done) {
                $this->shop = null;
            }
            return;
        }
        if ($this->menu !== null) {
            $this->menu->step($c, $this->party, $this->items, $this->book['items'] ?? [], $this->dex, $this->book ?? []);
            if ($this->menu->done) {
                $saves = $this->menu->saves;
                $this->menu = null;
                if ($saves && $this->afloat) {
                    $this->run([['say', '在水面上不能存档。']]);
                } elseif ($saves) {
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
        // the player, afloat, goes on over water, and comes ashore by stepping onto land
        if (!$this->free($x, $y) && !($a === $this->player && $this->afloat && $this->water($this->map, $x, $y))) {
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
        if (!$to->walkable($tx, $ty) && !($this->afloat && $this->water($to, $tx, $ty))) {
            return false;
        }
        $this->enter($id, $tx, $ty, $dir);
        return true;
    }

    private function water(Map $map, int $x, int $y): bool
    {
        return $map->inside($x, $y) && Tile::name($map->tile($x, $y)) === 'water';
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
        $this->afloat = $this->water($this->map, $x, $y); // afloat still, having crossed onto another map's water; else not
        $this->npcs = [];
        foreach ($this->map->npcs as [$id, $nx, $ny, $ndir, $look]) {
            $this->npcs[$id] = new Actor($id, $nx, $ny, Dir::from($ndir), $look);
        }
        $this->mapChanges++;
        $this->wildWait = Formulas::WILD_WAIT;
    }

    /**
     * What a wild beast of kind $id holds (docs/numbers.md 20): three times in four nothing; else, of the things that grow on
     * a mountain it lives on, the one that is kept on a byte under WILD_HOLDS_SECOND, and otherwise the one that is used up.
     * A kind with no such thing holds nothing. The rolls are made whatever the kind.
     */
    private function wildHolds(string $id): ?string
    {
        if ($this->rng->byte() < Formulas::WILD_HOLDS_NONE) {
            return null;
        }
        $second = $this->rng->byte() < Formulas::WILD_HOLDS_SECOND;
        $mountains = explode('、', $this->book['beasts'][$id]['mountain']);
        foreach ($this->book['items'] as $thing => $t) {
            if (isset($t['hold'], $t['grows']) && in_array($t['grows'], $mountains, true) && in_array($t['hold'], ['heal', 'cure', 'pp'], true) !== $second) {
                return $thing;
            }
        }
        return null;
    }

    /** A beast of $level as data/ makes it, or a god; holding $item, when one is given; with the individual values $dvs, or those in the middle. */
    private function beast(string $id, int $level, ?string $item = null, ?array $dvs = null): Fighter
    {
        $f = $this->make($id, $level, $dvs);
        if ($item !== null && !isset($this->book['items'][$item]['hold'])) {
            throw new InvalidArgumentException("'$item' is not a thing a beast can hold");
        }
        $f->item = $item;
        return $f;
    }

    private function make(string $id, int $level, ?array $dvs): Fighter
    {
        $b = $this->book ?? throw new LogicException('this world was made without the tables beasts and battles need');
        if (isset($b['gods'][$id])) { // a god fights as a beast does, knowing its moves from the first
            return new Fighter($id, $b['gods'][$id], $level, array_map(fn(string $move) => [1, $move], $b['gods'][$id]['moves']), $b['moves']);
        }
        $species = $b['beasts'][$id] ?? throw new InvalidArgumentException("no beast '$id'");
        return $dvs === null ? new Fighter($id, $species, $level, $b['learnsets'][$id], $b['moves']) : new Fighter($id, $species, $level, $b['learnsets'][$id], $b['moves'], $dvs);
    }

    /** A beast of $level joins the party. */
    public function join(string $beast, int $level): void
    {
        if (count($this->party) >= self::PARTY) {
            throw new LogicException('the party is full');
        }
        // docs/numbers.md 23: one given has individual values of its own, as one met in the wild has; a world made without dice gives the middle ones
        $this->party[] = $this->beast($beast, $level, null, $this->rng === null ? null : Formulas::dvs($this->rng->byte(), $this->rng->byte()));
        $this->dex->bind($beast);
    }

    /** The party rests: every beast whole again. */
    public function rest(): void
    {
        $this->mend();
        $p = $this->player;
        $this->restAt = [$this->map->id, $p->x, $p->y, $p->dir->value];
    }

    /** Every beast of the party is whole again. */
    private function mend(): void
    {
        foreach ($this->party as $f) {
            $f->restore($this->book['moves']);
        }
    }

    /**
     * Begins a trial: a battle against the beasts of the one who sets it, in their order, which cannot be fled nor
     * the beasts bound.
     * @param list<array{0: string, 1: int, 2?: string}> $theirs [beast or god, level], and what it holds when it holds a thing
     */
    public function trial(string $who, array $theirs, array $uses = []): void
    {
        $this->begin(array_map(fn(array $t) => $this->beast($t[0], $t[1], $t[2] ?? null), $theirs), $who, false, $uses);
    }

    /**
     * Begins a battle with one met on the road, who sends their beasts in order: a trial's rules (not fled, none bound,
     * experience at each fall and half as much again), told as a challenge.
     * @param list<array{0: string, 1: int}> $theirs [beast, level]
     */
    public function duel(string $who, array $theirs): void
    {
        $this->begin(array_map(fn(array $t) => $this->beast($t[0], $t[1], $t[2] ?? null), $theirs), $who, true);
    }

    /** Begins a battle with a wild $beast of $level: the party's beasts come to it as they are, and it leaves them as it ends. */
    public function fight(string $beast, int $level): void
    {
        if ($this->rng === null) {
            throw new LogicException('this world was made without the dice a battle needs');
        }
        // docs/numbers.md 20, 23: what it holds is rolled first, then its individual values
        $holds = $this->wildHolds($beast);
        $this->begin([$this->beast($beast, $level, $holds, Formulas::dvs($this->rng->byte(), $this->rng->byte()))], null);
    }

    /** @param list<Fighter> $theirs */
    private function begin(array $theirs, ?string $trial, bool $duel = false, array $uses = []): void
    {
        if ($this->rng === null) {
            throw new LogicException('this world was made without the dice a battle needs');
        }
        if (array_filter($this->party, fn(Fighter $f) => !$f->fainted()) === []) {
            throw new LogicException('a battle with no beast in the party that can fight');
        }
        $b = $this->book;
        foreach ($theirs as $f) { // docs/numbers.md 24: the kinds met in a battle are seen; a god is no kind of beast
            if (isset($b['beasts'][$f->id])) {
                $this->dex->see($f->id);
            }
        }
        if ($trial !== null && !$duel) { // docs/numbers.md 25: those that go into a god's trial able to fight grow closer
            foreach ($this->party as $f) {
                $f->bond = $f->fainted() ? $f->bond : Formulas::bond($f->bond, 'trial');
            }
        }
        $full = count($this->party) >= self::PARTY;
        $reward = $trial === null ? 0 : Formulas::reward($duel ? Formulas::REWARD_ROAD : Formulas::REWARD_GOD, $theirs[count($theirs) - 1]->level);
        $this->battle = new Screen(new Battle([$this->party, $theirs], $b['elements'], $b['moves'], $this->rng, [true, $trial !== null], $b['items']), $b['moves'], $this->rng, $b['items'], $this->items, $trial, $duel,
            $full ? Pen::name($this->box) : null, $full ? Pen::BOX - count($this->boxes[$this->box]) : 0, $reward, $this->afloat, $uses);
    }

    /**
     * The battle was lost: the script that began it is dropped, and the player wakes where the party was last made
     * whole (before any such place, where data/game.php rests them), the party whole, and half the money gone (docs/numbers.md 17).
     */
    private function wake(): void
    {
        $this->vm = null;
        [$this->dialog, $this->choices, $this->choiceCursor] = [null, null, null];
        $this->mend();
        $r = $this->book['rest'];
        [$map, $x, $y, $dir] = $this->restAt ?? [$r['map'], $r['x'], $r['y'], $r['dir']];
        $this->enter($map, $x, $y, Dir::from($dir));
        $lost = $this->money - intdiv($this->money, 2);
        $this->money -= $lost;
        $this->run([...$r['script'], ...($lost > 0 ? [['say', '慌乱之中丢了' . $lost . '贝。']] : [])]);
    }

    /** Whether water may be crossed yet: the flag data/game.php names is set. */
    public function mayFloat(): bool
    {
        return isset($this->flags[$this->book['float'] ?? throw new LogicException('this world was made without what lets water be crossed')]);
    }

    /** The first beast of the party that can carry the player over water: one of the water element that can fight. */
    public function swimmer(): ?Fighter
    {
        foreach ($this->party as $f) {
            if (!$f->fainted() && in_array('water', $f->species['elements'], true)) {
                return $f;
            }
        }
        return null;
    }

    /** The player, facing water with a beast to carry them, goes onto it. */
    public function float(): void
    {
        $p = $this->player;
        if (!$this->mayFloat() || $this->swimmer() === null || !$this->water($this->map, $p->x + $p->dir->dx(), $p->y + $p->dir->dy())) {
            throw new LogicException('no water faced, no beast to carry the player over it, or it is not yet allowed');
        }
        $this->afloat = true;
        $this->walk($p, $p->dir);
    }

    /** Opens buying from one who sells $sells. @param list<string> $sells ids of data/items.php */
    public function shop(array $sells): void
    {
        if ($this->book === null) {
            throw new LogicException('this world was made without the tables buying needs');
        }
        $this->shop = new Shop($sells);
    }

    /** Opens the pen's screen. */
    public function pen(): void
    {
        if ($this->book === null) {
            throw new LogicException('this world was made without the tables the pen needs');
        }
        $this->pen = new Pen();
    }

    /** What a saved game keeps: where the player is, the flags, the bag, the money, the party, the pen's boxes, where it last rested. Asked for when nothing holds the controls. */
    public function state(): array
    {
        if ($this->afloat) {
            throw new LogicException('a game is not saved on water');
        }
        $p = $this->player;
        [$seen, $caught] = [array_keys($this->dex->seen), array_keys($this->dex->caught)];
        sort($seen);
        sort($caught);
        return ['map' => $this->map->id, 'x' => $p->x, 'y' => $p->y, 'dir' => $p->dir->value, 'flags' => array_keys($this->flags),
            'items' => $this->items, 'party' => array_map(fn(Fighter $f) => $f->state(), $this->party), 'rest' => $this->restAt,
            'boxes' => array_map(fn(array $box) => array_map(fn(Fighter $f) => $f->state(), $box), $this->boxes), 'box' => $this->box, 'money' => $this->money,
            'seen' => $seen, 'caught' => $caught, 'steps' => $this->steps];
    }

    /** Puts the world as a saved game kept it (state()). */
    public function restore(array $s): void
    {
        $b = $this->book ?? throw new LogicException('this world was made without the tables a saved game needs');
        $beast = fn(array $f) => Fighter::fromState($f, $b['beasts'], $b['learnsets'], $b['moves']);
        $party = array_map($beast, $s['party']);
        $boxes = array_map(fn(array $box) => array_map($beast, $box), $s['boxes']);
        foreach ([...$party, ...array_merge(...$boxes)] as $f) {
            if ($f->item !== null && !isset($b['items'][$f->item]['hold'])) {
                throw new InvalidArgumentException("the saved game's {$f->id} holds what cannot be held: '{$f->item}'");
            }
        }
        foreach ([...$s['seen'], ...$s['caught']] as $id) {
            if (!isset($b['beasts'][$id])) {
                throw new InvalidArgumentException("the saved game's record has a kind there is none of: '$id'");
            }
        }
        if (array_diff($s['caught'], $s['seen']) !== []) {
            throw new InvalidArgumentException('the saved game has bound a kind it has not seen');
        }
        if (!array_is_list($boxes) || count($boxes) !== Pen::BOXES || !isset($boxes[$s['box']]) || array_filter($boxes, fn(array $box) => !array_is_list($box) || count($box) > Pen::BOX) !== []) {
            throw new InvalidArgumentException('the saved game\'s pen is not as a pen can be');
        }
        $map = $this->maps[$s['map']] ?? throw new InvalidArgumentException("the saved game is on a map there is none of: '{$s['map']}'");
        if (count($party) > self::PARTY || !$map->walkable($s['x'], $s['y']) || array_diff_key($s['items'], $b['items']) !== []
            || !is_int($s['money']) || $s['money'] < 0 || $s['money'] > Formulas::MAX_MONEY
            || !is_int($s['steps']) || $s['steps'] < 0 || $s['steps'] >= Formulas::BOND_STEPS) {
            throw new InvalidArgumentException('the saved game is not as a game can be');
        }
        $rest = $s['rest'];
        if ($rest !== null && !(isset($this->maps[$rest[0]]) && $this->maps[$rest[0]]->walkable($rest[1], $rest[2]) && Dir::tryFrom($rest[3]) !== null)) {
            throw new InvalidArgumentException('the saved game\'s place of rest is not one there is');
        }
        [$this->flags, $this->items, $this->party, $this->restAt, $this->boxes, $this->box, $this->money] = [array_fill_keys($s['flags'], true), $s['items'], $party, $rest, $boxes, $s['box'], $s['money']];
        [$this->dex->seen, $this->dex->caught, $this->steps] = [array_fill_keys($s['seen'], true), array_fill_keys($s['caught'], true), $s['steps']];
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

    /**
     * The rites the party's beasts could receive now (docs/numbers.md 27): for each that has one to receive, the jade
     * for it being in the bag and its level and bond enough. @return list<array{0: int, 1: int}> [place in the party, rite]
     */
    public function rites(): array
    {
        $out = [];
        foreach ($this->party as $i => $f) {
            $kind = $this->book['beasts'][$f->id] ?? null;
            $rite = $kind === null ? null : Formulas::nextRite($kind['kind'], $kind['tier'], $f->rank);
            if ($rite !== null && ($this->items[Formulas::RITES[$rite]['thing']] ?? 0) > 0 && $f->level >= Formulas::RITES[$rite]['level'] && $f->bond >= Formulas::RITES[$rite]['bond']) {
                $out[] = [$i, $rite];
            }
        }
        return $out;
    }

    /** The beast at $which of the party receives $rite, which takes the jade. The line that tells it. */
    public function rite(int $which, int $rite): string
    {
        if (!in_array([$which, $rite], $this->rites(), true)) {
            throw new LogicException("the party's beast $which cannot receive the rite $rite now");
        }
        $thing = Formulas::RITES[$rite]['thing'];
        if (--$this->items[$thing] === 0) {
            unset($this->items[$thing]);
        }
        $f = $this->party[$which];
        $was = $f->species['name'];
        $f->rite($rite);
        return $was . '受了' . Formulas::RITES[$rite]['name'] . '礼，成了' . $f->species['name'] . '！' . ($rite === Formulas::LAST_RITE ? '它的同心更强了。' : '六项能力都长了。');
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
    /** The steps walked since the party last grew closer (docs/numbers.md 25). */
    public int $steps = 0;

    private function arrived(): void
    {
        if (++$this->steps >= Formulas::BOND_STEPS) {
            $this->steps = 0;
            foreach ($this->party as $f) {
                $f->bond = Formulas::bond($f->bond, 'step');
            }
        }
        [$x, $y] = [$this->player->x, $this->player->y];
        $this->afloat = $this->water($this->map, $x, $y); // ashore, when the step was onto land
        if (($w = $this->map->warpAt($x, $y)) !== null) {
            $this->enter($w[2], $w[3], $w[4], Dir::from($w[5]));
            return;
        }
        if (($t = $this->map->triggerAt($x, $y)) !== null && !isset($this->flags[$t[2]])) {
            $this->flags[$t[2]] = true;
            $this->run($t[3]);
            return;
        }
        if (($who = $this->seenBy()) !== null) {
            $this->player->dir = $this->npcs[$who[0]]->dir->opposite();
            $this->run($who[5]);
            return;
        }
        $this->grass();
    }

    /**
     * The one on the map who watches (Map::$watch), has not yet been beaten, and sees the player where they now stand:
     * straight ahead of them, within their sight, with nothing between. None sees a player with no beast that can fight.
     * @return ?array the map's entry of that one
     */
    private function seenBy(): ?array
    {
        if (array_filter($this->party, fn(Fighter $f) => !$f->fainted()) === []) {
            return null;
        }
        foreach ($this->map->watch as [$id, $sight, $flag]) {
            $npc = $this->npcs[$id];
            if (isset($this->flags[$flag]) || $npc->moving) {
                continue;
            }
            for ($i = 1, [$x, $y] = [$npc->x, $npc->y]; $i <= $sight; $i++) {
                [$x, $y] = [$x + $npc->dir->dx(), $y + $npc->dir->dy()];
                if ($x === $this->player->x && $y === $this->player->y) {
                    foreach ($this->map->npcs as $def) {
                        if ($def[0] === $id) {
                            return $def;
                        }
                    }
                }
                if (!$this->free($x, $y)) {
                    break;
                }
            }
        }
        return null;
    }

    /**
     * A step taken where wild beasts are (tall grass; scree, on the mountains with no grass) may meet one (docs/numbers.md 13): not on the first steps after entering the
     * map or a battle; then a byte under the map's rate; then which of its seven. Afloat, it is the map's water that
     * holds them: three, each some levels over what the map says. With no beast that can fight, none is met.
     */
    private function grass(): void
    {
        // on water: what the map's water holds, of three; else what its grass does, of seven
        $wild = $this->afloat ? $this->map->water : $this->map->wild;
        if ($wild === null || !($this->afloat || Tile::wild($this->map->tile($this->player->x, $this->player->y)))
            || array_filter($this->party, fn(Fighter $f) => !$f->fainted()) === []) {
            return;
        }
        if ($this->wildWait > 0 && --$this->wildWait > 0) {
            return;
        }
        $rng = $this->rng ?? throw new LogicException('this world was made without the dice wild beasts need');
        // docs/numbers.md 19: with a beast of the party holding what wards them off, wild beasts are met half as often
        $calm = array_filter($this->party, fn(Fighter $f) => ($this->book['items'][$f->item ?? '']['hold'] ?? null) === 'calm') !== [];
        if ($rng->byte() >= Formulas::wildLimit($wild['rate']) >> ($calm ? 1 : 0)) {
            return;
        }
        [$level, $beast] = $wild['slots'][Formulas::wildSlot($rng, $this->afloat ? Formulas::WATER_SLOTS : Formulas::WILD_SLOTS)];
        $this->fight($beast, $level + ($this->afloat ? Formulas::waterLevelBonus($rng->byte()) : 0));
    }

    /** Confirm, standing still: talks to who, or reads what, the player faces; facing water with a beast of water, asks whether to go onto it, once that is allowed. */
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
        } elseif (!$this->afloat && $this->water($this->map, $x, $y) && ($f = $this->swimmer()) !== null) {
            if (!$this->mayFloat()) {
                $this->run([['say', '还不能渡水：还有一场试炼没有通过。']]);
                return;
            }
            $this->run([
                ['ask', '水面平静。要乘着' . $f->species['name'] . '渡水吗？', [['渡水', 'go'], ['算了', 'stay']]],
                ['label', 'go'],
                ['float'],
                ['label', 'stay'],
            ]);
        }
    }
}
