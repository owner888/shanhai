<?php
declare(strict_types=1);

namespace Shanhai\Render;

use MyPHP\Plugins\Scene\{Camera3D, Entity, Material, Scene};
use MyPHP\UI\Image;
use MyPHP\Plugins\Scene\Math\{Mat4, Vec3};
use Shanhai\World\{Actor, Dir, Map, Tile, World};

/**
 * The 3D scene of the map the player is on (MyPHP's Plugins\Scene): a tile is one unit, a map's x the scene's x
 * and its y the scene's z. Built once for a map: the ground and the walls each one mesh of the faces that can be
 * seen (faces()), each tile a little lighter or darker than its kind's color (Voxels::shade); each kind of thing standing
 * on it (a model of art/things) one instanced draw. The ground and the walls are drawn through a grain of pixels
 * (grain()), each tile's top turned one of eight ways so that the tiles do not repeat.
 * The actors are HD-2D's sprites (docs/art.md): art/sprites' person, robed in their look's color, stood upright on
 * their tile and drawn a little taller than written, so that the camera's slant shows them as written; a dithered
 * shadow under each. sync() then moves them, shows each its frame for the way it faces and its step, and moves the
 * camera, which looks down at the player from a fixed angle and never turns.
 */
final class Stage
{
    /** Where the camera stands from the player: up and back (south), and its vertical field of view in degrees. */
    public const CAMERA_HEIGHT = 6.0, CAMERA_BACK = 9.0, CAMERA_FOV = 32.0;

    /** The ground's color under each kind of tile: the blue-green landscape's (docs/art.md) 石绿 grass, 赭 paths, 石青 water. */
    public const GROUND = [
        'grass' => [0.45, 0.66, 0.42, 1.0],
        'tallGrass' => [0.33, 0.56, 0.36, 1.0],
        'scree' => [0.58, 0.56, 0.52, 1.0],
        'sand' => [0.84, 0.78, 0.62, 1.0],
        'path' => [0.78, 0.63, 0.43, 1.0],
        'floor' => [0.62, 0.46, 0.30, 1.0],
        'door' => [0.30, 0.20, 0.14, 1.0],
        'tree' => [0.45, 0.66, 0.42, 1.0],
        'rock' => [0.45, 0.66, 0.42, 1.0],
        'wall' => [0.45, 0.40, 0.36, 1.0],
        'water' => [0.13, 0.47, 0.62, 1.0],
    ];
    /** An actor's robe color, by its look. */
    public const LOOKS = [
        'player' => '#d94040',
        'elder' => '#8c8c99',
        'guard' => '#4d66b3',
        'shaman' => '#8c4da6',
        'god' => '#f2cc4d',
        'foe' => '#995933',
    ];
    /** How high the ground's top is, the water's, a wall's; and how far down the ground and the water go, which shows at a map's edge. */
    public const GROUND_TOP = 0.0, WATER_TOP = -0.12, WALL_TOP = 1.3, GROUND_BOTTOM = -0.2, WATER_BOTTOM = -0.32;
    private const WALL = [0.88, 0.85, 0.78, 1.0];
    /** What a model's own colors are drawn through: white, so they are as written. */
    private const AS_WRITTEN = [1.0, 1.0, 1.0, 1.0];
    /** The grain the ground and the walls are drawn through: its pixels' brightness, a tile's side of them, and how many of each. */
    private const GRAIN = [[1.0, 6], [0.93, 3], [0.86, 1]];
    /** The dithered shadow under an actor: its size in tiles, its pixels, and its color. */
    private const SHADOW = [0.7, 0.35], SHADOW_PIXELS = [16, 8], SHADOW_COLOR = '#1c2a1c';

    public readonly Scene $scene;
    /** @var array<string, Entity> by actor id */
    private array $actors = [];
    /** @var array<string, string> by actor id: its look's robe, '#rrggbb' */
    private array $robes = [];
    private static ?Image $grain = null, $shadow = null;

