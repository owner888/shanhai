<?php
declare(strict_types=1);

namespace Shanhai;

use MyPHP\Plugins\Game\{Game, Input};
use MyPHP\Plugins\Game\Gamepad\{Button, Gamepad, State};
use MyPHP\UI\{Canvas, Key, Node, Painter};
use Shanhai\Render\Stage;
use Shanhai\World\{Controls, Dir, World};

/**
 * The game as MyPHP runs it (Plugins\Game): each update reads the keys and the gamepad into Controls and steps
 * the World; each frame draws the map's Stage and, over it, the dialog box. The rules are the World's: nothing here decides them.
 */
final class Play extends Game
{
    /** The keys that walk, and those that confirm. */
    private const WALK = [
        Key::UP => Dir::Up, Key::W => Dir::Up, Key::DOWN => Dir::Down, Key::S => Dir::Down,
        Key::LEFT => Dir::Left, Key::A => Dir::Left, Key::RIGHT => Dir::Right, Key::D => Dir::Right,
    ];
    private const CONFIRM = [Key::SPACE, Key::RETURN, Key::Z];
    /** How far a gamepad's stick is pushed to walk. */
    private const STICK = 0.5;
    /** Updates a new map takes to fade in. */
    private const FADE_STEPS = 12;
    /** The 3D view's size in pixels, shown at the window's size. */
    private const VIEW_WIDTH = 960, VIEW_HEIGHT = 720;

    private Canvas $viewport;
    private Stage $stage;
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
        $confirm = false;
        foreach (self::CONFIRM as $key) {
            $confirm = $confirm || $in->pressed($key);
        }
        foreach (array_slice(Gamepad::pads(), 0, 1) as $pad) {
            $s = $pad->state();
            [$sx, $sy] = $s->leftStick();
            if ($dir === null && max(abs($sx), abs($sy)) >= self::STICK) {
                $dir = abs($sx) > abs($sy) ? ($sx > 0 ? Dir::Right : Dir::Left) : ($sy > 0 ? Dir::Down : Dir::Up);
            }
            $confirm = $confirm || $s->justPressed($this->lastPad, Button::South);
            $this->lastPad = $s;
        }

        $this->world->step(new Controls($dir, $confirm));

        if ($this->world->mapChanges !== $this->seenMap) {
            $this->seenMap = $this->world->mapChanges;
            $this->stage = new Stage($this->world);
            $this->fade = self::FADE_STEPS;
        } elseif ($this->fade > 0) {
            $this->fade--;
        }
        $this->stage->scene->step(1.0 / $this->rate());
    }

    public function draw(Painter $p, array $r, float $alpha): void
    {
        $this->stage->sync($this->world, $alpha);
        $this->stage->scene->camera->aspect = $r[2] / max(1.0, $r[3]);
        $this->viewport->renderScene($this->stage->scene);
        $p->image($this->viewport->bitmap(), $r, Node::FILL_BOX);

        if ($this->fade > 0) {
            $p->fill($r, 0x000000, 0, $this->fade / self::FADE_STEPS);
        }

        $name = $this->world->map->name;
        $p->fill([$r[0] + 16, $r[1] + 16, mb_strwidth($name) * 8 + 24, 34], 0x000000, 8.0, 0.55);
        $p->text($r[0] + 28, $r[1] + 24, $name, 15, 0xffffff);

        if ($this->world->dialog !== null) {
            $this->dialog($p, $r, $this->world->dialog);
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
