<?php
declare(strict_types=1);

namespace Shanhai\Render;

use MyPHP\Plugins\Scene\{Camera3D, Entity, Material, Mesh, Scene};
use MyPHP\Plugins\Scene\Math\{Mat4, Vec3};
use Shanhai\World\{Actor, Dir, Map, Tile, World};

/**
 * The 3D scene of the map the player is on (MyPHP's Plugins\Scene): a tile is one unit, a map's x the scene's x
 * and its y the scene's z. Built once for a map: the ground and the walls each one mesh of the faces that can be
 * seen (faces()), each kind of thing standing on it one instanced draw; sync() then moves the actors and the camera,
 * which looks down at the player from a fixed angle and never turns.
 */
final class Stage
{
    /** Where the camera stands from the player: up and back (south), and its vertical field of view in degrees. */
    public const CAMERA_HEIGHT = 8.0, CAMERA_BACK = 6.5, CAMERA_FOV = 40.0;

    /** The ground's color under each kind of tile. */
    public const GROUND = [
        'grass' => [0.36, 0.62, 0.30, 1.0],
        'tallGrass' => [0.24, 0.50, 0.24, 1.0],
        'scree' => [0.50, 0.49, 0.46, 1.0],
        'sand' => [0.70, 0.66, 0.55, 1.0],
        'path' => [0.76, 0.66, 0.46, 1.0],
        'floor' => [0.62, 0.47, 0.32, 1.0],
        'door' => [0.35, 0.24, 0.16, 1.0],
        'tree' => [0.36, 0.62, 0.30, 1.0],
        'rock' => [0.36, 0.62, 0.30, 1.0],
        'wall' => [0.45, 0.40, 0.36, 1.0],
        'water' => [0.20, 0.45, 0.75, 1.0],
    ];
    /** An actor's body color, by its look. */
    public const LOOKS = [
        'player' => [0.85, 0.25, 0.25, 1.0],
        'elder' => [0.55, 0.55, 0.60, 1.0],
        'guard' => [0.30, 0.40, 0.70, 1.0],
        'shaman' => [0.55, 0.30, 0.65, 1.0],
    ];
    /** How high the ground's top is, the water's, a wall's; and how far down the ground and the water go, which shows at a map's edge. */
    public const GROUND_TOP = 0.0, WATER_TOP = -0.12, WALL_TOP = 1.3, GROUND_BOTTOM = -0.2, WATER_BOTTOM = -0.32;
    private const SKIN = [0.96, 0.82, 0.68, 1.0];
    private const TRUNK = [0.45, 0.30, 0.18, 1.0], CROWN = [0.16, 0.42, 0.20, 1.0], BLADE = [0.30, 0.62, 0.26, 1.0];
    private const STONE = [0.55, 0.55, 0.58, 1.0], WALL = [0.80, 0.72, 0.58, 1.0], BOARD = [0.60, 0.44, 0.26, 1.0];
    /** Where the three tufts of a tall grass tile stand, from its center. */
    private const TUFTS = [[-0.25, -0.20], [0.20, 0.05], [-0.05, 0.28]];
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

        $trunks = $crowns = $tufts = $rocks = [];
        $map = $w->map;
        $this->faced(self::faces($map, self::ground(...)), shadow: false);
        $this->faced(self::faces($map, self::wall(...)), shadow: true);
        for ($y = 0; $y < $map->height; $y++) {
            for ($x = 0; $x < $map->width; $x++) {
                $kind = Tile::name($map->tile($x, $y));
                switch ($kind) {
                    case 'tree':
                        $trunks[] = Mat4::translation($x, 0.3, $y);
                        $crowns[] = Mat4::translation($x, 1.2, $y);
                        break;
                    case 'tallGrass':
                        foreach (self::TUFTS as [$dx, $dz]) {
                            $tufts[] = Mat4::translation($x + $dx, 0.225, $y + $dz);
                        }
                        break;
                    case 'rock':
                        $rocks[] = Mat4::translation($x, 0.25, $y)->multiply(Mat4::scaling(1.0, 0.8, 1.0));
                        break;
                }
            }
        }
        $this->instanced(Mesh::cylinder(0.10, 0.14, 0.6, 8), $trunks, self::TRUNK);
        $this->instanced(Mesh::cone(0.46, 1.2, 10), $crowns, self::CROWN);
        $this->instanced(Mesh::cone(0.12, 0.45, 6), $tufts, self::BLADE, shadow: false);
        $this->instanced(Mesh::sphere(0.42, 10, 8), $rocks, self::STONE);

