<?php
declare(strict_types=1);

namespace Shanhai;

use MyPHP\Plugins\Game\{Game, Input};
use MyPHP\Plugins\Game\Gamepad\{Button, Gamepad, State};
use MyPHP\UI\{Canvas, Key, Node, Painter};
use Shanhai\Battle\Screen;
use Shanhai\Render\{Arena, Stage};
use Shanhai\World\{Controls, Dir, World};

/**
 * The game as MyPHP runs it (Plugins\Game): each update reads the keys and the gamepad into Controls and steps
 * the World; each frame draws the map's Stage and, over it, the dialog box or the party's screen, or, in a battle,
 * its Arena, the two beasts' boxes, the line or the list. The rules are the World's: nothing here decides them.
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
    /** Updates a new map takes to fade in. */
    private const FADE_STEPS = 12;
    /** The 3D view's size in pixels, shown at the window's size. */
    private const VIEW_WIDTH = 960, VIEW_HEIGHT = 720;

    private Canvas $viewport;
    private Stage $stage;
    /** The scene of the battle going on; null outside one. */
    private ?Arena $arena = null;
    private int $seenMap;
    private int $fade = 0;
    /** The key walked by, which goes on while held, whatever else is pressed after it is not. */
    private ?int $walkKey = null;
    private State $lastPad;

    public function __construct(public readonly World $world)
    {
        $this->viewport = new Canvas(self::VIEW_WIDTH, self::VIEW_HEIGHT);
        $this->stage = new Stage($world);
        $this->seenMap = $world->mapChanges;
        $this->lastPad = new State();
    }

    public function update(Input $in): void
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
        if ($battle === null) {
            if ($this->arena !== null) {
                $this->arena = null;
                $this->fade = self::FADE_STEPS;
            }
        } elseif ($this->arena === null) {
            $this->arena = new Arena($battle);
            $this->fade = self::FADE_STEPS;
        } else {
            $this->arena->sync($battle);
        }

        if ($this->world->mapChanges !== $this->seenMap) {
            $this->seenMap = $this->world->mapChanges;
            $this->stage = new Stage($this->world);
            $this->fade = self::FADE_STEPS;
        } elseif ($this->fade > 0) {
            $this->fade--;
        }
        ($this->arena ?? $this->stage)->scene->step(1.0 / $this->rate());
    }

    public function draw(Painter $p, array $r, float $alpha): void
    {
        $battle = $this->world->battle;
        if ($battle !== null && $this->arena !== null) {
            $scene = $this->arena->scene;
        } else {
            $this->stage->sync($this->world, $alpha);
            $scene = $this->stage->scene;
        }
        $scene->camera->aspect = $r[2] / max(1.0, $r[3]);
        $this->viewport->renderScene($scene);
        $p->image($this->viewport->bitmap(), $r, Node::FILL_BOX);

        if ($this->fade > 0) {
            $p->fill($r, 0x000000, 0, $this->fade / self::FADE_STEPS);
        }
        if ($battle !== null && $this->arena !== null) {
            $this->battle($p, $r, $battle);
            return;
        }

        $name = $this->world->map->name;
        $p->fill([$r[0] + 16, $r[1] + 16, mb_strwidth($name) * 8 + 24, 34], 0x000000, 8.0, 0.55);
        $p->text($r[0] + 28, $r[1] + 24, $name, 15, 0xffffff);

        if ($this->world->menu !== null) {
            $this->choices($p, $r, $this->world->menu->items($this->world->party), $this->world->menu->cursor->at, 420.0);
        }
        if ($this->world->dialog !== null) {
            $this->dialog($p, $r, $this->world->dialog);
        }
    }

    /** A battle over its Arena: each beast's box (the other's at the top left, the player's at the right above the text), and the line or the list. */
    private function battle(Painter $p, array $r, Screen $s): void
    {
        $this->beastBox($p, $r[0] + 24, $r[1] + 24, $s, 1);
        $this->beastBox($p, $r[0] + $r[2] - self::BOX_WIDTH - 24, $r[1] + $r[3] - 290, $s, 0);
        if ($s->line !== null) {
            $this->dialog($p, $r, $s->line);
            return;
        }
        $this->choices($p, $r, $s->items(), $s->cursor->at, $s->phase === 'menu' ? 200.0 : 420.0);
    }

    /** A list at the bottom right of the view, $w wide, the cursor's mark by one of its entries. @param list<string> $items */
    private function choices(Painter $p, array $r, array $items, int $cursor, float $w): void
    {
        $lineHeight = Text::SIZE * 1.6;
        $h = 2 * Text::BOX_PADDING + count($items) * $lineHeight;
        $x = $r[0] + $r[2] - $w - 24;
        $y = $r[1] + $r[3] - $h - 24;
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

    /** A beast's box: its name, level, status, and its hp as a bar; the player's with the numbers too. */
    private function beastBox(Painter $p, float $x, float $y, Screen $s, int $side): void
    {
        $shown = $s->shown[$side];
        $f = $s->battle->parties[$side][$shown['which']];
        $h = $side === 0 ? 92.0 : 70.0;
        $p->fill([$x, $y, self::BOX_WIDTH, $h], 0x10141a, 10.0, 0.88);
        $p->stroke([$x, $y, self::BOX_WIDTH, $h], 0xe8dcc0, 10.0, 2.0, 0.9);
        $p->text($x + 16, $y + 10, $f->species['name'], Text::SIZE, 0xf4f0e6);
        $p->text($x + self::BOX_WIDTH - 76, $y + 12, 'Lv' . $f->level, Text::SIZE * 0.85, 0xf4f0e6);
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
            $p->text($x + 16, $y + 62, $shown['hp'] . ' / ' . $f->maxHp, Text::SIZE * 0.85, 0xf4f0e6);
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
