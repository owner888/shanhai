<?php
declare(strict_types=1);

namespace Shanhai;

use MyPHP\Device;
use MyPHP\Plugins\Game\{Game, GameAudio, Input, ScreenTransition, VirtualPad};
use MyPHP\Plugins\Game\Gamepad\{Button, Gamepad, State};
use MyPHP\UI\{Canvas, Key, Node, Painter};
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
    /** The 3D view's size in pixels, shown at the window's size. */
    private const VIEW_WIDTH = 960, VIEW_HEIGHT = 720;

    private Canvas $viewport;
    private Stage $stage;
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
    public function __construct(public readonly World $world, private readonly array $items)
    {
        $this->viewport = new Canvas(self::VIEW_WIDTH, self::VIEW_HEIGHT);
        $this->stage = new Stage($world);
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
        $this->pad->step($in);
        $this->transition->step(1.0 / $this->rate());
        if (!$this->transition->active) {
            $this->play($in);
        }
        $dt = 1.0 / $this->rate();
        $this->audio->step($dt);
        if ($this->arena !== null) {
            $this->arena->step($dt);
        } else {
            $this->stage->scene->step($dt);
        }
    }

    /** A beast is struck in the Arena: the tone of it, and for a critical hit the game stops a moment. */
    private function struck(bool $critical): void
    {
        $this->audio->playSfx($critical ? 'critical' : 'hit');
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
            $w->menu !== null => $w->menu->phase . $w->menu->at(),
            $w->pen !== null => $w->pen->note !== null ? null : 'pen' . $w->pen->phase . $w->pen->cursor->at,
            $w->shop !== null => $w->shop->note !== null ? null : 'shop' . $w->shop->phase . ($w->shop->phase === 'count' ? $w->shop->count : $w->shop->at),
            default => null,
        };
        // the same list, its cursor elsewhere: a list just opened makes no sound
        if ($at !== null && $this->cursorAt !== null && $at !== $this->cursorAt && rtrim($at, '0123456789') === rtrim($this->cursorAt, '0123456789')) {
            $this->audio->playSfx('cursor');
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

        $this->world->step(new Controls($dir, $confirm, $cancel, $menu));

        $battle = $this->world->battle;
        if ($battle !== null && $this->arena === null) {
            // a battle has begun: the flashes and blinds of an encounter, the Arena behind them when they are shut
            $this->transition->start(ScreenTransition::BATTLE_BLINDS, self::ENCOUNTER_SECONDS, onMidpoint: function () use ($battle): void {
                $this->arena = new Arena($battle, $this->struck(...));
                $this->cued = $battle->lines; // the lines it opens with tell of nothing to show
            });
        } elseif (($battle === null && $this->arena !== null) || $this->world->mapChanges !== $this->seenMap) {
            // a battle has ended, or the map is another: a fade, the new Stage behind it when it is dark
            $this->transition->start(ScreenTransition::FADE, self::FADE_SECONDS, onMidpoint: function (): void {
                $this->arena = null;
                if ($this->world->mapChanges !== $this->seenMap) {
                    $this->seenMap = $this->world->mapChanges;
                    $this->stage = new Stage($this->world);
                }
            });
        } elseif ($battle !== null) {
            if ($battle->lines !== $this->cued) {
                $this->cued = $battle->lines;
                if ($battle->cue !== null) {
                    $this->arena->cue($battle->cue);
                    if (isset(self::TONES[$battle->cue['what']])) {
                        $this->audio->playSfx($battle->cue['what']);
                    }
                }
            }
            $this->arena->sync($battle);
        }
        $this->cursor();
    }

    public function draw(Painter $p, array $r, float $alpha): void
    {
        $this->frame($p, $r, $alpha);
        $this->transition->draw($p, $r);
        $this->pad->draw($p, $r);
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
        $this->viewport->renderScene($scene);
        $p->image($this->viewport->bitmap(), $r, Node::FILL_BOX);

        if ($this->arena !== null) {
            if ($battle !== null) {
                $this->battle($p, $r, $battle);
            }
            return;
        }
        if (!$current || $battle !== null) {
            return; // the screen is closing on this map, for another or for a battle
        }

        $name = $this->world->map->name;
        $p->fill([$r[0] + 16, $r[1] + 16, mb_strwidth($name) * 8 + 24, 34], 0x000000, 8.0, 0.55);
        $p->text($r[0] + 28, $r[1] + 24, $name, 15, 0xffffff);

        if ($this->world->menu !== null) {
            $menu = $this->world->menu;
            $this->choices($p, $r, $menu->items($this->world->party, $this->world->items, $this->items), $menu->at(), $menu->phase === 'top' ? 200.0 : 420.0);
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
            $w = mb_strwidth($money) * 8 + 24;
            $p->fill([$r[0] + $r[2] - $w - 16, $r[1] + 16, $w, 34], 0x000000, 8.0, 0.55);
            $p->text($r[0] + $r[2] - $w - 4, $r[1] + 24, $money, 15, 0xffffff);
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
        $this->choices($p, $r, $s->items(), $s->cursor->at, $s->phase === 'menu' ? 200.0 : 420.0);
    }

    /** A list at the bottom right of the view, $w wide and $lift above the bottom, the cursor's mark by one of its entries. @param list<string> $items */
    private function choices(Painter $p, array $r, array $items, int $cursor, float $w, float $lift = 0.0): void
    {
        $lineHeight = Text::SIZE * 1.6;
        $h = 2 * Text::BOX_PADDING + count($items) * $lineHeight;
        $x = $r[0] + $r[2] - $w - 24;
        $y = $r[1] + $r[3] - $h - 24 - $lift;
        $p->fill([$x, $y, $w, $h], 0x10141a, 12.0, 0.92);
        $p->stroke([$x, $y, $w, $h], 0xe8dcc0, 12.0, 2.0, 0.9);
        foreach ($items as $i => $text) {
            $ty = $y + Text::BOX_PADDING + $i * $lineHeight;
            if ($i === $cursor) {
                $p->text($x + Text::BOX_PADDING, $ty, '▶', Text::SIZE * 0.8, 0xe8dcc0);
            }
            $p->text($x + Text::BOX_PADDING + Text::SIZE * 1.4, $ty, $text, Text::SIZE, 0xf4f0e6);
        }
    }

    private const BOX_WIDTH = 300.0, BAR_HEIGHT = 10.0;

    /** A beast's box: its name, level, status, and its hp as a bar; the player's with the numbers too, and how far it is to its next level. */
    private function beastBox(Painter $p, float $x, float $y, Screen $s, int $side): void
    {
        $shown = $s->shown[$side];
        $f = $s->battle->parties[$side][$shown['which']];
        $h = $side === 0 ? 104.0 : 70.0;
        $p->fill([$x, $y, self::BOX_WIDTH, $h], 0x10141a, 10.0, 0.88);
        $p->stroke([$x, $y, self::BOX_WIDTH, $h], 0xe8dcc0, 10.0, 2.0, 0.9);
        $p->text($x + 16, $y + 10, $f->species['name'], Text::SIZE, 0xf4f0e6);
        $p->text($x + self::BOX_WIDTH - 76, $y + 12, 'Lv' . $shown['level'], Text::SIZE * 0.85, 0xf4f0e6);
        if ($shown['status'] !== null) {
            $p->text($x + self::BOX_WIDTH - 132, $y + 12, Screen::STATUS[$shown['status']], Text::SIZE * 0.85, 0xf0b050);
        }
        $part = $shown['hp'] / $f->maxHp;
        $bar = [$x + 16, $y + 44, self::BOX_WIDTH - 32, self::BAR_HEIGHT];
        $p->fill($bar, 0x3a3f48, self::BAR_HEIGHT / 2);
        if ($shown['hp'] > 0) {
            // green over a half, yellow over a fifth, red under it
            $color = $part > 0.5 ? 0x58c060 : ($part > 0.2 ? 0xe0c040 : 0xd85040);
            $p->fill([$bar[0], $bar[1], max(self::BAR_HEIGHT, $bar[2] * $part), $bar[3]], $color, self::BAR_HEIGHT / 2);
        }
        if ($side === 0) {
            $p->text($x + 16, $y + 60, $shown['hp'] . ' / ' . $f->maxHp, Text::SIZE * 0.85, 0xf4f0e6);
            $growth = $f->species['growth'];
            $level = $shown['level'];
            $p->fill([$bar[0], $y + 88, $bar[2], 4.0], 0x3a3f48, 2.0);
            if ($level < Formulas::MAX_LEVEL) {
                [$from, $to] = [Formulas::expAtLevel($growth, $level), Formulas::expAtLevel($growth, $level + 1)];
                $part = max(0.0, min(1.0, ($shown['exp'] - $from) / ($to - $from)));
                if ($part > 0.0) {
                    $p->fill([$bar[0], $y + 88, $bar[2] * $part, 4.0], 0x50a0e0, 2.0);
                }
            }
        }
    }

    /** The dialog box, at the bottom of the view: the line showing, and the mark that confirm goes on. */
    private function dialog(Painter $p, array $r, string $line): void
    {
        $lineHeight = Text::SIZE * 1.5;
        $w = Text::BOX_WIDTH;
        $h = 2 * Text::BOX_PADDING + Text::LINES * $lineHeight;
        $x = $r[0] + ($r[2] - $w) / 2;
        $y = $r[1] + $r[3] - $h - 24;
        $p->fill([$x, $y, $w, $h], 0x10141a, 12.0, 0.92);
        $p->stroke([$x, $y, $w, $h], 0xe8dcc0, 12.0, 2.0, 0.9);
        foreach (Text::wrap($line, Text::columns()) as $i => $text) {
            $p->text($x + Text::BOX_PADDING, $y + Text::BOX_PADDING + $i * $lineHeight, $text, Text::SIZE, 0xf4f0e6);
        }
        $p->text($x + $w - Text::BOX_PADDING - Text::SIZE, $y + $h - Text::BOX_PADDING - Text::SIZE, '▼', Text::SIZE * 0.7, 0xe8dcc0);
    }
}
