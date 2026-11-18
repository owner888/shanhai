<?php
declare(strict_types=1);

namespace Shanhai;

use MyPHP\{App, Device, Logger};
use MyPHP\Plugins\Game\{Game, GameAudio, Input, ScreenTransition, VirtualPad};
use MyPHP\Plugins\Game\Gamepad\{Button, Gamepad, State};
use MyPHP\UI\{Canvas, Font, GradientStop, Key, LinearGradient, Node, Painter, Path, RadialGradient, Span, TextLayout, TextStyle};
use Shanhai\Battle\{Formulas, Screen};
use Shanhai\Render\{Arena, Stage};
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
    /**
     * The UI (docs/art.md): set in a serif, as HD-2D's are (the system's Songti where it has it, else its own font),
     * cream on dark panels ruled in gold; a smaller size for numbers and notes; a box's padding. How long a map's name
     * shows as one comes to it.
     */
    private const SERIF = 'Songti SC', INK = 0xf2ead8, INK_DIM = 0xb8b0a0, GOLD = 0xc8a868, GOLD_DIM = 0x6a5a3a,
        PANEL = [0x161b24, 0x06080c], SMALL = 15.0, PAD = 10.0, NAME_SECONDS = 3.5;
    /** @var array<string, TextLayout> shaped(), by size and text */
    private array $shapes = [];
    /** The serif the UI is set in, if the system has it (SERIF); '' for the system's own font; null until looked for, once. */
    private ?string $serif = null;
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

        if (($title = $this->world->title) !== null) {
            // the game's name over the map it begins on, between ornaments, and the title's list
            $band = [$r[0], $r[1] + $r[3] * 0.18, $r[2], 110.0];
            $p->fillPathGradient(Path::rect(...$band), new LinearGradient(stops: [new GradientStop(0x000000, 0.0, 0.0), new GradientStop(0x000000, 0.3, 0.55), new GradientStop(0x000000, 0.7, 0.55), new GradientStop(0x000000, 1.0, 0.0)], angle: 90));
            $this->heading($p, $r[0] + $r[2] / 2, $band[1] + 26, '山海驭兽录', 52.0, 1.0);
            if ($title->note !== null) {
                $this->dialog($p, $r, $title->note);
            } else {
                $this->choices($p, $r, $title->items(), $title->cursor->at, $title->phase === 'top' ? 200.0 : 640.0);
            }
            return;
        }

        // the map's name, at the top, as one comes to it: shown a while, then fading
        if ($this->namedFor !== $this->world->map->id) {
            [$this->namedFor, $this->namedAt] = [$this->world->map->id, microtime(true)];
        }
        $shown = microtime(true) - $this->namedAt;
        if ($shown < self::NAME_SECONDS) {
            $fade = min(1.0, $shown / 0.4, (self::NAME_SECONDS - $shown) / 0.8);
            $this->heading($p, $r[0] + $r[2] / 2, $r[1] + 28, $this->world->map->name, 26.0, $fade);
        }

        if ($this->world->menu !== null) {
            $menu = $this->world->menu;
            if ($menu->note !== null) {
                $this->dialog($p, $r, $menu->note);
            } else {
                [$lines, $at] = Pen::window($menu->items($this->world->party, $this->world->items, $this->items, $this->world->dex, $this->beasts ??= Data::beasts()), $menu->at());
                $this->choices($p, $r, $lines, $at, match ($menu->phase) { 'top' => 200.0, 'slots', 'overwrite' => 640.0, default => 420.0 });
            }
        }
        if (($shop = $this->world->shop) !== null) {
            if ($shop->note !== null) {
                $this->dialog($p, $r, $shop->note);
            } else {
                [$lines, $at] = Pen::window($shop->items($this->items), $shop->at());
                $this->choices($p, $r, $lines, $at, 280.0);
            }
        }
        if ($shop !== null || $this->world->menu !== null) {
            // what the player has to spend, at the top right
            $money = $this->world->money . ' 贝';
            $w = $this->shaped($p, $money, self::SMALL)->size()[0] + 2 * self::PAD;
            $box = [$r[0] + $r[2] - $w - 24, $r[1] + 24, $w, self::SMALL + 2 * self::PAD];
            $this->panel($p, $box);
            $this->write($p, $box[0] + self::PAD, $box[1] + self::PAD, $money, self::SMALL, self::GOLD);
        }
        if (($pen = $this->world->pen) !== null) {
            if ($pen->note !== null) {
                $this->dialog($p, $r, $pen->note);
            } else {
                [$lines, $at] = Pen::window($pen->items($this->world->party, $this->world->boxes, $this->world->box), $pen->cursor->at);
                $this->choices($p, $r, $lines, $at, $pen->phase === 'top' ? 280.0 : 420.0);
            }
        }
        if ($this->world->dialog !== null) {
            $this->dialog($p, $r, $this->world->dialog);
        }
        if ($this->world->choices !== null) {
            // above the dialog box, whose line is the question
            $this->choices($p, $r, $this->world->choices, $this->world->choiceCursor->at, 200.0, 2 * Text::BOX_PADDING + Text::LINES * Text::SIZE * 1.5 + 12);
        }
    }

    /** A battle over its Arena: each beast's box (the other's at the top left, the player's at the right above the text), and the line or the list. */
    private function battle(Painter $p, array $r, Screen $s): void
    {
        $this->beastBox($p, $r[0] + 24, $r[1] + 24, $s, 1);
        $this->beastBox($p, $r[0] + $r[2] - self::BOX_WIDTH - 24, $r[1] + $r[3] - 302, $s, 0);
        if ($s->line !== null) {
            $this->dialog($p, $r, $s->line);
            return;
        }
        [$lines, $at] = Pen::window($s->items(), $s->cursor->at);
        $this->choices($p, $r, $lines, $at, $s->phase === 'menu' ? 200.0 : 420.0);
    }

    /** A list at the bottom right of the view, $w wide and $lift above the bottom, the cursor's mark by one of its entries. @param list<string> $items */
    private function choices(Painter $p, array $r, array $items, int $cursor, float $w, float $lift = 0.0): void
    {
        $lineHeight = Text::SIZE * 1.7;
        $h = 2 * Text::BOX_PADDING + count($items) * $lineHeight;
        $x = $r[0] + $r[2] - $w - 24;
        $y = $r[1] + $r[3] - $h - 24 - $lift;
        $this->panel($p, [$x, $y, $w, $h]);
        foreach ($items as $i => $text) {
            $ty = $y + Text::BOX_PADDING + $i * $lineHeight;
            if ($i === $cursor) {
                // the entry the cursor is on: a band of gold fading to the right, a diamond before it
                $band = [$x + 6, $ty - ($lineHeight - Text::SIZE) / 2, $w - 12, $lineHeight];
                $p->fillPathGradient(Path::rect(...$band), new LinearGradient(stops: [new GradientStop(self::GOLD, 0.0, 0.32), new GradientStop(self::GOLD, 1.0, 0.0)], angle: 90));
                $this->diamond($p, $x + Text::BOX_PADDING + 5, $ty + Text::SIZE / 2 + 1, 5.0, self::GOLD);
            }
            $this->write($p, $x + Text::BOX_PADDING + Text::SIZE * 1.2, $ty, $text, Text::SIZE, $i === $cursor ? self::INK : self::INK_DIM);
        }
    }

    private const BOX_WIDTH = 300.0, BAR_HEIGHT = 6.0;

    /** A beast's box: its name, level, status, and its hp as a bar; the player's with the numbers too, and how far it is to its next level. */
    private function beastBox(Painter $p, float $x, float $y, Screen $s, int $side): void
    {
        $shown = $s->shown[$side];
        $f = $s->battle->parties[$side][$shown['which']];
        $h = $side === 0 ? 100.0 : 72.0;
        $this->panel($p, [$x, $y, self::BOX_WIDTH, $h]);
        $this->write($p, $x + 18, $y + 12, $f->species['name'], Text::SIZE, self::INK);
        $level = 'Lv ' . $shown['level'];
        $this->write($p, $x + self::BOX_WIDTH - 18 - $this->shaped($p, $level, self::SMALL)->size()[0], $y + 14, $level, self::SMALL, self::GOLD);
        if ($shown['status'] !== null) {
            $this->write($p, $x + self::BOX_WIDTH - 110, $y + 14, Screen::STATUS[$shown['status']], self::SMALL, 0xe8a050);
        }
        $part = $shown['hp'] / $f->maxHp;
        $bar = [$x + 18, $y + 46, self::BOX_WIDTH - 36, self::BAR_HEIGHT];
        $p->fill([$bar[0] - 1, $bar[1] - 1, $bar[2] + 2, $bar[3] + 2], self::GOLD_DIM, 1.0);
        $p->fill($bar, 0x0a0c10, 1.0);
        if ($shown['hp'] > 0) {
            // green over a half, amber over a fifth, red under it; lighter along its top
            [$from, $to] = $part > 0.5 ? [0x8ad870, 0x3e9a4a] : ($part > 0.2 ? [0xf0d070, 0xc08a30] : [0xf08060, 0xb83a30]);
            $p->fillPathGradient(Path::rect($bar[0], $bar[1], max(2.0, $bar[2] * $part), $bar[3]), new LinearGradient(stops: [new GradientStop($from, 0.0, 1.0), new GradientStop($to, 1.0, 1.0)]));
        }
        if ($side === 0) {
            $hp = $shown['hp'] . ' / ' . $f->maxHp;
            $this->write($p, $bar[0] + $bar[2] - $this->shaped($p, $hp, self::SMALL)->size()[0], $y + 56, $hp, self::SMALL, self::INK);
            $growth = $f->species['growth'];
            $level = $shown['level'];
            $line = [$bar[0], $y + 84, $bar[2], 3.0];
            $p->fill($line, 0x0a0c10, 1.0);
            if ($level < Formulas::MAX_LEVEL) {
                [$from, $to] = [Formulas::expAtLevel($growth, $level), Formulas::expAtLevel($growth, $level + 1)];
                $part = max(0.0, min(1.0, ($shown['exp'] - $from) / ($to - $from)));
                if ($part > 0.0) {
                    $p->fill([$line[0], $line[1], $line[2] * $part, $line[3]], 0x70b0e8, 1.0);
                }
            }
        }
    }

    /** The dialog box, at the bottom of the view: the line showing, and the mark that confirm goes on. */
    private function dialog(Painter $p, array $r, string $line): void
    {
        $lineHeight = Text::SIZE * 1.6;
        $w = Text::BOX_WIDTH;
        $h = 2 * Text::BOX_PADDING + Text::LINES * $lineHeight;
        $x = $r[0] + ($r[2] - $w) / 2;
        $y = $r[1] + $r[3] - $h - 24;
        $this->panel($p, [$x, $y, $w, $h]);
        foreach (Text::wrap($line, Text::columns()) as $i => $text) {
            $this->write($p, $x + Text::BOX_PADDING, $y + Text::BOX_PADDING + $i * $lineHeight, $text, Text::SIZE, self::INK);
        }
        // the mark that confirm goes on: a diamond, bobbing
        $bob = 2.0 * sin(microtime(true) * 5.0);
        $this->diamond($p, $x + $w - Text::BOX_PADDING - 4, $y + $h - Text::BOX_PADDING - 2 + $bob, 5.0, self::GOLD);
    }

    /**
     * A panel as HD-2D's (docs/art.md, the UI): dark and a little see-through, darker toward its foot, a gold rule round
     * it and a fainter one inside, a diamond at each corner. All the boxes are drawn with it.
     * @param array{0: float, 1: float, 2: float, 3: float} $b
     */
    private function panel(Painter $p, array $b): void
    {
        [$x, $y, $w, $h] = $b;
        $p->fillPathGradient(Path::rect($x, $y, $w, $h), new LinearGradient(stops: [new GradientStop(self::PANEL[0], 0.0, 0.86), new GradientStop(self::PANEL[1], 1.0, 0.94)]));
        $p->stroke($b, self::GOLD, 1.0, 1.5, 0.9);
        $p->stroke([$x + 4, $y + 4, $w - 8, $h - 8], self::GOLD_DIM, 0.0, 1.0, 0.8);
        foreach ([[$x, $y], [$x + $w, $y], [$x, $y + $h], [$x + $w, $y + $h]] as [$cx, $cy]) {
            $this->diamond($p, $cx, $cy, 4.5, self::GOLD);
        }
    }

    /** A name between two gold rules that fade outward, a diamond at each rule's inner end: the title's, a map's. */
    private function heading(Painter $p, float $cx, float $y, string $text, float $size, float $alpha): void
    {
        $w = $this->shaped($p, $text, $size)->size()[0];
        $this->write($p, $cx - $w / 2, $y, $text, $size, self::INK, $alpha);
        $mid = $y + $size * 0.62;
        $reach = 150.0;
        foreach ([-1, 1] as $side) {
            $inner = $cx + $side * ($w / 2 + 18);
            $outer = $inner + $side * $reach;
            [$from, $to] = $side < 0 ? [$outer, $inner] : [$inner, $outer];
            $p->fillPathGradient(Path::rect($from, $mid - 0.75, $to - $from, 1.5), new LinearGradient(stops: [new GradientStop(self::GOLD, 0.0, $side < 0 ? 0.0 : $alpha), new GradientStop(self::GOLD, 1.0, $side < 0 ? $alpha : 0.0)], angle: 90));
            $this->diamond($p, $inner, $mid, 4.0, self::GOLD, $alpha);
        }
    }

    private function diamond(Painter $p, float $cx, float $cy, float $r, int $rgb, float $alpha = 1.0): void
    {
        $p->fillPath((new Path())->moveTo($cx, $cy - $r)->lineTo($cx + $r, $cy)->lineTo($cx, $cy + $r)->lineTo($cx - $r, $cy)->close(), $rgb, $alpha);
    }

    /** Writes $text, its top left at $x, $y, in the serif the UI is set in (SERIF). */
    private function write(Painter $p, float $x, float $y, string $text, float $size, int $rgb, float $alpha = 1.0): void
    {
        $p->textLayout($this->shaped($p, $text, $size), $x, $y, $rgb, $alpha);
    }

    /** $text shaped in the UI's serif at $size, kept: the same lines are drawn every frame. */
    private function shaped(Painter $p, string $text, float $size): TextLayout
    {
        if (count($this->shapes) > 512) {
            $this->shapes = [];
        }
        $this->serif ??= in_array(self::SERIF, array_map(fn($f) => $f->name, Font::families()), true) ? self::SERIF : '';
        return $this->shapes["$size|$text"] ??= TextLayout::shape($p->textPainter()->textSystem(), [new Span($text, size: $size, font: $this->serif === '' ? null : $this->serif)], new TextStyle(), $size);
    }
}
