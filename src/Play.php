<?php
declare(strict_types=1);

namespace Shanhai;

use MyPHP\{App, Device, Logger};
use MyPHP\Plugins\Game\{Game, GameAudio, Input, ScreenTransition, VirtualPad};
use MyPHP\Plugins\Game\Gamepad\{Button, Gamepad, State};
use MyPHP\UI\{Canvas, GradientStop, Key, Node, Painter, RadialGradient};
use Shanhai\Battle\{Formulas, Screen};
use Shanhai\Render\{Arena, Sprites, Stage, Ui};
use Shanhai\World\{Controls, Dir, Pen, World};

/**
 * The game as MyPHP runs it (Plugins\Game): each update reads the keys and the gamepad into Controls and steps
 * the World; each frame draws the map's Stage and, over it, the dialog box or the party's screen, or, in a battle,
 * its Arena, the two beasts' boxes, the line or the list. The rules are the World's: nothing here decides them.
 * A change of map, and a battle's beginning and end, are shown by MyPHP's ScreenTransition: the World has changed
 * already, and waits, not stepped, while the screen closes on what was and opens on what is. On a device with no
 * keys, MyPHP's VirtualPad gives the keys this reads. A battle's line that tells of something to show (Screen::$cue)
 * is passed to the Arena once, and sounded with a tone of MyPHP's GameAudio; a critical hit stops the game a moment.
 */
final class Play extends Game
{
    /** The keys that walk, and those that confirm. */
    private const WALK = [
        Key::UP => Dir::Up, Key::W => Dir::Up, Key::DOWN => Dir::Down, Key::S => Dir::Down,
        Key::LEFT => Dir::Left, Key::A => Dir::Left, Key::RIGHT => Dir::Right, Key::D => Dir::Right,
    ];
    private const CONFIRM = [Key::SPACE, Key::RETURN, Key::Z];
    private const CANCEL = [Key::ESCAPE, Key::DELETE, Key::X];
    private const MENU = [Key::TAB, Key::M];
    /** How far a gamepad's stick is pushed to walk. */
    private const STICK = 0.5;
    /** Seconds a change of map takes to close and open, and a battle's beginning. */
    private const FADE_SECONDS = 0.4, ENCOUNTER_SECONDS = 0.55;
    /** Updates the game stops for when a critical hit lands. */
    private const CRITICAL_STOP = 3;
    /** The tones, made by GameAudio: [from Hz, to Hz, seconds, square wave]. */
    private const TONES = [
        'cursor' => [880.0, 880.0, 0.04, true],
        'use' => [300.0, 520.0, 0.12, false],
        'hit' => [220.0, 80.0, 0.14, true],
        'critical' => [440.0, 60.0, 0.22, true],
        'faint' => [300.0, 60.0, 0.35, false],
        'heal' => [520.0, 780.0, 0.18, false],
        'seal' => [500.0, 700.0, 0.10, false],
        'broke' => [400.0, 200.0, 0.16, true],
        'caught' => [660.0, 1320.0, 0.35, false],
        'level' => [523.0, 1046.0, 0.30, false],
    ];
    /** The 3D view's height in pixels; its width is the window's shape at that height, so that its pixels stay square. */
    private const VIEW_HEIGHT = 720;
    /**
     * lens()'s tilt-shift: each band [where its blur starts, where it is most, how much it is most], as parts of the
     * view's height from its top; the far band longer and stronger than the near. And its levels: [from how much
     * blur, wholly from, the sigma in points].
     */
    private const TILT = [[0.5, 0.0, 1.0], [0.72, 1.0, 0.8]], TILT_LEVELS = [[0.0, 0.5, 1.5], [0.5, 1.0, 4.0]];
    /** How many maps' Stages are kept (stageFor()): the map, the two beside it a road joins, and one more. */
    private const STAGES = 4;
    /** How long a map's name shows as one comes to it. */
    private const NAME_SECONDS = 3.5;
    /** The UI's pieces (Render\Ui): every box, list and line drawn over the view. */
    private Ui $ui;
    /** @var ?array<string, array> data/moves.php and data/elements.php, read when a battle's moves are first shown */
    private ?array $moves = null, $elements = null;
    /** The map whose name was shown, and when it began. */
    private string $namedFor = '';
    private float $namedAt = 0.0;
    /** How dark lens() makes the view's corners. */
    private const VIGNETTE = 0.55;

