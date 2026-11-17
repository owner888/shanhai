<?php
declare(strict_types=1);

namespace Shanhai\Render;

use MyPHP\Plugins\Scene\{Camera3D, Entity, Material, Mesh, Scene};
use MyPHP\UI\Image;
use MyPHP\Plugins\Scene\Math\{Mat4, Vec3};
use Shanhai\World\{Actor, Dir, Map, Tile, World};

/**
 * The 3D scene of the map the player is on (MyPHP's Plugins\Scene), drawn HD-2D (docs/art.md): a tile is one unit,
 * a map's x the scene's x and its y the scene's z. Built once for a map:
 * - the ground and the walls: blocks, of which only the faces that can be seen (faces()), one mesh for each texture
 *   of art/sprites/ground (GROUND, by the kind of tile), each tile a little lighter or darker (Voxels::shade) and its
 *   top's texture turned over one of four ways (turned()), so that the tiles do not repeat;
 * - the things standing on it (art/sprites/things: trees, tall grass, rocks, signs): sprites stood upright, one
 *   instanced draw a kind, with a dithered shadow under them;
 * - the actors: art/sprites/people's person, robed in their look's color, a shadow under each.
 * A sprite stands a little taller than written (stretch()), so that the camera's slant shows it as written, and is
 * drawn as its pixels are, not lit. sync() then moves the actors, shows each its frame for the way it faces and its
 * step, and moves the camera, which looks down at the player from a fixed angle and never turns.
 */
final class Stage
{
    /** Where the camera stands from the player: up and back (south), and its vertical field of view in degrees. */
    public const CAMERA_HEIGHT = 10.0, CAMERA_BACK = 15.0, CAMERA_FOV = 32.0;

    /** The texture of the ground under each kind of tile (art/sprites/ground/). Under a wall, where little shows, scree. */
    public const GROUND = [
        'grass' => 'grass', 'tallGrass' => 'grass', 'tree' => 'grass', 'rock' => 'grass',
        'scree' => 'scree', 'sand' => 'sand', 'path' => 'path', 'floor' => 'floor', 'door' => 'door',
        'wall' => 'scree', 'water' => 'water',
    ];
    /**
     * The things a map stands on its tiles, by kind (the signs are the map's own list): the sprite, how far before
     * the tile's middle it stands (tall grass in front of who walks in it, so that it hides their legs; the others a
     * little behind, so that who stands beside one is before what of it reaches over their tile); how far it may
     * stand off the middle sideways, by its place, so that a wood is not a grid (and two trees side by side, whose
     * crowns meet, a little nearer and farther every other tile, so that one is always before the other); and the
     * size of the shadow under it in tiles, if it has one.
     */
    public const THINGS = [
        'tree' => ['things/tree', -0.05, 0.15, [1.4, 0.6]],
        'tallGrass' => ['things/tallGrass', 0.3, 0.0, null],
        'rock' => ['things/rock', -0.05, 0.0, [0.9, 0.35]],
        'sign' => ['things/sign', -0.05, 0.0, [0.5, 0.25]],
    ];
    /**
     * How many tiles of land beyond an outdoor map's edges are drawn, each way, so that the view never shows the end of
     * the world (HD-2D's views are full to their edges): enough for the widest window's sides, and, the camera looking
     * north and down, more before the player than behind. What they are is surround()'s.
     */
    public const BEYOND = ['up' => 20, 'down' => 10, 'left' => 16, 'right' => 16];
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
    public const GROUND_TOP = 0.0, WATER_TOP = -0.12, WALL_TOP = 2.2, GROUND_BOTTOM = -0.2, WATER_BOTTOM = -0.32;
    /** What a model's own colors are drawn through: white, so they are as written. */
    private const AS_WRITTEN = [1.0, 1.0, 1.0, 1.0];
    /** The dithered shadow under an actor: its size in tiles; and every shadow's pixels and color. */
    private const SHADOW = [0.7, 0.35], SHADOW_PIXELS = [16, 8], SHADOW_COLOR = '#1c2a1c';

    public readonly Scene $scene;
    /** @var array<string, Entity> by actor id */
    private array $actors = [];
    /** @var array<string, string> by actor id: its look's robe, '#rrggbb' */
    private array $robes = [];
    /** @var array<string, list<array{0: float, 1: float, 2: Mat4}>> by kind of thing: where each stands, x and z, and its transform */
    private array $standing = [];
    /** @var array<string, array{0: Entity, 1: Entity}> by kind of thing: the entity drawing them, and the one drawing those faded (fades()) */
    private array $stood = [];
    /** The player's tile the things were last faded for. */
    private string $fadedFor = '';
    private static ?Image $shadow = null;

