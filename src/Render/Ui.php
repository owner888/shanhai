<?php
declare(strict_types=1);

namespace Shanhai\Render;

use MyPHP\UI\{Font, GradientStop, Image, LinearGradient, Node, Painter, Path, Span, TextLayout, TextStyle};
use Shanhai\Battle\Fighter;
use Shanhai\Text;

/**
 * The UI's pieces, as HD-2D's are drawn (docs/art.md, the UI): every screen of the game is put together from these, so
 * that how a panel, a list, a bar or a line of text looks is said once, here. Play says what shows; this says how.
 *
 * - panel(): every box; heading(): a name between gold rules (a map's), banner(): one on a dark band (the title's);
 *   diamond(): the marks.
 * - write() and width(): text in the UI's serif, shaped once and kept.
 * - list(): a list at the bottom right, as wide as its widest entry, the entry the cursor is on marked, and beside it,
 *   when there is something to say of that entry, info(): its lines, and a picture.
 * - party(): the party as rows, each a beast's name, level and hp as a bar, for every list that is of the party.
 * - dialog(): the dialog box; status(): a beast's box in a battle; plate(): a small box of one line (the money).
 * - bar(): a bar of how much of something (hp, exp).
 * The sizes of the text and the dialog box are Text's, which wraps the lines to them.
 */
final class Ui
{
    /** The serif the UI is set in, where the system has it (else its own font); the colors: cream on dark, gold. */
    public const SERIF = 'Songti SC', INK = 0xf2ead8, INK_DIM = 0xb8b0a0, GOLD = 0xc8a868, GOLD_DIM = 0x6a5a3a,
        PANEL = [0x161b24, 0x06080c];
    /** A smaller size, for numbers and notes; a small box's padding; how far boxes keep from the view's edge. */
    public const SMALL = 15.0, PAD = 10.0, MARGIN = 24.0;
    /** A list's line height, and a dialog's, as many times the text's size. */
    public const LIST_LINE = 1.7, DIALOG_LINE = 1.6;
    /** The box beside a list that tells of its entry (info()): how wide, and how big its picture is drawn at most. */
    public const INFO_WIDTH = 340.0, PICTURE = 160.0;
    /** A bar's colors by how full it is: [over this part, from, to]; the last for what is left. */
    public const HP_COLORS = [[0.5, 0x8ad870, 0x3e9a4a], [0.2, 0xf0d070, 0xc08a30], [0.0, 0xf08060, 0xb83a30]];
    public const EXP_COLOR = 0x70b0e8;

    private Painter $p;
    /** @var array<string, TextLayout> shaped(), by size and text */
    private array $shapes = [];
    /** The serif found (SERIF), '' for the system's own font; null until looked for, once. */
    private ?string $serif = null;

    /** Draws with $p, the frame's painter, until another frame's. */
    public function on(Painter $p): self
    {
        $this->p = $p;
        return $this;
    }

    /**
     * A panel: dark and a little see-through, darker toward its foot, a gold rule round it and a fainter one inside,
     * a diamond at each corner. Gradients over boxes are MyPHP's fillGradient, drawn as they are; a path's
     * (fillPathGradient) is masked through the glyph atlas, which a band as wide as the view does not fit.
     * @param array{0: float, 1: float, 2: float, 3: float} $b
     */
    public function panel(array $b): void
    {
        [$x, $y, $w, $h] = $b;
        $this->p->fillGradient($b, new LinearGradient(stops: [new GradientStop(self::PANEL[0], 0.0, 0.86), new GradientStop(self::PANEL[1], 1.0, 0.94)]));
        $this->p->stroke($b, self::GOLD, 1.0, 1.5, 0.9);
        $this->p->stroke([$x + 4, $y + 4, $w - 8, $h - 8], self::GOLD_DIM, 0.0, 1.0, 0.8);
        foreach ([[$x, $y], [$x + $w, $y], [$x, $y + $h], [$x + $w, $y + $h]] as [$cx, $cy]) {
            $this->diamond($cx, $cy, 4.5, self::GOLD);
        }
    }

