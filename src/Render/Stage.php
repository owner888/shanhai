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
 *   of art/sprites/ground (GROUND, by the kind of tile), each tile a little lighter or darker (shade()) and its
 *   top's texture turned over one of four ways (turned()), so that the tiles do not repeat. Outdoors the walls are
 *   houses (blocks()): plastered and timbered, a door where the map has one, a thatched roof over each (roof());
 *   indoors they are a room's, and the ones between the camera and the floor are cut low, so that the room shows;
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
    /** What stands on the open land beyond a map's edges: each kind on a tile where the place's noise is below its number (and above the one before). */
    public const WILD = ['tree' => 0.07, 'rock' => 0.09, 'tallGrass' => 0.15];
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
    /**
     * A room's wall cut low, between the camera and the floor (blocks()); a house's walls, outdoors; how much higher
     * its roof's ridge is than they, and how far its eaves reach past them. Proportioned as HD-2D's: a door a head
     * taller than who goes through it, a house about three times their height to the ridge.
     */
    public const LOW_WALL_TOP = 0.3, HOUSE_TOP = 2.4, RIDGE = 1.6, EAVES = 0.3;
    /** What a house's walls are drawn with, outdoors, by the kind of tile; a room's, indoors. */
    public const HOUSE = ['wall' => 'house', 'door' => 'houseDoor'], ROOM = ['wall' => 'wall'];
    /** What a model's own colors are drawn through: white, so they are as written. */
    private const AS_WRITTEN = [1.0, 1.0, 1.0, 1.0];
    /** How much lighter or darker a tile is than another, at most, by where it is (shade()). */
    private const GRAIN = 0.05;
    /** Where a face takes its texture from at its four corners, unless it says (a texture's top left is 0, 0). */
    private const UV = [[0.0, 1.0], [1.0, 1.0], [1.0, 0.0], [0.0, 0.0]];
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
    /** @var list<array{0: array{0: int, 1: int, 2: int, 3: int}, 1: list<array{0: Entity, 1: string}>}> the houses, outdoors: each its box in the scene's places, and its entities with their textures */
    private array $houses = [];
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
        $land = $map->indoor ? $map : self::surround($map, $w->neighbours());
        [$left, $up] = $map->indoor ? [0, 0] : [self::BEYOND['left'], self::BEYOND['up']];
        $at = new Vec3(-$left, 0.0, -$up);
        foreach (self::byTexture($land, self::faces($land, self::ground(...)), self::GROUND) as $texture => $faces) {
            $this->faced($faces, "ground/$texture", shadow: false, at: $at);
        }
        if ($map->indoor) {
            foreach (self::byTexture($land, self::faces($land, self::blocks($land)), self::ROOM) as $texture => $faces) {
                $this->faced($faces, "ground/$texture", shadow: true, at: $at);
            }
        } else {
            // each house its own entities, so that the one that hides the player can be drawn faded (fade())
            $blocks = self::blocks($land);
            foreach (self::houses($land) as [$box, $tiles]) {
                $own = fn(string $kind, int $x, int $y) => isset($tiles["$x,$y"]) ? $blocks($kind, $x, $y) : null;
                $drawn = [];
                foreach ([...self::byTexture($land, self::faces($land, $own), self::HOUSE), 'thatch' => self::roof($box)] as $texture => $faces) {
                    $drawn[] = [$this->faced($faces, "ground/$texture", shadow: true, at: $at), "ground/$texture"];
                }
                [$x0, $x1, $y0, $y1] = $box;
                $this->houses[] = [[$x0 - $left, $x1 - $left, $y0 - $up, $y1 - $up], $drawn];
            }
        }
        $places = ['sign' => $map->signs];
        foreach ($map->indoor ? [] : $w->neighbours() as [$next, $nx, $ny]) {
            foreach ($next->signs as [$sx, $sy]) {
                $places['sign'][] = [$sx + $nx, $sy + $ny];
            }
        }
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
        $here = [];
        foreach ([$w->player, ...array_values($w->npcs)] as $a) {
            [$x, $z] = $a->place($alpha);
            if (!isset($this->actors[$a->id])) { // one who was not on the map when this Stage was built (a Stage is kept, Play)
                $this->robes[$a->id] = self::LOOKS[$a->look];
                $this->actors[$a->id] = $this->scene->add(self::figure($a));
            }
            $here[$a->id] = true;
            $e = $this->actors[$a->id];
            $e->scale = new Vec3(1.0, 1.0, 1.0);
            $e->position = new Vec3($x, self::GROUND_TOP, $z);
            $e->material->texture = Sprites::image('people/person', $a->dir->value, self::step($a, $alpha), ['r' => $this->robes[$a->id]]);
        }
        foreach (array_diff_key($this->actors, $here) as $gone) {
            $gone->scale = new Vec3(0.0, 0.0, 0.0); // one who is not on the map now
        }
        [$px, $pz] = $w->player->place($alpha);
        $this->fade((int)round($px), (int)round($pz));
        $this->scene->camera->target = new Vec3($px, 0.0, $pz);
        $this->scene->camera->position = new Vec3($px, self::CAMERA_HEIGHT, $pz + self::CAMERA_BACK);
    }

    /** A color made a little lighter or darker by where it is (GRAIN), the same each time: a tile's. */
    public static function shade(array $rgba, int ...$at): array
    {
        $f = 1.0 + self::GRAIN * (2.0 * (crc32(implode(',', $at)) % 1000) / 999.0 - 1.0);
        return [min(1.0, $rgba[0] * $f), min(1.0, $rgba[1] * $f), min(1.0, $rgba[2] * $f), $rgba[3]];
    }

    /**
     * One mesh of faces in faces()' form: [corners, normal, color], and where it takes its texture from at the
     * corners, unless UV. Packed as MyPHP's Mesh takes them: position, normal, uv, color a vertex; two triangles a face.
     */
    public static function mesh(array $faces): Mesh
    {
        $vertices = $indices = [];
        foreach ($faces as $i => $face) {
            [$corners, $normal, $color] = $face;
            $uv = $face[3] ?? self::UV;
            foreach ($corners as $k => $corner) {
                array_push($vertices, ...$corner, ...$normal, ...$uv[$k], ...$color);
            }
            array_push($indices, 4 * $i, 4 * $i + 1, 4 * $i + 2, 4 * $i, 4 * $i + 2, 4 * $i + 3);
        }
        return new Mesh(pack('f*', ...$vertices), pack('V*', ...$indices), 4 * count($faces), 6 * count($faces));
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
     * (an exit) lies the map it joins, as it is ($neighbours, World::neighbours), so that the land goes on across the
     * seam as the classic's routes and towns join. Where none lies, beyond an exit what can be walked on at the edge
     * goes on (a path leads on, a meadow spreads); water goes on; all else
     * is open land, as HD-2D's views are full to their edges and never end in nothing: grass, with a tree, a patch of
     * tall grass or a rock here and there (WILD), by the place, the same each time, so that it is a landscape and
     * not a wall of trees.
     */
    /** @param array<string, array{0: Map, 1: int, 2: int}> $neighbours by the way off the map: a map and where its top left is */
    public static function surround(Map $map, array $neighbours = []): Map
    {
        $b = self::BEYOND;
        $char = array_flip(array_map(fn(array $k) => $k[0], Tile::KINDS));
        $rows = [];
        for ($y = -$b['up']; $y < $map->height + $b['down']; $y++) {
            $row = '';
            for ($x = -$b['left']; $x < $map->width + $b['right']; $x++) {
                if ($map->inside($x, $y)) {
                    $row .= $map->tile($x, $y);
                    continue;
                }
                foreach ($neighbours as [$next, $nx, $ny]) {
                    if (!$next->indoor && $next->inside($x - $nx, $y - $ny)) {
                        $row .= $next->tile($x - $nx, $y - $ny);
                        continue 2;
                    }
                }
                $edge = $map->tile(max(0, min($map->width - 1, $x)), max(0, min($map->height - 1, $y)));
                $sides = array_filter(['left' => $x < 0, 'right' => $x >= $map->width, 'up' => $y < 0, 'down' => $y >= $map->height]);
                $exit = count($sides) === 1 && isset($map->exits[array_key_first($sides)]);
                if (Tile::name($edge) === 'water' || ($exit && Tile::walkable($edge))) {
                    $row .= $edge;
                    continue;
                }
                $n = Sprites::noise($x, $y, 'beyond');
                $row .= $char['grass'];
                foreach (self::WILD as $kind => $upTo) {
                    if ($n < $upTo) {
                        $row[strlen($row) - 1] = $char[$kind];
                        break;
                    }
                }
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
        foreach ([...array_unique(self::GROUND), ...self::HOUSE, ...self::ROOM, 'thatch'] as $ground) {
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
        $color = self::shade(self::AS_WRITTEN, $x, $y);
        return $kind === 'water' ? [self::WATER_TOP, self::WATER_BOTTOM, $color] : [self::GROUND_TOP, self::GROUND_BOTTOM, $color];
    }

    /**
     * The blocks standing on a map's ground, as faces() takes them: indoors a room's walls, WALL_TOP high, but those
     * with the floor behind them (north, away from the camera), which are cut LOW_WALL_TOP high, as HD-2D cuts away
     * a room's near wall; outdoors houses, the walls and the door in them HOUSE_TOP high. None for another tile.
     * @return \Closure(string, int, int): ?array{0: float, 1: float, 2: array}
     */
    public static function blocks(Map $map): \Closure
    {
        return function (string $kind, int $x, int $y) use ($map): ?array {
            $shade = self::shade(self::AS_WRITTEN, $x, $y);
            if ($map->indoor) {
                return $kind !== 'wall' ? null : [$map->walkable($x, $y - 1) ? self::LOW_WALL_TOP : self::WALL_TOP, self::GROUND_TOP, $shade];
            }
            $inWall = fn(int $dx) => $map->inside($x + $dx, $y) && Tile::name($map->tile($x + $dx, $y)) === 'wall';
            return $kind === 'wall' || ($kind === 'door' && ($inWall(-1) || $inWall(1))) ? [self::HOUSE_TOP, self::GROUND_TOP, $shade] : null;
        };
    }

    /**
     * The houses of a map (blocks(), outdoors): each the tiles that touch, the walls and the door, and the box round
     * them, [x0, x1, y0, y1] in tiles.
     * @return list<array{0: array{0: int, 1: int, 2: int, 3: int}, 1: array<string, true>}> each [its box, its tiles by "x,y"]
     */
    public static function houses(Map $map): array
    {
        $block = self::blocks($map);
        $house = fn(int $x, int $y) => $map->inside($x, $y) && $block(Tile::name($map->tile($x, $y)), $x, $y) !== null;
        $seen = [];
        $out = [];
        for ($y = 0; $y < $map->height; $y++) {
            for ($x = 0; $x < $map->width; $x++) {
                if (!$house($x, $y) || isset($seen["$x,$y"])) {
                    continue;
                }
                [$x0, $x1, $y0, $y1] = [$x, $x, $y, $y];
                $tiles = ["$x,$y" => true];
                $todo = [[$x, $y]];
                $seen["$x,$y"] = true;
                while ($todo !== []) {
                    [$tx, $ty] = array_pop($todo);
                    [$x0, $x1, $y0, $y1] = [min($x0, $tx), max($x1, $tx), min($y0, $ty), max($y1, $ty)];
                    foreach ([[1, 0], [-1, 0], [0, 1], [0, -1]] as [$dx, $dy]) {
                        $key = ($tx + $dx) . ',' . ($ty + $dy);
                        if ($house($tx + $dx, $ty + $dy) && !isset($seen[$key])) {
                            $seen[$key] = $tiles[$key] = true;
                            $todo[] = [$tx + $dx, $ty + $dy];
                        }
                    }
                }
                $out[] = [[$x0, $x1, $y0, $y1], $tiles];
            }
        }
        return $out;
    }

    /**
     * A house's roof (houses()' box): a thatched roof from its eaves, EAVES beyond its walls, up to a ridge along its
     * length (east to west, across the camera's view) RIDGE above them, and a gable at each end. In faces()' form,
     * the thatch running down each slope a tile of it to a tile.
     * @param array{0: int, 1: int, 2: int, 3: int} $box
     * @return list<array>
     */
    public static function roof(array $box): array
    {
        [$x0, $x1, $y0, $y1] = $box;
        $top = self::HOUSE_TOP;
        $ridge = $top + self::RIDGE;
        [$wl, $wr, $zn, $zs] = [$x0 - 0.5, $x1 + 0.5, $y0 - 0.5, $y1 + 0.5];
        [$el, $er, $en, $es] = [$wl - self::EAVES, $wr + self::EAVES, $zn - self::EAVES, $zs + self::EAVES];
        $zm = ($zn + $zs) / 2;
        $eave = $top - self::RIDGE * self::EAVES / ($zm - $zn); // the slope carried on past the wall
        $slope = hypot($zm - $en, $ridge - $eave);
        $long = $er - $el;
        $run = [[0.0, $slope], [$long, $slope], [$long, 0.0], [0.0, 0.0]];
        $white = self::AS_WRITTEN;
        $up = 1.0 / hypot(self::RIDGE, $zm - $zn);
        $half = $zm - $zn;
        $gable = [[0.0, 1.0], [2 * $half, 1.0], [$half, 0.0], [$half, 0.0]];
        return [
            // the slope to the south, toward the camera, and the one to the north
            [[[$el, $eave, $es], [$er, $eave, $es], [$er, $ridge, $zm], [$el, $ridge, $zm]], [0.0, $half * $up, self::RIDGE * $up], $white, $run],
            [[[$er, $eave, $en], [$el, $eave, $en], [$el, $ridge, $zm], [$er, $ridge, $zm]], [0.0, $half * $up, -self::RIDGE * $up], $white, $run],
            // the gables, a triangle each (a face whose two top corners are one)
            [[[$wr, $top, $zs], [$wr, $top, $zn], [$wr, $ridge, $zm], [$wr, $ridge, $zm]], [1.0, 0.0, 0.0], $white, $gable],
            [[[$wl, $top, $zn], [$wl, $top, $zs], [$wl, $ridge, $zm], [$wl, $ridge, $zm]], [-1.0, 0.0, 0.0], $white, $gable],
        ];
    }

    /**
     * Whether a house (houses()' box) hides the player, at $px, $pz in the same places: the player behind its front
     * (its eaves), or in it (at the door), within its width, and near enough that its ridge shows above their middle
     * on the screen. Such a house is drawn faded, as the things that hide the player (fades()).
     * @param array{0: int, 1: int, 2: int, 3: int} $box
     */
    public static function hides(array $box, float $px, float $pz): bool
    {
        [$x0, $x1, $y0, $y1] = $box;
        [$pw, $ph] = Sprites::size('people/person');
        $slant = atan2(self::CAMERA_HEIGHT, self::CAMERA_BACK);
        // on the screen, how far the ridge shows above the player's middle: the ridge lies (y0 + y1) / 2, and each
        // tile farther from the camera shows sin(slant) higher, each tile of height cos(slant)
        $over = ($pz - ($y0 + $y1) / 2) * sin($slant) + (self::HOUSE_TOP + self::RIDGE) * cos($slant) - $ph / 2;
        return $px > $x0 - 0.5 - $pw / 2 && $px < $x1 + 0.5 + $pw / 2 && $pz < $y1 + 0.5 + self::EAVES && $over > 0;
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
     * faces() by the texture each is drawn with: the one of the kind of tile it belongs to ($textures: GROUND, HOUSE,
     * ROOM), a top's the tile it covers, a side's the tile it stands at the edge of. A door's (houseDoor) is the whole
     * of its texture on each face, not tiled, so that the door shows once, at its foot.
     * @param array<string, string> $textures by kind of tile
     * @return array<string, list<array>>
     */
    public static function byTexture(Map $map, array $faces, array $textures): array
    {
        $out = [];
        foreach ($faces as $f) {
            [$corners, $n] = $f;
            $x = (int)round(array_sum(array_column($corners, 0)) / 4 - $n[0] / 2);
            $y = (int)round(array_sum(array_column($corners, 2)) / 4 - $n[2] / 2);
            $texture = $textures[Tile::name($map->tile($x, $y))];
            if ($texture === 'houseDoor') {
                $f[3] = [[0.0, 1.0], [1.0, 1.0], [1.0, 0.0], [0.0, 0.0]];
            }
            $out[$texture][] = $f;
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
    private function faced(array $faces, string $texture, bool $shadow, Vec3 $at): ?Entity
    {
        if ($faces === []) {
            return null;
        }
        $e = new Entity(self::mesh($faces), new Material(shininess: 8.0, texture: Sprites::image($texture, 'down', 0)), position: $at);
        $e->castShadow = $shadow;
        $e->pickable = false;
        return $this->scene->add($e);
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

    /** Draws faded the things and the houses that hide the player on their tile ($px, $pz), the rest as they are (fades(), hides()); again only when the player is on another tile. */
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
        foreach ($this->houses as [$box, $drawn]) {
            $faded = self::hides($box, $px, $pz);
            foreach ($drawn as [$e, $texture]) {
                if ($e !== null) {
                    $e->material->texture = Sprites::image($texture, 'down', 0, faded: $faded);
                }
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

    /** A quad the size a sprite is written ($stretch taller, by default the map's stretch()), upright, its bottom's middle at the origin, facing +z. */
    public static function upright(string $sprite, ?float $stretch = null): Mesh
    {
        [$w, $h] = Sprites::size($sprite);
        [$hw, $h] = [$w / 2, $h * ($stretch ?? self::stretch())];
        return self::mesh([[[[-$hw, 0.0, 0.0], [$hw, 0.0, 0.0], [$hw, $h, 0.0], [-$hw, $h, 0.0]], [0.0, 0.0, 1.0], self::AS_WRITTEN]]);
    }

    /** A quad lying a hair over the ground, $w by $d tiles, its middle at the origin. */
    public static function flat(float $w, float $d): Mesh
    {
        [$hw, $hd] = [$w / 2, $d / 2];
        return self::mesh([[[[-$hw, 0.01, $hd], [$hw, 0.01, $hd], [$hw, 0.01, -$hd], [-$hw, 0.01, -$hd]], [0.0, 1.0, 0.0], self::AS_WRITTEN]]);
    }

    /** The dithered shadow (shadow()) as MyPHP's Image, made once. */
    public static function shadowImage(): Image
    {
        return self::$shadow ??= Image::fromPixels(...[...self::SHADOW_PIXELS, self::shadow()])->pixelated();
    }
}
