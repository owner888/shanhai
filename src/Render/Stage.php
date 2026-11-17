<?php
declare(strict_types=1);

namespace Shanhai\Render;

use MyPHP\Plugins\Scene\{Camera3D, Entity, Material, Scene};
use MyPHP\Plugins\Scene\Math\{Mat4, Vec3};
use Shanhai\World\{Actor, Dir, Map, Tile, World};

/**
 * The 3D scene of the map the player is on (MyPHP's Plugins\Scene): a tile is one unit, a map's x the scene's x
 * and its y the scene's z. Built once for a map: the ground and the walls each one mesh of the faces that can be
 * seen (faces()), each tile a little lighter or darker than its kind's color (Voxels::shade); each kind of thing standing
 * on it (a model of art/things) one instanced draw; the actors are art/people's person, robed in their look's color.
 * sync() then moves the actors and the camera, which looks down at the player from a fixed angle and never turns.
 */
final class Stage
{
    /** Where the camera stands from the player: up and back (south), and its vertical field of view in degrees. */
    public const CAMERA_HEIGHT = 8.0, CAMERA_BACK = 6.5, CAMERA_FOV = 40.0;

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
        'player' => [0.85, 0.25, 0.25, 1.0],
        'elder' => [0.55, 0.55, 0.60, 1.0],
        'guard' => [0.30, 0.40, 0.70, 1.0],
        'shaman' => [0.55, 0.30, 0.65, 1.0],
        'god' => [0.95, 0.80, 0.30, 1.0],
        'foe' => [0.60, 0.35, 0.20, 1.0],
    ];
    /** How high the ground's top is, the water's, a wall's; and how far down the ground and the water go, which shows at a map's edge. */
    public const GROUND_TOP = 0.0, WATER_TOP = -0.12, WALL_TOP = 1.3, GROUND_BOTTOM = -0.2, WATER_BOTTOM = -0.32;
    private const WALL = [0.88, 0.85, 0.78, 1.0];
    /** What a model's own colors are drawn through: white, so they are as written. */
    private const AS_WRITTEN = [1.0, 1.0, 1.0, 1.0];
    /** How high a walking actor's step lifts it. */
    private const BOB = 0.05;
    /** The turn about the vertical axis that faces an actor, built facing south, each way. */
    private const TURN = ['down' => 0.0, 'right' => M_PI_2, 'up' => M_PI, 'left' => -M_PI_2];

    public readonly Scene $scene;
    /** @var array<string, Entity> by actor id */
    private array $actors = [];

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
            $this->actors[$a->id] = $this->scene->add(self::figure($a));
        }
        $this->sync($w, 0.0);
    }

    /** Puts the actors where the world has them, $alpha (0 to 1) into the next update, and the camera over the player. */
    public function sync(World $w, float $alpha): void
    {
        foreach ([$w->player, ...array_values($w->npcs)] as $a) {
            [$x, $z] = $a->place($alpha);
            $lift = $a->moving ? self::BOB * abs(sin(M_PI * 2.0 * ($a->progress + $alpha) / World::WALK_STEPS)) : 0.0;
            $e = $this->actors[$a->id];
            $e->position = new Vec3($x, self::GROUND_TOP + $lift, $z);
            $e->rotation->y = self::TURN[$a->dir->value];
        }
        [$px, $pz] = $w->player->place($alpha);
        $this->scene->camera->target = new Vec3($px, 0.0, $pz);
        $this->scene->camera->position = new Vec3($px, self::CAMERA_HEIGHT, $pz + self::CAMERA_BACK);
    }

    /** Works out ahead the models every map draws (Voxels::prepare): its things, and a person in each look's robe. Once, as the game starts. */
    public static function prepare(): void
    {
        foreach (['things/tree', 'things/tallGrass', 'things/rock', 'things/sign'] as $thing) {
            Voxels::prepare($thing);
        }
        foreach (self::LOOKS as $robe) {
            Voxels::prepare('people/person', ['r' => $robe]);
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
     * @return list<array{0: list<array{0: float, 1: float, 2: float}>, 1: array{0: float, 1: float, 2: float}, 2: array}> each [its four corners, counterclockwise seen from outside; its normal; its color]
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
                $out[] = [[[$x0, $top, $z1], [$x1, $top, $z1], [$x1, $top, $z0], [$x0, $top, $z0]], [0.0, 1.0, 0.0], $color];
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
    private function faced(array $faces, bool $shadow): void
    {
        if ($faces === []) {
            return;
        }
        $e = new Entity(Voxels::mesh($faces), new Material(shininess: 8.0));
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

    /** An actor's figure, facing south: art/people's person, robed in the color of its look. */
    private static function figure(Actor $a): Entity
    {
        return Voxels::entity('people/person', ['r' => self::LOOKS[$a->look]], $a->id)[0];
    }
}