    /** A name between two gold rules that fade outward, a diamond at each rule's inner end, centered on $cx. */
    public function heading(float $cx, float $y, string $text, float $size, float $alpha = 1.0): void
    {
        $w = $this->width($text, $size);
        $this->write($cx - $w / 2, $y, $text, $size, self::INK, $alpha);
        $mid = $y + $size * 0.62;
        foreach ([-1, 1] as $side) {
            $inner = $cx + $side * ($w / 2 + 18);
            [$from, $to] = $side < 0 ? [$inner - 150.0, $inner] : [$inner, $inner + 150.0];
            $this->p->fillGradient([$from, $mid - 0.75, $to - $from, 1.5], new LinearGradient(stops: [new GradientStop(self::GOLD, 0.0, $side < 0 ? 0.0 : $alpha), new GradientStop(self::GOLD, 1.0, $side < 0 ? $alpha : 0.0)], angle: 90));
            $this->diamond($inner, $mid, 4.0, self::GOLD, $alpha);
        }
    }

    /** A heading() on a dark band across the view, fading at its ends, its top at $y: the game's name on the title. */
    public function banner(array $r, float $y, string $text, float $size): void
    {
        $h = $size + 58.0;
        $this->p->fillGradient([$r[0], $y, $r[2], $h], new LinearGradient(stops: [new GradientStop(0x000000, 0.0, 0.0), new GradientStop(0x000000, 0.3, 0.55), new GradientStop(0x000000, 0.7, 0.55), new GradientStop(0x000000, 1.0, 0.0)], angle: 90));
        $this->heading($r[0] + $r[2] / 2, $y + 26, $text, $size);
    }

    public function diamond(float $cx, float $cy, float $r, int $rgb, float $alpha = 1.0): void
    {
        $this->p->fillPath((new Path())->moveTo($cx, $cy - $r)->lineTo($cx + $r, $cy)->lineTo($cx, $cy + $r)->lineTo($cx - $r, $cy)->close(), $rgb, $alpha);
    }

    /** Writes $text, its top left at $x, $y, in the UI's serif. */
    public function write(float $x, float $y, string $text, float $size = Text::SIZE, int $rgb = self::INK, float $alpha = 1.0): void
    {
        $this->p->textLayout($this->shaped($text, $size), $x, $y, $rgb, $alpha);
    }

    /** How wide write() writes $text. */
    public function width(string $text, float $size = Text::SIZE): float
    {
        return $this->shaped($text, $size)->size()[0];
    }

    /**
     * A list at the bottom right of the view, $lift above its foot: as wide as its widest entry, $least at least; the
     * entry the cursor is on with a band of gold fading to the right and a diamond before it, the others dimmer. When
     * $info says something of that entry (info()), its box stands to the list's left, their feet in line.
     * @param array{0: float, 1: float, 2: float, 3: float} $r the view
     * @param list<string> $items
     * @param ?array{0: list<string>, 1: ?Image} $info
     */
    public function list(array $r, array $items, int $cursor, float $least = 200.0, float $lift = 0.0, ?array $info = null): void
    {
        $line = Text::SIZE * self::LIST_LINE;
        $mark = Text::SIZE * 1.2; // the room before an entry for the cursor's diamond
        $w = $least;
        foreach ($items as $text) {
            $w = max($w, $this->width($text) + $mark + 2 * Text::BOX_PADDING + 8);
        }
        $w = min($w, $r[2] - 2 * self::MARGIN);
        $h = 2 * Text::BOX_PADDING + count($items) * $line;
        [$x, $y] = [$r[0] + $r[2] - $w - self::MARGIN, $r[1] + $r[3] - $h - self::MARGIN - $lift];
        $this->panel([$x, $y, $w, $h]);
        foreach ($items as $i => $text) {
            $ty = $y + Text::BOX_PADDING + $i * $line;
            if ($i === $cursor) {
                $this->chosen([$x + 6, $ty - ($line - Text::SIZE) / 2, $w - 12, $line], $x + Text::BOX_PADDING + 5, $ty + Text::SIZE / 2 + 1);
            }
            $this->write($x + Text::BOX_PADDING + $mark, $ty, $text, Text::SIZE, $i === $cursor ? self::INK : self::INK_DIM);
        }
        if ($info !== null) {
            $this->info([$x - self::INFO_WIDTH - 12, $y + $h], ...$info);
        }
    }