    private Canvas $viewport;
    private Stage $stage;
    /** @var array<string, Stage> the maps' scenes built last, by map id, the newest last: walked back into, one is not built again (STAGES of them) */
    private array $stages = [];
    /** The scene of the battle going on; null outside one. */
    private ?Arena $arena = null;
    /** The count of maps entered that the Stage was built at: behind the World's while the screen closes on the old map. */
    private int $seenMap;
    private ScreenTransition $transition;
    private VirtualPad $pad;
    private GameAudio $audio;
    /** The count of the battle's lines whose cue has been acted on. */
    private int $cued = 0;
    /** Which list shows and where its cursor is, to sound its moving. */
    private ?string $cursorAt = null;
    /** The key walked by, which goes on while held, whatever else is pressed after it is not. */
    private ?int $walkKey = null;
    private State $lastPad;

    /** @param array<string, array> $items data/items.php, for the names a bag shows */
    /** @var ?array<string, array> data/beasts.php, read when the record of beasts is first shown */
    private ?array $beasts = null;

    public function __construct(public readonly World $world, private readonly array $items)
    {
        $this->viewport = new Canvas(intdiv(self::VIEW_HEIGHT * 16, 9), self::VIEW_HEIGHT);
        $this->ui = new Ui();
        Stage::prepare(); // so that no change of map, nor a battle, stops to draw a sprite
        Arena::prepare();
        $this->stage = $this->stageFor();
        $this->seenMap = $world->mapChanges;
        $this->lastPad = new State();
        $this->transition = new ScreenTransition();
        $this->pad = new VirtualPad(enabled: !Device::isDesktop());
        $this->audio = new GameAudio();
        foreach (self::TONES as $name => [$from, $to, $seconds, $square]) {
            $this->audio->registerTone($name, $from, $to, $seconds, $square);
        }
    }

    public function update(Input $in): void
    {
        $started = hrtime(true);
        $this->timed('the pad', fn() => $this->pad->step($in));
        $this->timed('the transition', fn() => $this->transition->step(1.0 / $this->rate()));
        if (!$this->transition->active) {
            $this->play($in);
        }
        $dt = 1.0 / $this->rate();
        $this->timed('the sounds\' step', fn() => $this->audio->step($dt));
        $this->timed('the scene\'s step', fn() => $this->arena !== null ? $this->arena->step($dt) : $this->stage->scene->step($dt));
        $this->updating += hrtime(true) - $started;
    }

    /** A beast is struck in the Arena: the tone of it, and for a critical hit the game stops a moment. */
    private function struck(bool $critical): void
    {
        $this->sound($critical ? 'critical' : 'hit');
        if ($critical) {
            $this->hitStop(self::CRITICAL_STOP);
        }
    }

    /** Sounds the cursor of the list showing when it has moved. */
    private function cursor(): void
    {
        $w = $this->world;
        $at = match (true) {
            $w->battle !== null => $w->battle->phase === 'text' ? null : $w->battle->phase . $w->battle->cursor->at,
            $w->choices !== null => 'ask' . $w->choiceCursor->at,
            $w->title !== null => $w->title->note !== null ? null : 'title' . $w->title->phase . $w->title->cursor->at,
            $w->menu !== null => $w->menu->note !== null ? null : $w->menu->phase . $w->menu->at(),
            $w->pen !== null => $w->pen->note !== null ? null : 'pen' . $w->pen->phase . $w->pen->cursor->at,
            $w->shop !== null => $w->shop->note !== null ? null : 'shop' . $w->shop->phase . ($w->shop->phase === 'count' ? $w->shop->count : $w->shop->at),
            default => null,
        };
        // the same list, its cursor elsewhere: a list just opened makes no sound
        if ($at !== null && $this->cursorAt !== null && $at !== $this->cursorAt && rtrim($at, '0123456789') === rtrim($this->cursorAt, '0123456789')) {
            $this->sound('cursor');
        }
        $this->cursorAt = $at;
    }

