<?php
declare(strict_types=1);

namespace Shanhai\Render;

use MyPHP\Plugins\Scene\{Entity, Material, Mesh};
use MyPHP\Plugins\Scene\Math\Vec3;

/**
 * The models of art/: figures built of small blocks, thirty-two to a tile's side, written as boxes of voxels.
 *
 * A model is a PHP file that returns
 *   colors  a character => '#rrggbb', or null for a color given when it is drawn (a person's robe by their look)
 *   parts   a name => [] for 'body', the root, which stands at the model's origin; for another part ['at' => its pivot,
 *           a voxel's corner, 'swing' => [axis, amplitude, frequency]] when it moves on MyPHP's Animator3D
 *   boxes   [part, color, from, to]: every voxel from one corner to the other, both included, made that part and color,
 *           over what an earlier box made; the color '.' takes them away
 * and, for a beast, 'looks': the passage of data/beasts.php it is drawn from (a test holds the two the same).
 * A rounded shape is written with ball(), which gives its boxes.
 * A model's origin is the ground under its middle, y up; it is built facing south (+z), as the actors are.
 * What is wrong with one throws where it is read.
 *
 * faces() is PHP alone, so the tests read every model with no window; mesh(), solid() and entity() make MyPHP's.
 * A model's vertices are worked out once and kept, packed, for each set of colors it is drawn in: a map's things and
 * people are built again each time the map changes, and working them out again is what made the change stop a moment.
 */
final class Voxels
{
    /** How many voxels make a tile's side. */
    public const PER_TILE = 32;
    /** A voxel's side, in tiles. */
    private const SIDE = 1.0 / self::PER_TILE;
    /** How far a voxel's shade may be from its color's, either way: the grain of the blocks. */
    public const GRAIN = 0.05;
    public const DIR = __DIR__ . '/../../art';
    /** A face's four corners about a voxel's lowest corner, counterclockwise seen from outside, by its normal. */
    private const FACES = [
        [[0, 1, 0], [[0, 1, 1], [1, 1, 1], [1, 1, 0], [0, 1, 0]]],
        [[0, -1, 0], [[0, 0, 0], [1, 0, 0], [1, 0, 1], [0, 0, 1]]],
        [[0, 0, 1], [[0, 0, 1], [1, 0, 1], [1, 1, 1], [0, 1, 1]]],
        [[0, 0, -1], [[1, 0, 0], [0, 0, 0], [0, 1, 0], [1, 1, 0]]],
        [[1, 0, 0], [[1, 0, 1], [1, 0, 0], [1, 1, 0], [1, 1, 1]]],
        [[-1, 0, 0], [[0, 0, 0], [0, 0, 1], [0, 1, 1], [0, 1, 0]]],
    ];

    /** @var array<string, array> by name, as read */
    private static array $read = [];
    /** @var array<string, array<string, array{0: string, 1: string, 2: int, 3: int}>> by name and colors: each part's vertices and indices, packed, and their counts */
    private static array $packed = [];

    /** Whether art/ has the model: 'beasts/lushu', 'people/person'. */
    public static function has(string $name): bool
    {
        return is_file(self::DIR . "/$name.php");
    }

    /** The model, its file read and checked. */
    public static function load(string $name): array
    {
        return self::$read[$name] ??= self::check(require self::DIR . "/$name.php", $name);
    }

    /** @return list<string> every model of art/, by name */
    public static function names(): array
    {
        $dir = realpath(self::DIR);
        return array_map(fn(string $f) => substr($f, strlen($dir) + 1, -4), glob("$dir/*/*.php"));
    }

    /**
     * Its voxels, each box over the ones before.
     * @return array<string, array<string, string>> part => "x,y,z" => color
     */
    public static function voxels(array $model): array
    {
        $at = [];
        foreach ($model['boxes'] as [$part, $color, $from, $to]) {
            for ($x = $from[0]; $x <= $to[0]; $x++) {
                for ($y = $from[1]; $y <= $to[1]; $y++) {
                    for ($z = $from[2]; $z <= $to[2]; $z++) {
                        if ($color === '.') {
                            unset($at["$x,$y,$z"]);
                        } else {
                            $at["$x,$y,$z"] = [$part, $color];
                        }
                    }
                }
            }
        }
        $parts = array_fill_keys(array_keys($model['parts']), []);
        foreach ($at as $key => [$part, $color]) {
            $parts[$part][$key] = $color;
        }
        foreach ($parts as $part => $voxels) {
            if ($voxels === []) {
                throw new \UnexpectedValueException("the part $part has no voxels");
            }
        }
        return $parts;
    }