    /**
     * A box that tells of a list's entry, its foot's left corner at $foot: a picture (a beast's sprite) over the lines,
     * if there is one, drawn whole in pixels; the lines wrapped to the box.
     * @param array{0: float, 1: float} $foot
     * @param list<string> $lines
     */
    public function info(array $foot, array $lines, ?Image $picture = null): void
    {
        $columns = intdiv((int)((self::INFO_WIDTH - 2 * Text::BOX_PADDING) * 2), (int)self::SMALL);
        $wrapped = array_merge(...array_map(fn(string $l) => Text::wrap($l, $columns), $lines ?: ['']));
        $line = self::SMALL * self::DIALOG_LINE;
        $tall = 0.0;
        if ($picture !== null) {
            [$pw, $ph] = $picture->size();
            $scale = floor(min(self::PICTURE / $pw, self::PICTURE / $ph)); // whole pixels, so that they stay square and sharp
            $tall = $ph * max(1.0, $scale) + Text::BOX_PADDING;
        }
        $h = 2 * Text::BOX_PADDING + $tall + count($wrapped) * $line;
        [$x, $y] = [$foot[0], $foot[1] - $h];
        $this->panel([$x, $y, self::INFO_WIDTH, $h]);
        if ($picture !== null) {
            $s = max(1.0, $scale);
            $this->p->image($picture, [$x + (self::INFO_WIDTH - $pw * $s) / 2, $y + Text::BOX_PADDING, $pw * $s, $ph * $s], Node::FILL_BOX);
        }
        foreach ($wrapped as $i => $text) {
            $this->write($x + Text::BOX_PADDING, $y + Text::BOX_PADDING + $tall + $i * $line, $text, self::SMALL, $i === 0 ? self::INK : self::INK_DIM);
        }
    }

    /**
     * The party as a list: a row each, its name, its level, its status, and its hp as a bar with the numbers; the
     * one the cursor is on marked as list()'s, the one $picked (to be moved) with a diamond of its own.
     * @param array{0: float, 1: float, 2: float, 3: float} $r
     * @param list<Fighter> $party
     */
    public function party(array $r, array $party, int $cursor, ?int $picked = null, float $lift = 0.0): void
    {
        $row = Text::SIZE * 2.6;
        $w = 460.0;
        $h = 2 * Text::BOX_PADDING + count($party) * $row;
        [$x, $y] = [$r[0] + $r[2] - $w - self::MARGIN, $r[1] + $r[3] - $h - self::MARGIN - $lift];
        $this->panel([$x, $y, $w, $h]);
        $mark = Text::SIZE * 1.2;
        foreach ($party as $i => $f) {
            $ty = $y + Text::BOX_PADDING + $i * $row;
            if ($i === $cursor) {
                $this->chosen([$x + 6, $ty - 4, $w - 12, $row - 2], $x + Text::BOX_PADDING + 5, $ty + Text::SIZE / 2 + 1);
            }
            if ($i === $picked) {
                $this->diamond($x + $w - Text::BOX_PADDING - 6, $ty + Text::SIZE / 2 + 1, 5.0, 0xe8a050);
            }
            $left = $x + Text::BOX_PADDING + $mark;
            $ink = $i === $cursor ? self::INK : self::INK_DIM;
            $this->write($left, $ty, $f->species['name'], Text::SIZE, $ink);
            $this->write($left + 120, $ty + 2, 'Lv ' . $f->level, self::SMALL, self::GOLD);
            $state = $f->fainted() ? '倒' : ($f->status === null ? '' : \Shanhai\Battle\Screen::STATUS[$f->status]);
            if ($state !== '') {
                $this->write($left + 190, $ty + 2, $state, self::SMALL, 0xe8a050);
            }
            $numbers = $f->hp . ' / ' . $f->maxHp;
            $right = $x + $w - Text::BOX_PADDING - 22;
            $this->write($right - $this->width($numbers, self::SMALL), $ty + 2, $numbers, self::SMALL, $ink);
            $this->bar([$left, $ty + Text::SIZE + 8, $right - $left, 5.0], $f->hp / max(1, $f->maxHp));
        }
    }