    /** Steps the World with what the player asks, and begins a transition when what is to be shown has changed. */
    private function play(Input $in): void
    {
        // The key pressed last walks; when it is let go, another still held.
        foreach (self::WALK as $key => $_) {
            if ($in->pressed($key)) {
                $this->walkKey = $key;
            }
        }
        if ($this->walkKey !== null && !$in->down($this->walkKey)) {
            $this->walkKey = null;
            foreach (self::WALK as $key => $_) {
                if ($in->down($key)) {
                    $this->walkKey = $key;
                    break;
                }
            }
        }
        $dir = $this->walkKey !== null ? self::WALK[$this->walkKey] : null;
        $confirm = $cancel = $menu = false;
        foreach (self::CONFIRM as $key) {
            $confirm = $confirm || $in->pressed($key);
        }
        foreach (self::CANCEL as $key) {
            $cancel = $cancel || $in->pressed($key);
        }
        foreach (self::MENU as $key) {
            $menu = $menu || $in->pressed($key);
        }
        foreach (array_slice(Gamepad::pads(), 0, 1) as $pad) {
            $s = $pad->state();
            [$sx, $sy] = $s->leftStick();
            if ($dir === null && max(abs($sx), abs($sy)) >= self::STICK) {
                $dir = abs($sx) > abs($sy) ? ($sx > 0 ? Dir::Right : Dir::Left) : ($sy > 0 ? Dir::Down : Dir::Up);
            }
            $confirm = $confirm || $s->justPressed($this->lastPad, Button::South);
            $cancel = $cancel || $s->justPressed($this->lastPad, Button::East);
            $menu = $menu || $s->justPressed($this->lastPad, Button::Start);
            $this->lastPad = $s;
        }

        $this->timed('the world\'s step', fn() => $this->world->step(new Controls($dir, $confirm, $cancel, $menu)));

        $battle = $this->world->battle;
        if ($battle !== null && $this->arena === null) {
            // a battle has begun: the flashes and blinds of an encounter, the Arena behind them when they are shut
            $this->transition->start(ScreenTransition::BATTLE_BLINDS, self::ENCOUNTER_SECONDS, onMidpoint: function () use ($battle): void {
                $this->arena = $this->timed('building the battle\'s scene', fn() => new Arena($battle, $this->struck(...)));
                $this->cued = $battle->lines; // the lines it opens with tell of nothing to show
                gc_collect_cycles(); // while the screen is shut, as at the end of a battle (below)
            });
        } elseif ($battle === null && $this->arena === null && $this->world->mapChanges !== $this->seenMap && $this->world->seamless) {
            // across a seam to the map beside (an exit): no fade; the new map's Stage, which draws the one left beside it,
            // the player in the step across, so that the land goes on as it was
            $this->seenMap = $this->world->mapChanges;
            $this->stage = $this->stageFor();
        } elseif (($battle === null && $this->arena !== null) || $this->world->mapChanges !== $this->seenMap) {
            // a battle has ended, or the map is another: a fade, the new Stage behind it when it is dark
            $this->transition->start(ScreenTransition::FADE, self::FADE_SECONDS, onMidpoint: function (): void {
                $this->arena = null;
                if ($this->world->mapChanges !== $this->seenMap) {
                    $this->seenMap = $this->world->mapChanges;
                    $this->stage = $this->stageFor();
                }
                // The scene let go of holds itself (an entity and its children point at each other): PHP frees it only when
                // it collects cycles, which, left to itself or to MyPHP's MemoryWatch, it does at some moment of play, and the
                // game stops while the scene's meshes go. Collected here, it is while the screen is dark.
                $this->timed('collecting cycles', gc_collect_cycles(...));
            });
        } elseif ($battle !== null) {
            if ($battle->lines !== $this->cued) {
                $this->cued = $battle->lines;
                if ($battle->cue !== null) {
                    $this->arena->cue($battle->cue);
                    if (isset(self::TONES[$battle->cue['what']])) {
                        $this->sound($battle->cue['what']);
                    }
                }
            }
            $this->arena->sync($battle);
        }
        $this->cursor();
    }

    public function draw(Painter $p, array $r, float $alpha): void
    {
        $started = hrtime(true);
        $this->frame($p, $r, $alpha);
        $this->transition->draw($p, $r);
        $this->pad->draw($p, $r);
        $this->watch($started);
    }