    /**
     * Each part's faces that can be seen, in tiles from its pivot: a voxel's side is one unless a voxel of the same part
     * lies against it (a part that moves must not open a hole). Each in Stage::faces' form, its color with its grain.
     * @param array<string, array{0: float, 1: float, 2: float, 3: float}> $colors what the model's null colors are
     * @return array<string, list<array{0: list<array{0: float, 1: float, 2: float}>, 1: array{0: float, 1: float, 2: float}, 2: array}>>
     */
    public static function faces(array $model, array $colors = []): array
    {
        $rgba = [];
        foreach ($model['colors'] as $key => $hex) {
            $rgba[$key] = $hex === null
                ? ($colors[$key] ?? throw new \InvalidArgumentException("the color $key is given when drawn, and was not"))
                : [...array_map(fn(string $h) => hexdec($h) / 255, str_split(substr($hex, 1), 2)), 1.0];
        }
        $out = [];
        foreach (self::voxels($model) as $part => $voxels) {
            $pivot = $model['parts'][$part]['at'] ?? [0, 0, 0];
            $out[$part] = [];
            foreach ($voxels as $key => $color) {
                [$x, $y, $z] = array_map('intval', explode(',', $key));
                $shade = self::shade($rgba[$color], $x, $y, $z);
                foreach (self::FACES as [$n, $corners]) {
                    if (isset($voxels[($x + $n[0]) . ',' . ($y + $n[1]) . ',' . ($z + $n[2])])) {
                        continue;
                    }
                    $out[$part][] = [
                        array_map(fn(array $c) => [
                            ($x + $c[0] - $pivot[0]) * self::SIDE,
                            ($y + $c[1] - $pivot[1]) * self::SIDE,
                            ($z + $c[2] - $pivot[2]) * self::SIDE,
                        ], $corners),
                        array_map('floatval', $n),
                        $shade,
                    ];
                }
            }
        }
        return $out;
    }

    /**
     * The boxes of a ball of one part and color about a voxel's corner, a row of x a box; none below the ground.
     * Squashed or stretched by the radii of its three axes.
     * @return list<array> boxes, as a model writes them
     */
    public static function ball(string $part, string $color, array $center, int $rx, int $ry, int $rz): array
    {
        [$cx, $cy, $cz] = $center;
        $boxes = [];
        for ($y = -$ry; $y < $ry; $y++) {
            for ($z = -$rz; $z < $rz; $z++) {
                // the middle of the voxel's row, against the ellipsoid
                $left = 1.0 - (($y + 0.5) / $ry) ** 2 - (($z + 0.5) / $rz) ** 2;
                if ($left <= 0.0 || $cy + $y < 0) {
                    continue;
                }
                $w = (int)round($rx * sqrt($left));
                if ($w > 0) {
                    $boxes[] = [$part, $color, [$cx - $w, $cy + $y, $cz + $z], [$cx + $w - 1, $cy + $y, $cz + $z]];
                }
            }
        }
        return $boxes;
    }

    /** A color made a little lighter or darker by where it is, the same each time: one voxel's, one tile's. */
    public static function shade(array $rgba, int ...$at): array
    {
        $f = 1.0 + self::GRAIN * (2.0 * (crc32(implode(',', $at)) % 1000) / 999.0 - 1.0);
        return [min(1.0, $rgba[0] * $f), min(1.0, $rgba[1] * $f), min(1.0, $rgba[2] * $f), $rgba[3]];
    }

    /** One mesh of faces in Stage::faces' form; each face's corners its vertices. */
    public static function mesh(array $faces): Mesh
    {
        return new Mesh(...self::pack($faces));
    }

