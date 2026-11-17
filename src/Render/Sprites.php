<?php
declare(strict_types=1);

namespace Shanhai\Render;

use MyPHP\UI\Image;

/**
 * The pixel sprites of art/sprites/: HD-2D's figures (docs/art.md), drawn flat in pixels and stood up in the 3D scene.
 *
 * A sprite is a PHP file that returns
 *   size     [width, height] in pixels, Sprites::PER_TILE to a tile
 *   colors   a character => '#rrggbb', or null for a color given when it is drawn (a person's robe by their look)
 *   shades   a character => [another character, a factor]: that one's color made lighter or darker (a robe in shade)
 *   outline  '#rrggbb': drawn round it, on every empty pixel beside one that is not (up, down, left or right);
 *            null for none (a ground's texture, which has no empty pixel)
 *   round    true for a figure drawn round (a person): shaded across as a body is, lit from the left (round())
 *   views    a way it faces ('down', 'up', 'left') => its frames, standing first, then the steps of a walk;
 *            a frame is its rows, top first, a character a pixel, '.' empty. A thing that never turns (a tree,
 *            a ground's texture) has only 'down', and one frame.
 * Facing right is facing left turned over. What is wrong with one throws where it is read.
 * A sprite's rows may be written by hand or painted by code in its file, with the helpers here (blank(), blob(),
 * noise()), as Voxels::ball writes a model's boxes: the same each time, so it is still the file that says what it is.
 *
 * rgba() is PHP alone, so the tests read every sprite with no window; image() makes MyPHP's Image of a frame,
 * pixelated, once for each set of colors, and keeps it.
 */
final class Sprites
{
    /** How many pixels make a tile's side, as the voxels' (Voxels::PER_TILE). */
    public const PER_TILE = 32;
    public const DIR = __DIR__ . '/../../art/sprites';
    /** The ways a sprite is written facing; right is left turned over. */
    public const VIEWS = ['down', 'up', 'left'];

    /** The bands of a round figure's shading (round()), as factors of its colors, from the shaded side to the lit. */
    private const ROUND = [0.52, 0.68, 0.84, 1.0, 1.14];
    /** The thresholds of ordered dithering, a 4 by 4 Bayer pattern, from 0 to 1. */
    private const BAYER = [[0 / 16, 8 / 16, 2 / 16, 10 / 16], [12 / 16, 4 / 16, 14 / 16, 6 / 16], [3 / 16, 11 / 16, 1 / 16, 9 / 16], [15 / 16, 7 / 16, 13 / 16, 5 / 16]];

    /** @var array<string, array> by name, as read */
    private static array $read = [];
    /** @var array<string, Image> by name, colors, way and frame */
    private static array $images = [];

    public static function has(string $name): bool
    {
        return is_file(self::DIR . "/$name.php");
    }

    /** The sprite, its file read and checked. */
    public static function load(string $name): array
    {
        return self::$read[$name] ??= self::check(require self::DIR . "/$name.php", $name);
    }

    /** Whether it turns: has the views of every way, not 'down' alone. */
    public static function turns(string $name): bool
    {
        return count(self::load($name)['views']) > 1;
    }

    /** How many frames it has a way (a standing one and the walk's). */
    public static function frames(string $name): int
    {
        return count(self::load($name)['views']['down']);
    }

    /** Its size in tiles: [width, height]. @return array{0: float, 1: float} */
    public static function size(string $name): array
    {
        [$w, $h] = self::load($name)['size'];
        return [$w / self::PER_TILE, $h / self::PER_TILE];
    }