    public function __construct(World $w)
    {
        $this->scene = new Scene(new Camera3D(fovYDegrees: self::CAMERA_FOV));
        $this->scene->clearColor = [0.05, 0.07, 0.10, 1.0];
        $this->scene->shadowsEnabled = true;
        $this->scene->shadowBounds = 12.0;
        if (!$w->map->indoor) {
            $this->scene->addSkyDome(); // MyPHP's: beyond the map's edge is sky and far ground, not the dark of a room
        }

        $trees = $grass = $rocks = [];
        $map = $w->map;
        $this->faced(self::faces($map, self::ground(...)), shadow: false);
        $this->faced(self::faces($map, self::wall(...)), shadow: true);
        for ($y = 0; $y < $map->height; $y++) {
            for ($x = 0; $x < $map->width; $x++) {
                $kind = Tile::name($map->tile($x, $y));
                switch ($kind) {
                    case 'tree':
                        $trees[] = Mat4::translation($x, self::GROUND_TOP, $y);
                        break;
                    case 'tallGrass':
                        $grass[] = Mat4::translation($x, self::GROUND_TOP, $y);
                        break;
                    case 'rock':
                        $rocks[] = Mat4::translation($x, self::GROUND_TOP, $y);
                        break;
                }
            }
        }
        $this->instanced('things/tree', $trees);
        $this->instanced('things/tallGrass', $grass, shadow: false);
        $this->instanced('things/rock', $rocks);
        $this->instanced('things/sign', array_map(fn(array $at) => Mat4::translation($at[0], self::GROUND_TOP, $at[1]), $map->signs));

        foreach ([$w->player, ...array_values($w->npcs)] as $a) {
            $this->robes[$a->id] = self::LOOKS[$a->look];
            $this->actors[$a->id] = $this->scene->add(self::figure($a));
        }
        $this->sync($w, 0.0);
    }

    /** Puts the actors where the world has them, $alpha (0 to 1) into the next update, and the camera over the player. */
    public function sync(World $w, float $alpha): void
    {
        foreach ([$w->player, ...array_values($w->npcs)] as $a) {
            [$x, $z] = $a->place($alpha);
            $e = $this->actors[$a->id];
            $e->position = new Vec3($x, self::GROUND_TOP, $z);
            $e->material->texture = Sprites::image('person', $a->dir->value, self::step($a, $alpha), ['r' => $this->robes[$a->id]]);
        }
        [$px, $pz] = $w->player->place($alpha);
        $this->scene->camera->target = new Vec3($px, 0.0, $pz);
        $this->scene->camera->position = new Vec3($px, self::CAMERA_HEIGHT, $pz + self::CAMERA_BACK);
    }

    /** How much taller an actor stands than its sprite is written: an upright thing seen down the camera's slant shows
     * shorter by the slant's cosine, so stood that much taller it shows as written. */
    public static function stretch(): float
    {
        return hypot(self::CAMERA_HEIGHT, self::CAMERA_BACK) / self::CAMERA_BACK;
    }

    /**
     * The frame of its sprite an actor shows, $alpha into the next update: standing still, the standing one (0); walking,
     * a step's first half one foot forward (1, or 2 on every other tile), its second half standing, as a walk of four.
     */
    public static function step(Actor $a, float $alpha): int
    {
        if (!$a->moving || ($a->progress + $alpha) / World::WALK_STEPS >= 0.5) {
            return 0;
        }
        return ($a->x + $a->y) % 2 === 0 ? 1 : 2;
    }

    /** Works out ahead what every map draws: its things' models (Voxels::prepare), and a person's sprite in each look's robe. Once, as the game starts. */
    public static function prepare(): void
    {
        foreach (['things/tree', 'things/tallGrass', 'things/rock', 'things/sign'] as $thing) {
            Voxels::prepare($thing);
        }
        foreach (self::LOOKS as $robe) {
            Sprites::prepare('person', ['r' => $robe]);
        }
    }

    /** The block of ground of a tile, a kind at a place: its top, its bottom, its color with the place's shade. Water lies lower. */
    public static function ground(string $kind, int $x, int $y): array
    {
        $color = Voxels::shade(self::GROUND[$kind], $x, $y);
        return $kind === 'water' ? [self::WATER_TOP, self::WATER_BOTTOM, $color] : [self::GROUND_TOP, self::GROUND_BOTTOM, $color];
    }