    /** A frame longer than this is logged, in development, with where its time went (watch()). */
    private const SLOW_FRAME_MS = 50.0;
    /** When the frame before was drawn, and the time the updates since took, in nanoseconds; PHP's collector's times then. */
    private int $drawn = 0, $updating = 0;
    private ?array $collected = null;

    /**
     * In development, a frame that comes long after the one before is logged (subsystem "frame"), its time told out:
     * the World's and the scene's updates, its drawing, PHP's collecting of cycles (MyPHP's MemoryWatch collects
     * every 10 seconds, and PHP when its roots fill), and what is left, spent outside PHP's part of the frame.
     */
    private function watch(int $started): void
    {
        if (!App::isDev()) {
            return;
        }
        $now = hrtime(true);
        $gc = gc_status();
        $seconds = fn(?array $g) => $g === null ? 0.0 : $g['collector_time'] + $g['destructor_time'] + $g['free_time'];
        $gap = ($now - $this->drawn) / 1e6;
        if ($this->drawn !== 0 && $gap > self::SLOW_FRAME_MS) {
            [$updates, $drawing, $collecting] = [$this->updating / 1e6, ($now - $started) / 1e6, 1000 * ($seconds($gc) - $seconds($this->collected))];
            Logger::warn(sprintf('a frame took %.0f ms: updates %.1f, drawing %.1f, PHP collecting cycles %.1f (%d runs), elsewhere %.1f%s',
                $gap, $updates, $drawing, $collecting, $gc['runs'] - ($this->collected['runs'] ?? 0), $gap - $updates - $drawing - $collecting,
                implode('', array_map(fn(string $what, float $ms) => sprintf('; of the updates, %s %.1f', $what, $ms), array_keys($this->spent), $this->spent))), 'frame');
        }
        [$this->drawn, $this->updating, $this->collected, $this->spent] = [$now, 0, $gc, []];
    }

    /** Plays a sound of TONES, its time kept for watch(). */
    private function sound(string $name): void
    {
        $this->timed('playing a sound', fn() => $this->audio->playSfx($name));
    }

    /** What the updates since the frame before were spent on, that watch() tells of: what => ms. @var array<string, float> */
    private array $spent = [];

    /** Does $what, and keeps how long it took for watch(). @template T @param \Closure(): T $do @return T */
    private function timed(string $what, \Closure $do): mixed
    {
        $started = hrtime(true);
        $done = $do();
        $this->spent[$what] = ($this->spent[$what] ?? 0.0) + (hrtime(true) - $started) / 1e6;
        return $done;
    }

    /**
     * The Stage of the map the player is on: the one kept from the last time on it, or a new one, kept (the oldest
     * let go past STAGES), so that walking back and forth across a seam builds nothing.
     */
    private function stageFor(): Stage
    {
        $id = $this->world->map->id;
        $stage = $this->stages[$id] ?? $this->timed('building the map\'s scene', fn() => new Stage($this->world));
        unset($this->stages[$id]);
        $this->stages[$id] = $stage;
        if (count($this->stages) > self::STAGES) {
            array_shift($this->stages);
        }
        return $stage;
    }

    /**
     * HD-2D's lens over the 3D view (docs/art.md): what is far (the top) and what is near (the bottom) blurred, more
     * toward the edge, so that the middle, where the player is, looks like a small world close up (tilt-shift); and
     * the corners darkened. MyPHP's backdrop blur, a level a sigma, each taking over from the one before.
     * @param array{0: float, 1: float, 2: float, 3: float} $r
     */
    private function lens(Painter $p, array $r): void
    {
        [$x, $y, $w, $h] = $r;
        $s = $p->scale();
        foreach (self::TILT as [$from, $to, $most]) {
            [$y0, $y1] = [$y + $h * min($from, $to), $y + $h * max($from, $to)];
            $line = [$x * $s, ($y + $h * $from) * $s, $x * $s, ($y + $h * $to) * $s];
            foreach (self::TILT_LEVELS as [$lo, $hi, $sigma]) {
                $p->blurLevel([$x, $y0, $w, $y1 - $y0], 0, $sigma * $s, $line, 0.0, $most, $lo, $hi);
            }
        }
        $p->fillRadialGradient($r, new RadialGradient(stops: [new GradientStop(0x000000, 0.0, 0.0), new GradientStop(0x000000, 0.6, 0.0), new GradientStop(0x000000, 1.0, self::VIGNETTE)]));
    }