    /**
     * A bar of how much is left: hp in HP_COLORS, by how full it is, lighter at its left; or one color ($rgb, exp's).
     * @param array{0: float, 1: float, 2: float, 3: float} $b
     */
    public function bar(array $b, float $part, ?int $rgb = null): void
    {
        $part = max(0.0, min(1.0, $part));
        $this->p->fill([$b[0] - 1, $b[1] - 1, $b[2] + 2, $b[3] + 2], self::GOLD_DIM, 1.0);
        $this->p->fill($b, 0x0a0c10, 1.0);
        if ($part <= 0.0) {
            return;
        }
        $full = [$b[0], $b[1], max(2.0, $b[2] * $part), $b[3]];
        if ($rgb !== null) {
            $this->p->fill($full, $rgb, 1.0);
            return;
        }
        foreach (self::HP_COLORS as [$over, $from, $to]) {
            if ($part > $over || $over === 0.0) {
                $this->p->fillGradient($full, new LinearGradient(stops: [new GradientStop($from, 0.0, 1.0), new GradientStop($to, 1.0, 1.0)], angle: 90));
                return;
            }
        }
    }

    /**
     * A beast's page (the menu's details): a panel in the middle of the view, its sprite on the left, drawn whole in
     * pixels; on the right, its first line (what it is and how it stands) over its hp as a bar, then the others.
     * @param list<string> $lines Menu::detail's
     */
    public function sheet(array $r, array $lines, ?Image $picture, float $hp): void
    {
        [$w, $h] = [min(860.0, $r[2] - 2 * self::MARGIN), 360.0];
        [$x, $y] = [$r[0] + ($r[2] - $w) / 2, $r[1] + ($r[3] - $h) / 2 - 20];
        $this->panel([$x, $y, $w, $h]);
        $side = 260.0; // the sprite's part
        if ($picture !== null) {
            [$pw, $ph] = $picture->size();
            $s = max(1.0, floor(min(($side - 2 * Text::BOX_PADDING) / $pw, ($h - 2 * Text::BOX_PADDING) / $ph)));
            $this->p->image($picture, [$x + ($side - $pw * $s) / 2, $y + ($h - $ph * $s) / 2, $pw * $s, $ph * $s], Node::FILL_BOX);
        }
        $this->p->fillGradient([$x + $side, $y + 16, 1.0, $h - 32], new LinearGradient(stops: [new GradientStop(self::GOLD, 0.0, 0.0), new GradientStop(self::GOLD, 0.5, 0.7), new GradientStop(self::GOLD, 1.0, 0.0)]));
        $left = $x + $side + Text::BOX_PADDING;
        $width = $x + $w - Text::BOX_PADDING - $left;
        $columns = intdiv((int)($width * 2), (int)self::SMALL);
        $ty = $y + Text::BOX_PADDING + 4;
        foreach ($lines as $i => $line) {
            if ($i === 0) {
                $this->write($left, $ty, $line, Text::SIZE);
                $this->bar([$left, $ty + Text::SIZE + 12, $width, 6.0], $hp);
                $ty += Text::SIZE + 36;
                continue;
            }
            foreach (Text::wrap($line, $columns) as $part) {
                $this->write($left, $ty, $part, self::SMALL, self::INK_DIM);
                $ty += self::SMALL * self::DIALOG_LINE;
            }
            $ty += 8;
        }
    }