    /**
     * Faces packed as MyPHP's Mesh takes them: the vertices (position, normal, uv, color), the indices, their counts.
     * @return array{0: string, 1: string, 2: int, 3: int}
     */
    private static function pack(array $faces): array
    {
        $vertices = $indices = [];
        foreach ($faces as $i => [$corners, $normal, $color]) {
            foreach ($corners as $k => $corner) {
                array_push($vertices, ...$corner, ...$normal, ...[[0.0, 1.0], [1.0, 1.0], [1.0, 0.0], [0.0, 0.0]][$k], ...$color);
            }
            array_push($indices, 4 * $i, 4 * $i + 1, 4 * $i + 2, 4 * $i, 4 * $i + 2, 4 * $i + 3);
        }
        return [pack('f*', ...$vertices), pack('V*', ...$indices), 4 * count($faces), 6 * count($faces)];
    }

    /** A model's parts packed (pack()), worked out the first time it is drawn in these colors and kept. @return array<string, array{0: string, 1: string, 2: int, 3: int}> */
    private static function packed(string $name, array $colors): array
    {
        return self::$packed[$name . json_encode($colors)] ??= array_map(self::pack(...), self::faces(self::load($name), $colors));
    }

    /** Works a model out in these colors ahead of its first drawing, so that drawing it later takes no time. */
    public static function prepare(string $name, array $colors = []): void
    {
        self::packed($name, $colors);
    }

    /** A model of a body alone, as one mesh: what a map draws many of at once. */
    public static function solid(string $name): Mesh
    {
        $parts = self::packed($name, []);
        if (array_keys($parts) !== ['body']) {
            throw new \InvalidArgumentException("$name has parts that move: it is not drawn as one mesh");
        }
        return new Mesh(...$parts['body']);
    }

    /**
     * A model of art/ as entities: the body, the other parts its children at their pivots.
     * @return array{0: Entity, 1: array<string, Entity>} the body, and every part by name (the body among them)
     */
    public static function entity(string $model, array $colors = [], string $name = ''): array
    {
        $parts = [];
        foreach (self::packed($model, $colors) as $part => $packed) {
            $at = self::load($model)['parts'][$part]['at'] ?? [0, 0, 0];
            $parts[$part] = new Entity(new Mesh(...$packed), new Material(shininess: 8.0),
                position: new Vec3($at[0] * self::SIDE, $at[1] * self::SIDE, $at[2] * self::SIDE),
                name: $part === 'body' ? $name : '');
        }
        foreach ($parts as $part => $e) {
            if ($part !== 'body') {
                $parts['body']->addChild($e);
            }
        }
        return [$parts['body'], $parts];
    }

    /** The model as written, checked: what is wrong throws, naming the model. */
    private static function check(array $m, string $name): array
    {
        $fail = fn(string $what) => throw new \UnexpectedValueException("art/$name.php: $what");
        foreach (['colors', 'parts', 'boxes'] as $key) {
            is_array($m[$key] ?? null) || $fail("no $key");
        }
        foreach ($m['colors'] as $key => $hex) {
            (is_string($key) && strlen($key) === 1 && $key !== '.') || $fail("a color is one character, not '.': $key");
            ($hex === null || preg_match('/^#[0-9a-f]{6}$/', $hex) === 1) || $fail("the color $key is not #rrggbb or null");
        }
        ($m['parts']['body'] ?? null) === [] || $fail('no body, or a body with a pivot or a swing');
        foreach ($m['parts'] as $part => $p) {
            if ($part === 'body') {
                continue;
            }
            (is_array($p['at'] ?? null) && count($p['at']) === 3) || $fail("the part $part has no pivot");
            array_diff(array_keys($p), ['at', 'swing']) === [] || $fail("the part $part has what a part does not");
            if (isset($p['swing'])) {
                [$axis, $amplitude, $frequency] = $p['swing'];
                (in_array($axis, ['x', 'y', 'z'], true) && is_float($amplitude) && is_float($frequency)) || $fail("the part $part's swing");
            }
        }
        foreach ($m['boxes'] as $i => $box) {
            [$part, $color, $from, $to] = $box + [null, null, null, null];
            isset($m['parts'][$part]) || $fail("box $i: no part $part");
            ($color === '.' || array_key_exists($color, $m['colors'])) || $fail("box $i: no color $color");
            (count($from) === 3 && count($to) === 3) || $fail("box $i: a corner is three numbers");
            ($from[0] <= $to[0] && $from[1] <= $to[1] && $from[2] <= $to[2]) || $fail("box $i: from is past to");
            $from[1] >= 0 || $fail("box $i: below the ground");
        }
        return $m;
    }
}