    /** The view and what is over it, before the transition and the pad. */
    private function frame(Painter $p, array $r, float $alpha): void
    {
        $battle = $this->world->battle;
        // The Stage is the World's map unless the screen is closing on the one before: then it is drawn as it was left.
        $current = $this->world->mapChanges === $this->seenMap;
        if ($this->arena !== null) {
            $scene = $this->arena->scene;
        } else {
            if ($current) {
                $this->stage->sync($this->world, $alpha);
            }
            $scene = $this->stage->scene;
        }
        $scene->camera->aspect = $r[2] / max(1.0, $r[3]);
        // the view as wide as the window's shape, made again when the window takes another
        $width = min(Canvas::MAX, max(1, (int)round(self::VIEW_HEIGHT * $scene->camera->aspect)));
        if ($this->viewport->w !== $width) {
            $this->viewport = new Canvas($width, self::VIEW_HEIGHT);
        }
        $this->viewport->renderScene($scene);
        $p->image($this->viewport->bitmap(), $r, Node::FILL_BOX);
        $this->lens($p, $r);

        if ($this->arena !== null) {
            if ($battle !== null) {
                $this->battle($p, $r, $battle);
            }
            return;
        }
        if (!$current || $battle !== null) {
            return; // the screen is closing on this map, for another or for a battle
        }

        $ui = $this->ui->on($p);
        if (($title = $this->world->title) !== null) {
            // the game's name over the map it begins on, between gold rules, and the title's list
            $ui->banner($r, $r[1] + $r[3] * 0.18, '山海驭兽录', 52.0);
            if ($title->note !== null) {
                $ui->dialog($r, $title->note);
            } else {
                $ui->list($r, $title->items(), $title->cursor->at);
            }
            return;
        }

        // the map's name, at the top, as one comes to it: shown a while, then fading
        if ($this->namedFor !== $this->world->map->id) {
            [$this->namedFor, $this->namedAt] = [$this->world->map->id, microtime(true)];
        }
        $shown = microtime(true) - $this->namedAt;
        if ($shown < self::NAME_SECONDS) {
            $ui->heading($r[0] + $r[2] / 2, $r[1] + 28, $this->world->map->name, 26.0, min(1.0, $shown / 0.4, (self::NAME_SECONDS - $shown) / 0.8));
        }

        if (($menu = $this->world->menu) !== null) {
            $this->menu($ui, $r, $menu);
        }
        if (($shop = $this->world->shop) !== null) {
            if ($shop->note !== null) {
                $ui->dialog($r, $shop->note);
            } else {
                [$lines, $at] = Pen::window($shop->items($this->items), $shop->at());
                $id = $shop->sells[$shop->at] ?? null;
                $ui->list($r, $lines, $at, info: $shop->phase !== 'count' && $id !== null ? [Info::thing($this->items[$id], $this->items[$id]['price']), null] : null);
            }
        }
        if ($shop !== null || $menu !== null) {
            $ui->plate($r, $this->world->money . ' 贝'); // what the player has to spend
        }
        if (($pen = $this->world->pen) !== null) {
            if ($pen->note !== null) {
                $ui->dialog($r, $pen->note);
            } else {
                [$lines, $at] = Pen::window($pen->items($this->world->party, $this->world->boxes, $this->world->box), $pen->cursor->at);
                $ui->list($r, $lines, $at);
            }
        }
        if ($this->world->dialog !== null) {
            $ui->dialog($r, $this->world->dialog);
        }
        if ($this->world->choices !== null) {
            // above the dialog box, whose line is the question
            $ui->list($r, $this->world->choices, $this->world->choiceCursor->at, lift: Ui::dialogHeight() - Ui::MARGIN);
        }
    }