    public function __construct(World $w)
    {
        $this->scene = new Scene(new Camera3D(fovYDegrees: self::CAMERA_FOV));
        $this->scene->clearColor = [0.05, 0.07, 0.10, 1.0];
        $this->scene->shadowsEnabled = true;
        $this->scene->shadowBounds = 20.0;
        if (!$w->map->indoor) {
            $this->scene->addSkyDome(); // MyPHP's: beyond the map's edge is sky and far ground, not the dark of a room
        }

        // the land drawn: the map, and outdoors the land beyond its edges; its tile x, y is the scene's x - left, z - up
        $map = $w->map;
        $land = $map->indoor ? $map : self::surround($map);
        [$left, $up] = $map->indoor ? [0, 0] : [self::BEYOND['left'], self::BEYOND['up']];
        foreach (self::byTexture($land, self::faces($land, self::ground(...))) as $texture => $faces) {
            $this->faced($faces, "ground/$texture", shadow: false, at: new Vec3(-$left, 0.0, -$up));
        }
        $this->faced(self::faces($land, self::wall(...)), 'ground/wall', shadow: true, at: new Vec3(-$left, 0.0, -$up));
        $places = ['sign' => $map->signs];
        for ($y = 0; $y < $land->height; $y++) {
            for ($x = 0; $x < $land->width; $x++) {
                $kind = Tile::name($land->tile($x, $y));
                if (isset(self::THINGS[$kind])) {
                    $places[$kind][] = [$x - $left, $y - $up];
                }
            }
        }
        foreach ($places as $kind => $at) {
            $this->things($kind, $at);
        }

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
            $e->material->texture = Sprites::image('people/person', $a->dir->value, self::step($a, $alpha), ['r' => $this->robes[$a->id]]);
        }
        [$px, $pz] = $w->player->place($alpha);
        $this->fade((int)round($px), (int)round($pz));
        $this->scene->camera->target = new Vec3($px, 0.0, $pz);
        $this->scene->camera->position = new Vec3($px, self::CAMERA_HEIGHT, $pz + self::CAMERA_BACK);
    }

    /**
     * Whether a thing of a kind, $dx tiles beside the player and $dy before them (south, toward the camera), hides them:
     * stands before them, wide enough to reach across them, and tall enough on the screen to cover more than half of
     * them. Such a thing is drawn faded (Sprites::fade), as HD-2D fades what stands between the camera and the player.
     */
    public static function fades(string $kind, float $dx, float $dy): bool
    {
        [$w, $h] = Sprites::size(self::THINGS[$kind][0]);
        [$pw, $ph] = Sprites::size('people/person');
        $slant = atan2(self::CAMERA_HEIGHT, self::CAMERA_BACK);
        return $dy > 0 && abs($dx) < ($w + $pw) / 2 && $h - $dy * sin($slant) > $ph / 2;
    }

    /**
     * The map and the land around it, BEYOND: a larger map whose middle is $map. Beyond an edge the player can walk off
     * (an exit), what can be walked on at the edge goes on (a path leads on, a meadow spreads); water goes on; all else
     * is wood, the trees closing round the map as HD-2D's views are closed round, never ending in nothing.
     */
    public static function surround(Map $map): Map
    {
        $b = self::BEYOND;
        $tree = array_search('tree', array_map(fn(array $k) => $k[0], Tile::KINDS), true);
        $rows = [];
        for ($y = -$b['up']; $y < $map->height + $b['down']; $y++) {
            $row = '';
            for ($x = -$b['left']; $x < $map->width + $b['right']; $x++) {
                if ($map->inside($x, $y)) {
                    $row .= $map->tile($x, $y);
                    continue;
                }
                $edge = $map->tile(max(0, min($map->width - 1, $x)), max(0, min($map->height - 1, $y)));
                $sides = array_filter(['left' => $x < 0, 'right' => $x >= $map->width, 'up' => $y < 0, 'down' => $y >= $map->height]);
                $exit = count($sides) === 1 && isset($map->exits[array_key_first($sides)]);
                $row .= Tile::name($edge) === 'water' || ($exit && Tile::walkable($edge)) ? $edge : $tree;
            }
            $rows[] = $row;
        }
        return Map::fromArray(['id' => $map->id, 'name' => $map->name, 'rows' => $rows]);
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

    /** Works out ahead the sprites every map draws: its grounds, its things, and a person in each look's robe. Once, as the game starts. */
    public static function prepare(): void
    {
        foreach ([...array_unique(self::GROUND), 'wall'] as $ground) {
            Sprites::prepare("ground/$ground");
        }
        foreach (self::THINGS as [$sprite]) {
            Sprites::prepare($sprite);
        }
        foreach (self::LOOKS as $robe) {
            Sprites::prepare('people/person', ['r' => $robe]);
        }
    }

    /** The block of ground of a tile, a kind at a place: its top, its bottom, and the place's shade its texture is drawn through. Water lies lower. */
    public static function ground(string $kind, int $x, int $y): array
    {
        $color = Voxels::shade(self::AS_WRITTEN, $x, $y);
        return $kind === 'water' ? [self::WATER_TOP, self::WATER_BOTTOM, $color] : [self::GROUND_TOP, self::GROUND_BOTTOM, $color];
    }

    /** A wall tile's block, standing on the ground; none for another tile. */
    public static function wall(string $kind, int $x, int $y): ?array
    {
        return $kind === 'wall' ? [self::WALL_TOP, self::GROUND_TOP, Voxels::shade(self::AS_WRITTEN, $x, $y)] : null;
    }

    /**
     * The faces of a map's blocks that can be seen, a block a tile: $block gives a kind of tile's, at a place, [top, bottom, color],
     * or null for none. A block's top is a face; a side is one only above the top of the block beside it, down to its
     * own bottom where there is none (another kind's, or the map's edge). So no face lies hidden against another
     * block: at the line where two tops meet, such a face is as near as they are, and the depth test lets it through
     * now and then, a dark line across the ground as the camera moves. Corners are shared exactly, so the tops leave no gap.
     * A top takes its texture turned over one of four ways (turned()); a side takes it from the top of the texture down,
     * a tile of it to a tile of height, so that it tiles up a wall as along the ground.
     * @param \Closure(string, int, int): ?array{0: float, 1: float, 2: array} $block
     * @return list<array{0: list<array{0: float, 1: float, 2: float}>, 1: array{0: float, 1: float, 2: float}, 2: array, 3: list<array{0: float, 1: float}>}> each [its four corners, counterclockwise seen from outside; its normal; its color; where it takes its texture from at them]
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
                    $tall = $top - $from;
                    $out[] = [$corners, [(float)$dx, 0.0, (float)$dy], $color, [[0.0, $tall], [1.0, $tall], [1.0, 0.0], [0.0, 0.0]]];
                }
            }
        }
        return $out;
    }

    /**
     * faces() of the ground, by the texture each is drawn with: the one of the kind of tile it belongs to (GROUND),
     * a top's the tile it covers, a side's the tile it stands at the edge of.
     * @return array<string, list<array>>
     */
    public static function byTexture(Map $map, array $faces): array
    {
        $out = [];
        foreach ($faces as $f) {
            [$corners, $n] = $f;
            $x = (int)round(array_sum(array_column($corners, 0)) / 4 - $n[0] / 2);
            $y = (int)round(array_sum(array_column($corners, 2)) / 4 - $n[2] / 2);
            $out[self::GROUND[Tile::name($map->tile($x, $y))]][] = $f;
        }
        return $out;
    }

    /**
     * Where a tile's top takes its texture from, at its four corners: the texture as it is, or turned over left to
     * right, top to bottom or both, by the tile's place, the same each time. Never turned round, so that what runs one
     * way (a floor's planks, a wall's courses, the crests of water) runs the same way on every tile.
     * @return list<array{0: float, 1: float}>
     */
    public static function turned(int $x, int $y): array
    {
        $way = crc32("turn $x,$y") % 4;
        [$l, $r] = $way & 1 ? [1.0, 0.0] : [0.0, 1.0];
        [$t, $b] = $way & 2 ? [1.0, 0.0] : [0.0, 1.0];
        return [[$l, $b], [$r, $b], [$r, $t], [$l, $t]];
    }

    /**
     * The shadow under a sprite's pixels, RGBA (SHADOW_PIXELS): an oval of every other pixel, as a dither, so that the
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

    /** Adds one entity that draws faces() as a mesh through a texture of art/sprites; nothing when there are none. */
    private function faced(array $faces, string $texture, bool $shadow, Vec3 $at): void
    {
        if ($faces === []) {
            return;
        }
        $e = new Entity(Voxels::mesh($faces), new Material(shininess: 8.0, texture: Sprites::image($texture, 'down', 0)), position: $at);
        $e->castShadow = $shadow;
        $e->pickable = false;
        $this->scene->add($e);
    }

    /**
     * Adds a kind of thing (THINGS) at each of its places: its sprite upright, all in one instanced draw, and those
     * that hide the player in another, faded (fade()); and the shadow under each in a third.
     * @param list<array{0: int, 1: int}> $at
     */
    private function things(string $kind, array $at): void
    {
        [$sprite, $before, $off, $shadow] = self::THINGS[$kind];
        foreach ($at as [$x, $y]) {
            $sx = $x + $off * (2.0 * Sprites::noise($x, $y, 'stand') - 1.0);
            $sz = $y + $before - ($off > 0.0 && $x % 2 !== 0 ? 0.04 : 0.0);
            $this->standing[$kind][] = [$sx, $sz, Mat4::translation($sx, self::GROUND_TOP, $sz)];
        }
        $this->stood[$kind] = [];
        foreach ([false, true] as $faded) {
            $e = new Entity(self::upright($sprite), Material::unlit(texture: Sprites::image($sprite, 'down', 0, faded: $faded)));
            $e->castShadow = false;
            $e->pickable = false;
            $this->stood[$kind][] = $this->scene->add($e);
        }
        $this->stood[$kind][0]->setInstances(array_column($this->standing[$kind], 2));
        if ($shadow !== null) {
            $under = new Entity(self::flat(1.0, 1.0), Material::unlit(texture: self::shadowImage()));
            $under->setInstances(array_map(fn(array $p) => Mat4::translation($p[0], self::GROUND_TOP, $p[1] - $before)->multiply(Mat4::scaling($shadow[0], 1.0, $shadow[1])), $this->standing[$kind]));
            $under->castShadow = false;
            $under->pickable = false;
            $this->scene->add($under);
        }
    }

    /** Draws faded the things that hide the player on their tile ($px, $pz), the rest as they are (fades()); again only when the player is on another tile. */
    private function fade(int $px, int $pz): void
    {
        if ($this->fadedFor === "$px,$pz") {
            return;
        }
        $this->fadedFor = "$px,$pz";
        foreach ($this->standing as $kind => $standing) {
            $sorted = [[], []];
            foreach ($standing as [$x, $z, $m]) {
                $sorted[(int)self::fades($kind, $x - $px, $z - $pz)][] = $m;
            }
            foreach ($sorted as $i => $transforms) {
                $e = $this->stood[$kind][$i];
                $e->setInstances($transforms);
                $e->scale = $transforms === [] ? new Vec3(0.0, 0.0, 0.0) : new Vec3(1.0, 1.0, 1.0);
            }
        }
    }

    /**
     * An actor's figure: its sprite upright, facing the camera (+z), and the shadow under it. sync() gives the frame.
     */
    private static function figure(Actor $a): Entity
    {
        $e = new Entity(self::upright('people/person'), Material::unlit(texture: Sprites::image('people/person', $a->dir->value, 0, ['r' => self::LOOKS[$a->look]])), name: $a->id);
        $e->castShadow = false;
        $e->pickable = false;
        $under = new Entity(self::flat(...self::SHADOW), Material::unlit(texture: self::shadowImage()));
        $under->castShadow = false;
        $under->pickable = false;
        $e->addChild($under);
        return $e;
    }

    /** A quad the size a sprite is written (stretch() taller), upright, its bottom's middle at the origin, facing +z. */
    private static function upright(string $sprite): Mesh
    {
        [$w, $h] = Sprites::size($sprite);
        [$hw, $h] = [$w / 2, $h * self::stretch()];
        return Voxels::mesh([[[[-$hw, 0.0, 0.0], [$hw, 0.0, 0.0], [$hw, $h, 0.0], [-$hw, $h, 0.0]], [0.0, 0.0, 1.0], self::AS_WRITTEN]]);
    }

    /** A quad lying a hair over the ground, $w by $d tiles, its middle at the origin. */
    private static function flat(float $w, float $d): Mesh
    {
        [$hw, $hd] = [$w / 2, $d / 2];
        return Voxels::mesh([[[[-$hw, 0.01, $hd], [$hw, 0.01, $hd], [$hw, 0.01, -$hd], [-$hw, 0.01, -$hd]], [0.0, 1.0, 0.0], self::AS_WRITTEN]]);
    }

    private static function shadowImage(): Image
    {
        return self::$shadow ??= Image::fromPixels(...[...self::SHADOW_PIXELS, self::shadow()])->pixelated();
    }
}