    /** A wall tile's block, standing on the ground; none for another tile. */
    public static function wall(string $kind, int $x, int $y): ?array
    {
        return $kind === 'wall' ? [self::WALL_TOP, self::GROUND_TOP, Voxels::shade(self::WALL, $x, $y)] : null;
    }

    /**
     * The faces of a map's blocks that can be seen, a block a tile: $block gives a kind of tile's, at a place, [top, bottom, color],
     * or null for none. A block's top is a face; a side is one only above the top of the block beside it, down to its
     * own bottom where there is none (another kind's, or the map's edge). So no face lies hidden against another
     * block: at the line where two tops meet, such a face is as near as they are, and the depth test lets it through
     * now and then, a dark line across the ground as the camera moves. Corners are shared exactly, so the tops leave no gap.
     * @param \Closure(string, int, int): ?array{0: float, 1: float, 2: array} $block
     * @return list<array{0: list<array{0: float, 1: float, 2: float}>, 1: array{0: float, 1: float, 2: float}, 2: array, 3?: list<array{0: float, 1: float}>}> each [its four corners, counterclockwise seen from outside; its normal; its color], and a top where it takes the grain from (turned())
     */
    public static function faces(Map $map, \Closure $block): array
    {
        $at = fn(int $x, int $y): ?array => $x < 0 || $y < 0 || $x >= $map->width || $y >= $map->height ? null : $block(Tile::name($map->tile($x, $y)), $x, $y);
        $out = [];
        for ($y = 0; $y < $map->height; $y++) {
            for ($x = 0; $x < $map->width; $x++) {
                if (($b = $at($x, $y)) === null) {
                    continue;
                }
                [$top, $bottom, $color] = $b;
                [$x0, $x1, $z0, $z1] = [$x - 0.5, $x + 0.5, $y - 0.5, $y + 0.5];
                $out[] = [[[$x0, $top, $z1], [$x1, $top, $z1], [$x1, $top, $z0], [$x0, $top, $z0]], [0.0, 1.0, 0.0], $color, self::turned($x, $y)];
                // each side, by the block beside it: south, north, east, west
                foreach ([[0, 1], [0, -1], [1, 0], [-1, 0]] as [$dx, $dy]) {
                    $from = max($bottom, $at($x + $dx, $y + $dy)[0] ?? $bottom);
                    if ($from >= $top) {
                        continue;
                    }
                    $corners = match (true) {
                        $dy === 1 => [[$x0, $from, $z1], [$x1, $from, $z1], [$x1, $top, $z1], [$x0, $top, $z1]],
                        $dy === -1 => [[$x1, $from, $z0], [$x0, $from, $z0], [$x0, $top, $z0], [$x1, $top, $z0]],
                        $dx === 1 => [[$x1, $from, $z1], [$x1, $from, $z0], [$x1, $top, $z0], [$x1, $top, $z1]],
                        default => [[$x0, $from, $z0], [$x0, $from, $z1], [$x0, $top, $z1], [$x0, $top, $z0]],
                    };
                    $out[] = [$corners, [(float)$dx, 0.0, (float)$dy], $color];
                }
            }
        }
        return $out;
    }

    /** Adds one entity that draws faces() as a mesh; nothing when there are none. */
    /**
     * Where a tile's top takes the grain from, at its four corners: the grain turned and turned over one of eight ways,
     * by the tile's place, the same each time.
     * @return list<array{0: float, 1: float}>
     */
    public static function turned(int $x, int $y): array
    {
        $way = crc32("turn $x,$y") % 8;
        $uv = [[0.0, 1.0], [1.0, 1.0], [1.0, 0.0], [0.0, 0.0]];
        if ($way >= 4) {
            $uv = array_reverse($uv);
        }
        return [...array_slice($uv, $way % 4), ...array_slice($uv, 0, $way % 4)];
    }