    /**
     * The menu's screen showing: a list of the party as the party's rows; the bag and what can be held with what the
     * thing on the cursor is; the record of beasts with the one on the cursor, its sprite, if it has been seen.
     */
    private function menu(Ui $ui, array $r, \Shanhai\World\Menu $menu): void
    {
        if ($menu->note !== null) {
            $ui->dialog($r, $menu->note);
            return;
        }
        $party = $this->world->party;
        if ($menu->phase === 'sheet' && ($f = $party[$menu->at()] ?? null) !== null) {
            [$sprite, $colors] = Arena::sprite($f);
            $book = ['elements' => $this->elements ??= Data::elements(), 'items' => $this->items, 'moves' => $this->moves ??= Data::moves()];
            $this->ui->sheet($r, \Shanhai\World\Menu::detail($f, $book), Sprites::image($sprite, 'left', 0, $colors), $f->hp / max(1, $f->maxHp));
            return;
        }
        if (in_array($menu->phase, ['party', 'detail', 'use'], true) && $party !== []) {
            $ui->party($r, $party, $menu->at(), $menu->phase === 'party' ? $menu->party->picked : null);
            return;
        }
        $beasts = $this->beasts ??= Data::beasts();
        [$lines, $at] = Pen::window($menu->items($party, $this->world->items, $this->items, $this->world->dex, $beasts), $menu->at());
        $info = match ($menu->phase) {
            'bag' => ($id = array_keys($this->world->items)[$menu->at()] ?? null) === null ? null : [Info::thing($this->items[$id]), null],
            'give' => ($id = \Shanhai\World\Menu::holdable($this->world->items, $this->items)[$menu->at()] ?? null) === null ? null : [Info::thing($this->items[$id]), null],
            'dex' => $this->seen($menu->at(), $beasts),
            default => null,
        };
        $ui->list($r, $lines, $at, info: $info);
    }

    /** The box of the record of beasts for the one at $index: its name, kind and mountain, and its sprite, if it has been seen. @return array{0: list<string>, 1: ?\MyPHP\UI\Image} */
    private function seen(int $index, array $beasts): array
    {
        $id = array_keys($beasts)[$index] ?? null;
        if ($id === null || !isset($this->world->dex->seen[$id])) {
            return [['还没有见过。'], null];
        }
        $b = $beasts[$id];
        [$sprite, $colors] = Arena::spriteOf($id, $b);
        return [[$b['name'], \Shanhai\World\Dex::KINDS[$b['kind']] . '　出没：' . $b['mountain']], Sprites::image($sprite, 'left', 0, $colors)];
    }

    /** A battle over its Arena: each beast's box (the other's at the top left, the player's at the right above the text), and the line or the list. */
    private function battle(Painter $p, array $r, Screen $s): void
    {
        $ui = $this->ui->on($p);
        foreach ([1, 0] as $side) {
            $shown = $s->shown[$side];
            $f = $s->battle->parties[$side][$shown['which']];
            $exp = null;
            if ($side === 0) {
                $level = $shown['level'];
                $exp = 1.0;
                if ($level < Formulas::MAX_LEVEL) {
                    [$from, $to] = [Formulas::expAtLevel($f->species['growth'], $level), Formulas::expAtLevel($f->species['growth'], $level + 1)];
                    $exp = ($shown['exp'] - $from) / ($to - $from);
                }
            }
            [$x, $y] = $side === 1 ? [$r[0] + Ui::MARGIN, $r[1] + Ui::MARGIN] : [$r[0] + $r[2] - self::BOX_WIDTH - Ui::MARGIN, $r[1] + $r[3] - Ui::dialogHeight() - 112];
            $ui->status($x, $y, self::BOX_WIDTH, $f->species['name'], $shown['level'], $shown['status'] === null ? null : Screen::STATUS[$shown['status']], $shown['hp'], $f->maxHp, $exp);
        }
        if ($s->line !== null) {
            $ui->dialog($r, $s->line);
            return;
        }
        $me = $s->battle->fighter(0);
        if (in_array($s->phase, ['party', 'dose'], true)) {
            $ui->party($r, $s->battle->parties[0], $s->cursor->at);
            return;
        }
        [$lines, $at] = Pen::window($s->items(), $s->cursor->at);
        $moves = $this->moves ??= Data::moves();
        $elements = $this->elements ??= Data::elements();
        $info = match ($s->phase) {
            'moves' => ($m = $me->moves[$s->cursor->at] ?? null) === null ? null : [Info::move($moves[$m['id']], $elements, $m['pp']), null],
            'bag' => ($id = array_keys($this->world->items)[$s->cursor->at] ?? null) === null ? null : [Info::thing($this->items[$id]), null],
            default => null,
        };
        $ui->list($r, $lines, $at, info: $info);
    }

    private const BOX_WIDTH = 300.0;
}