    /**
     * A frame's pixels, RGBA, a byte each, rows top first: facing $dir ('down', 'up', 'left', 'right'), with $colors
     * for the colors the file leaves to the drawing.
     * @param array<string, string> $colors a character => '#rrggbb'
     */
    public static function rgba(string $name, string $dir, int $frame, array $colors = []): string
    {
        $s = self::load($name);
        [$w, $h] = $s['size'];
        $dir = count($s['views']) === 1 ? 'down' : $dir;
        $rows = $s['views'][$dir === 'right' ? 'left' : $dir][$frame] ?? throw new \OutOfRangeException("$name: no frame $frame facing $dir");
        if ($dir === 'right') {
            $rows = array_map(strrev(...), $rows);
        }
        $palette = [];
        foreach ($s['colors'] as $c => $hex) {
            $hex = $colors[$c] ?? $hex ?? throw new \InvalidArgumentException("$name: the color '$c' is given when drawn, and was not");
            $palette[$c] = self::bytes($hex);
        }
        foreach ($s['shades'] as $c => [$of, $factor]) {
            $palette[$c] = array_map(fn(int $v) => min(255, (int)round($v * $factor)), $palette[$of]);
        }
        $outline = $s['outline'] === null ? null : self::bytes($s['outline']);
        $filled = fn(int $x, int $y) => $x >= 0 && $y >= 0 && $x < $w && $y < $h && $rows[$y][$x] !== '.';
        $light = ($s['round'] ?? false) ? self::round($rows) : null;
        $out = '';
        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                $c = $rows[$y][$x];
                if ($c !== '.') {
                    $rgb = $light === null ? $palette[$c] : array_map(fn(int $v) => min(255, (int)round($v * $light[$y][$x])), $palette[$c]);
                    $out .= pack('C4', ...[...$rgb, 255]);
                } elseif ($outline !== null && ($filled($x - 1, $y) || $filled($x + 1, $y) || $filled($x, $y - 1) || $filled($x, $y + 1))) {
                    $out .= pack('C4', ...[...$outline, 255]);
                } else {
                    $out .= "\0\0\0\0";
                }
            }
        }
        return $out;
    }

    /**
     * Pixels $w wide, RGBA, faded: every other one left out, as a checkerboard, so that what is behind shows through
     * without the scene blending; as HD-2D fades what stands between the camera and the player.
     */
    public static function fade(string $rgba, int $w): string
    {
        $px = str_split($rgba, 4);
        foreach ($px as $i => $p) {
            if ((($i % $w) + intdiv($i, $w)) % 2 === 1) {
                $px[$i] = "\0\0\0\0";
            }
        }
        return implode('', $px);
    }

    /**
     * A frame as MyPHP's Image, pixelated (drawn in hard pixels, not smoothed), made once; $faded, every other pixel left
     * out (fade()).
     * @param array<string, string> $colors
     */
    public static function image(string $name, string $dir, int $frame, array $colors = [], bool $faded = false): Image
    {
        [$w, $h] = self::load($name)['size'];
        return self::$images[$name . json_encode($colors) . "$dir/$frame" . ($faded ? '/faded' : '')] ??= Image::fromPixels($w, $h, $faded
            ? self::fade(self::rgba($name, $dir, $frame, $colors), $w)
            : self::rgba($name, $dir, $frame, $colors))->pixelated();
    }

    /** Makes every frame of it, every way, ahead: once, as the game starts. @param array<string, string> $colors */
    public static function prepare(string $name, array $colors = []): void
    {
        foreach (self::turns($name) ? [...self::VIEWS, 'right'] : ['down'] as $dir) {
            for ($f = 0; $f < self::frames($name); $f++) {
                self::image($name, $dir, $f, $colors);
            }
        }
    }

    /** Rows of $w by $h empty pixels, to paint on. @return list<string> */
    public static function blank(int $w, int $h): array
    {
        return array_fill(0, $h, str_repeat('.', $w));
    }

    /**
     * Paints on $rows a round lump at $cx, $cy, $rx by $ry pixels, shaded as a ball lit from the top left and a little
     * from the front: $ramp's characters from the darkest to the lightest, a band of the shade each, their edges
     * dithered (a 4 by 4 Bayer pattern), as pixel art shades a round thing. Over what was painted before.
     * @param list<string> $rows
     */
    public static function blob(array &$rows, float $cx, float $cy, float $rx, float $ry, string $ramp): void
    {
        [$lx, $ly, $lz] = [-0.55, -0.62, 0.56]; // toward the light: left, up (rows go down), out
        $n = strlen($ramp);
        for ($y = max(0, (int)floor($cy - $ry)); $y <= min(count($rows) - 1, (int)ceil($cy + $ry)); $y++) {
            for ($x = max(0, (int)floor($cx - $rx)); $x <= min(strlen($rows[0]) - 1, (int)ceil($cx + $rx)); $x++) {
                [$dx, $dy] = [($x + 0.5 - $cx) / $rx, ($y + 0.5 - $cy) / $ry];
                $d = $dx * $dx + $dy * $dy;
                if ($d > 1.0) {
                    continue;
                }
                $lit = $dx * $lx + $dy * $ly + sqrt(1.0 - $d) * $lz; // -1 to 1
                $v = max(0.0, min(0.999, ($lit + 0.2) / 1.2)) * $n + self::BAYER[$y % 4][$x % 4] - 0.5;
                $rows[$y][$x] = $ramp[max(0, min($n - 1, (int)floor($v)))];
            }
        }
    }

    /**
     * How much lighter or darker each pixel of a round figure is (a factor of its color): each row's runs of pixels
     * taken as the cross-section of something round (a leg, the body, the head), its normal turning from the left
     * edge to the right, lit from the left and the front, and a little from above; in ROUND's bands, their edges
     * dithered. So a figure drawn flat shows as a body, lit on one side and in shade on the other, as HD-2D's do.
     * @param list<string> $rows
     * @return list<list<float>>
     */
    public static function round(array $rows): array
    {
        $h = count($rows);
        $w = strlen($rows[0]);
        $n = count(self::ROUND);
        $top = array_fill(0, $w, $h);
        foreach ($rows as $y => $row) {
            for ($x = 0; $x < $w; $x++) {
                if ($row[$x] !== '.' && $top[$x] === $h) {
                    $top[$x] = $y;
                }
            }
        }
        $out = [];
        foreach ($rows as $y => $row) {
            $out[$y] = array_fill(0, $w, 1.0);
            preg_match_all('/[^.]+/', $row, $runs, PREG_OFFSET_CAPTURE);
            foreach ($runs[0] as [$run, $from]) {
                $len = strlen($run);
                for ($x = $from; $x < $from + $len; $x++) {
                    $u = $len < 2 ? 0.0 : 2.0 * ($x - $from + 0.5) / $len - 1.0; // -1 at the left edge, 1 at the right
                    $lit = -0.85 * $u + 0.5 * sqrt(max(0.0, 1.0 - $u * $u)) + ($y - $top[$x] < 2 ? 0.25 : 0.0);
                    $v = max(0.0, min(0.999, ($lit + 0.85) / 1.6)) * $n + self::BAYER[$y % 4][$x % 4] - 0.5;
                    $out[$y][$x] = self::ROUND[max(0, min($n - 1, (int)floor($v)))];
                }
            }
        }
        return $out;
    }

    /** A number from 0 to 1 by a place and a seed, the same each time: a sprite's or a texture's scatter. */
    public static function noise(int $x, int $y, string $seed = ''): float
    {
        return (crc32("$seed $x,$y") % 10007) / 10007;
    }

    /** '#rrggbb' as MyPHP's colors, 0 to 1. @return array{0: float, 1: float, 2: float, 3: float} */
    public static function color(string $hex): array
    {
        return [...array_map(fn(int $v) => $v / 255, self::bytes($hex)), 1.0];
    }

    /** @return array{0: int, 1: int, 2: int} */
    private static function bytes(string $hex): array
    {
        return [hexdec(substr($hex, 1, 2)), hexdec(substr($hex, 3, 2)), hexdec(substr($hex, 5, 2))];
    }

    private static function check(array $s, string $name): array
    {
        $fail = fn(string $why) => throw new \UnexpectedValueException("art/sprites/$name: $why");
        $hex = fn(?string $c) => $c === null || preg_match('/^#[0-9a-f]{6}$/', $c) === 1;
        [$w, $h] = $s['size'] ?? $fail('no size');
        foreach ($s['colors'] ?? $fail('no colors') as $c => $color) {
            (is_string($c) && strlen($c) === 1 && $c !== '.' && $hex($color)) || $fail("the color '$c' is not one character to '#rrggbb' or null");
        }
        foreach ($s['shades'] ?? [] as $c => $shade) {
            isset($s['colors'][$c]) && $fail("'$c' is both a color and a shade");
            (is_array($shade) && array_key_exists($shade[0] ?? '', $s['colors']) && is_float($shade[1] ?? null)) || $fail("the shade '$c' is not [a color, a factor]");
        }
        $s['shades'] ??= [];
        is_bool($s['round'] ?? false) || $fail('round is not true or false');
        array_key_exists('outline', $s) && (is_string($s['outline']) && $hex($s['outline']) || $s['outline'] === null) || $fail('no outline, nor null for none');
        $known = array_merge(['.'], array_map('strval', array_keys($s['colors'])), array_map('strval', array_keys($s['shades'])));
        $ways = array_keys($s['views'] ?? []);
        $ways === self::VIEWS || $ways === ['down'] || $fail('the views are not ' . implode(', ', self::VIEWS) . ', in that order, nor down alone');
        $count = count($s['views']['down']);
        $ways === ['down'] && $count !== 1 && $fail('a thing that never turns has one frame');
        foreach ($s['views'] as $dir => $frames) {
            count($frames) === $count || $fail("$dir has " . count($frames) . " frames, down $count");
            foreach ($frames as $f => $rows) {
                count($rows) === $h || $fail("$dir $f is " . count($rows) . " rows high, not $h");
                foreach ($rows as $y => $row) {
                    strlen($row) === $w || $fail("$dir $f, row $y, is " . strlen($row) . " pixels wide, not $w");
                    ($bad = array_diff(str_split($row), $known)) === [] || $fail("$dir $f, row $y: no color '" . reset($bad) . "'");
                }
            }
        }
        return $s;
    }
}