    /**
     * The grain's pixels, RGBA: a tile's side of them (Sprites::PER_TILE), each a gray of GRAIN by its place,
     * the same each time. The ground's and the walls' colors are drawn through it.
     */
    public static function grain(): string
    {
        $grays = [];
        foreach (self::GRAIN as [$gray, $count]) {
            array_push($grays, ...array_fill(0, $count, (int)round($gray * 255)));
        }
        $out = '';
        for ($y = 0; $y < Sprites::PER_TILE; $y++) {
            for ($x = 0; $x < Sprites::PER_TILE; $x++) {
                $g = $grays[crc32("grain $x,$y") % count($grays)];
                $out .= pack('C4', $g, $g, $g, 255);
            }
        }
        return $out;
    }

    /**
     * The shadow under an actor's pixels, RGBA (SHADOW_PIXELS): an oval of every other pixel, as a dither, so that the
     * ground shows through without the scene blending.
     */
    public static function shadow(): string
    {
        [$w, $h] = self::SHADOW_PIXELS;
        $color = array_map(fn(float $v) => (int)round($v * 255), array_slice(Sprites::color(self::SHADOW_COLOR), 0, 3));
        $out = '';
        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                $in = (($x + 0.5 - $w / 2) / ($w / 2)) ** 2 + (($y + 0.5 - $h / 2) / ($h / 2)) ** 2 <= 1.0;
                $out .= $in && ($x + $y) % 2 === 0 ? pack('C4', ...[...$color, 255]) : "\0\0\0\0";
            }
        }
        return $out;
    }

    private function faced(array $faces, bool $shadow): void
    {
        if ($faces === []) {
            return;
        }
        self::$grain ??= Image::fromPixels(Sprites::PER_TILE, Sprites::PER_TILE, self::grain())->pixelated();
        $e = new Entity(Voxels::mesh($faces), new Material(shininess: 8.0, texture: self::$grain));
        $e->castShadow = $shadow;
        $e->pickable = false;
        $this->scene->add($e);
    }

    /**
     * Adds one entity that draws a model of art/ at each of $transforms, in its own colors; nothing when there are none.
     * @param list<Mat4> $transforms
     */
    private function instanced(string $model, array $transforms, bool $shadow = true): void
    {
        if ($transforms === []) {
            return;
        }
        $e = new Entity(Voxels::solid($model), new Material(shininess: 8.0));
        $e->setInstances($transforms, array_fill(0, count($transforms), self::AS_WRITTEN));
        $e->castShadow = $shadow;
        $e->pickable = false;
        $this->scene->add($e);
    }

    /**
     * An actor's figure: its sprite, upright on a quad the size it is written (stretch() taller), its bottom on the ground
     * at its middle, facing the camera (+z), drawn as its pixels are, not lit; and the shadow under it. sync() gives the frame.
     */
    private static function figure(Actor $a): Entity
    {
        [$w, $h] = Sprites::size('person');
        [$hw, $h] = [$w / 2, $h * self::stretch()];
        $quad = Voxels::mesh([[[[-$hw, 0.0, 0.0], [$hw, 0.0, 0.0], [$hw, $h, 0.0], [-$hw, $h, 0.0]], [0.0, 0.0, 1.0], self::AS_WRITTEN]]);
        $e = new Entity($quad, Material::unlit(texture: Sprites::image('person', $a->dir->value, 0, ['r' => self::LOOKS[$a->look]])), name: $a->id);
        $e->castShadow = false;
        $e->pickable = false;

        [$sw, $sd] = [self::SHADOW[0] / 2, self::SHADOW[1] / 2];
        self::$shadow ??= Image::fromPixels(...[...self::SHADOW_PIXELS, self::shadow()])->pixelated();
        $flat = Voxels::mesh([[[[-$sw, 0.01, $sd], [$sw, 0.01, $sd], [$sw, 0.01, -$sd], [-$sw, 0.01, -$sd]], [0.0, 1.0, 0.0], self::AS_WRITTEN]]);
        $under = new Entity($flat, Material::unlit(texture: self::$shadow));
        $under->castShadow = false;
        $under->pickable = false;
        $e->addChild($under);
        return $e;
    }
}