        $posts = $boards = [];
        foreach ($map->signs as [$x, $y]) {
            $posts[] = Mat4::translation($x, 0.3, $y)->multiply(Mat4::scaling(0.1, 0.6, 0.1));
            $boards[] = Mat4::translation($x, 0.7, $y)->multiply(Mat4::scaling(0.6, 0.35, 0.08));
        }
        $this->instanced(Mesh::cube(1.0), $posts, self::TRUNK);
        $this->instanced(Mesh::cube(1.0), $boards, self::BOARD);

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
            $e->position = new Vec3($x, 0.38 + $lift, $z);
            $e->rotation->y = self::TURN[$a->dir->value];
        }
        [$px, $pz] = $w->player->place($alpha);
        $this->scene->camera->target = new Vec3($px, 0.0, $pz);
        $this->scene->camera->position = new Vec3($px, self::CAMERA_HEIGHT, $pz + self::CAMERA_BACK);
    }

    /** A tile's block of ground: its top, its bottom, its color. Water lies lower. */
    public static function ground(string $kind): array
    {
        return $kind === 'water' ? [self::WATER_TOP, self::WATER_BOTTOM, self::GROUND['water']] : [self::GROUND_TOP, self::GROUND_BOTTOM, self::GROUND[$kind]];
    }

    /** A wall tile's block, standing on the ground; none for another tile. */
    public static function wall(string $kind): ?array
    {
        return $kind === 'wall' ? [self::WALL_TOP, self::GROUND_TOP, self::WALL] : null;
    }

    /**
     * The faces of a map's blocks that can be seen, a block a tile: $block gives a kind of tile's [top, bottom, color],
     * or null for none. A block's top is a face; a side is one only above the top of the block beside it, down to its
     * own bottom where there is none (another kind's, or the map's edge). So no face lies hidden against another
     * block: at the line where two tops meet, such a face is as near as they are, and the depth test lets it through
     * now and then, a dark line across the ground as the camera moves. Corners are shared exactly, so the tops leave no gap.
     * @param \Closure(string): ?array{0: float, 1: float, 2: array} $block
     * @return list<array{0: list<array{0: float, 1: float, 2: float}>, 1: array{0: float, 1: float, 2: float}, 2: array}> each [its four corners, counterclockwise seen from outside; its normal; its color]
     */
    public static function faces(Map $map, \Closure $block): array
    {
        $at = fn(int $x, int $y): ?array => $x < 0 || $y < 0 || $x >= $map->width || $y >= $map->height ? null : $block(Tile::name($map->tile($x, $y)));
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
        $vertices = $indices = [];
        foreach ($faces as $i => [$corners, $normal, $color]) {
            foreach ($corners as $k => $corner) {
                // MyPHP's vertex: position, normal, uv, color
                array_push($vertices, ...$corner, ...$normal, ...[[0.0, 1.0], [1.0, 1.0], [1.0, 0.0], [0.0, 0.0]][$k], ...$color);
            }
            array_push($indices, 4 * $i, 4 * $i + 1, 4 * $i + 2, 4 * $i, 4 * $i + 2, 4 * $i + 3);
        }
        $e = new Entity(new Mesh(pack('f*', ...$vertices), pack('V*', ...$indices), 4 * count($faces), 6 * count($faces)), new Material(shininess: 8.0));
        $e->castShadow = $shadow;
        $e->pickable = false;
        $this->scene->add($e);
    }

    /**
     * Adds one entity that draws $mesh at each of $transforms, all of one color or each of its own; nothing when there are none.
     * @param list<Mat4> $transforms
     * @param array{0: float, 1: float, 2: float, 3: float}|list<array{0: float, 1: float, 2: float, 3: float}> $colors
     */
    private function instanced(Mesh $mesh, array $transforms, array $colors, bool $shadow = true): void
    {
        if ($transforms === []) {
            return;
        }
        $e = new Entity($mesh, new Material(shininess: 8.0));
        $e->setInstances($transforms, is_array($colors[0]) ? $colors : array_fill(0, count($transforms), $colors));
        $e->castShadow = $shadow;
        $e->pickable = false;
        $this->scene->add($e);
    }

    /** An actor's figure, facing south: a body, a head, and a nose that shows which way it faces. */
    private static function figure(Actor $a): Entity
    {
        $color = self::LOOKS[$a->look];
        $body = new Entity(Mesh::capsule(0.2, 0.3, 12, 8, $color), new Material($color, shininess: 16.0), name: $a->id);
        $body->addChild(new Entity(Mesh::sphere(0.17, 12, 8, self::SKIN), new Material(self::SKIN, shininess: 16.0), position: new Vec3(0.0, 0.48, 0.0)));
        $body->addChild(new Entity(Mesh::cube(0.08, self::SKIN), new Material(self::SKIN), position: new Vec3(0.0, 0.48, 0.17)));
        return $body;
    }
}