    /** How high the dialog box stands, with the room under it: a list asked over it is lifted this much. */
    public static function dialogHeight(): float
    {
        return 2 * Text::BOX_PADDING + Text::LINES * Text::SIZE * self::DIALOG_LINE + self::MARGIN + 12;
    }

    /** The dialog box, at the bottom of the view: the line showing, and the mark that confirm goes on, a diamond bobbing. */
    public function dialog(array $r, string $line): void
    {
        $lh = Text::SIZE * self::DIALOG_LINE;
        $w = Text::BOX_WIDTH;
        $h = 2 * Text::BOX_PADDING + Text::LINES * $lh;
        [$x, $y] = [$r[0] + ($r[2] - $w) / 2, $r[1] + $r[3] - $h - self::MARGIN];
        $this->panel([$x, $y, $w, $h]);
        foreach (Text::wrap($line, Text::columns()) as $i => $text) {
            $this->write($x + Text::BOX_PADDING, $y + Text::BOX_PADDING + $i * $lh, $text);
        }
        $bob = 2.0 * sin(microtime(true) * 5.0);
        $this->diamond($x + $w - Text::BOX_PADDING - 4, $y + $h - Text::BOX_PADDING - 2 + $bob, 5.0, self::GOLD);
    }

    /**
     * A beast's box in a battle, $w wide at $x, $y: its name, level, status, hp as a bar; the player's ($exp not null)
     * with the numbers too, and how far it is to its next level.
     */
    public function status(float $x, float $y, float $w, string $name, int $level, ?string $state, int $hp, int $maxHp, ?float $exp): void
    {
        $h = $exp !== null ? 100.0 : 72.0;
        $this->panel([$x, $y, $w, $h]);
        $this->write($x + 18, $y + 12, $name);
        $lv = 'Lv ' . $level;
        $this->write($x + $w - 18 - $this->width($lv, self::SMALL), $y + 14, $lv, self::SMALL, self::GOLD);
        if ($state !== null) {
            $this->write($x + $w - 110, $y + 14, $state, self::SMALL, 0xe8a050);
        }
        $bar = [$x + 18, $y + 46, $w - 36, 6.0];
        $this->bar($bar, $hp / max(1, $maxHp));
        if ($exp !== null) {
            $numbers = $hp . ' / ' . $maxHp;
            $this->write($bar[0] + $bar[2] - $this->width($numbers, self::SMALL), $y + 56, $numbers, self::SMALL);
            $this->bar([$bar[0], $y + 84, $bar[2], 3.0], $exp, self::EXP_COLOR);
        }
    }

    /** A small box of one line at the top right of the view (what the player has to spend). */
    public function plate(array $r, string $text): void
    {
        $w = $this->width($text, self::SMALL) + 2 * self::PAD;
        $box = [$r[0] + $r[2] - $w - self::MARGIN, $r[1] + self::MARGIN, $w, self::SMALL + 2 * self::PAD];
        $this->panel($box);
        $this->write($box[0] + self::PAD, $box[1] + self::PAD, $text, self::SMALL, self::GOLD);
    }

    /** The band and the diamond of the entry the cursor is on. @param array{0: float, 1: float, 2: float, 3: float} $band */
    private function chosen(array $band, float $mx, float $my): void
    {
        $this->p->fillGradient($band, new LinearGradient(stops: [new GradientStop(self::GOLD, 0.0, 0.32), new GradientStop(self::GOLD, 1.0, 0.0)], angle: 90));
        $this->diamond($mx, $my, 5.0, self::GOLD);
    }

    /** $text shaped in the UI's serif at $size, kept: the same lines are drawn every frame. */
    private function shaped(string $text, float $size): TextLayout
    {
        if (count($this->shapes) > 512) {
            $this->shapes = [];
        }
        $this->serif ??= in_array(self::SERIF, array_map(fn($f) => $f->name, Font::families()), true) ? self::SERIF : '';
        return $this->shapes["$size|$text"] ??= TextLayout::shape($this->p->textPainter()->textSystem(), [new Span($text, size: $size, font: $this->serif === '' ? null : $this->serif)], new TextStyle(), $size);
    }
}
