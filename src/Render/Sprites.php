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
 *   outline  '#rrggbb': drawn round it, on every empty pixel beside one that is not (up, down, left or right)
 *   views    a way it faces ('down', 'up', 'left') => its frames, standing first, then the steps of a walk;
 *            a frame is its rows, top first, a character a pixel, '.' empty
 * Facing right is facing left turned over. What is wrong with one throws where it is read.
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
        $outline = self::bytes($s['outline']);
        $filled = fn(int $x, int $y) => $x >= 0 && $y >= 0 && $x < $w && $y < $h && $rows[$y][$x] !== '.';
        $out = '';
        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                $c = $rows[$y][$x];
                if ($c !== '.') {
                    $out .= pack('C4', ...[...$palette[$c], 255]);
                } elseif ($filled($x - 1, $y) || $filled($x + 1, $y) || $filled($x, $y - 1) || $filled($x, $y + 1)) {
                    $out .= pack('C4', ...[...$outline, 255]);
                } else {
                    $out .= "\0\0\0\0";
                }
            }
        }
        return $out;
    }

    /** A frame as MyPHP's Image, pixelated (drawn in hard pixels, not smoothed), made once. @param array<string, string> $colors */
    public static function image(string $name, string $dir, int $frame, array $colors = []): Image
    {
        [$w, $h] = self::load($name)['size'];
        return self::$images[$name . json_encode($colors) . "$dir/$frame"] ??= Image::fromPixels($w, $h, self::rgba($name, $dir, $frame, $colors))->pixelated();
    }

    /** Makes every frame of it, every way, ahead: once, as the game starts. @param array<string, string> $colors */
    public static function prepare(string $name, array $colors = []): void
    {
        foreach ([...self::VIEWS, 'right'] as $dir) {
            for ($f = 0; $f < self::frames($name); $f++) {
                self::image($name, $dir, $f, $colors);
            }
        }
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
        (is_string($s['outline'] ?? null) && $hex($s['outline'])) || $fail('no outline');
        $known = array_merge(['.'], array_map('strval', array_keys($s['colors'])), array_map('strval', array_keys($s['shades'])));
        array_keys($s['views'] ?? []) === self::VIEWS || $fail('the views are not ' . implode(', ', self::VIEWS) . ', in that order');
        $count = count($s['views']['down']);
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
