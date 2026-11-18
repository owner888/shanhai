<?php
declare(strict_types=1);

namespace Shanhai\Render;

use MyPHP\Plugins\Scene\{Animator3D, Camera3D, Entity, Material, Mesh, ParticleEmitter3D, Scene};
use MyPHP\Plugins\Scene\Math\Vec3;
use Shanhai\Battle\{Fighter, Screen};
use Shanhai\World\{Map, Tile};

/**
 * The 3D scene of a battle (MyPHP's Plugins\Scene), drawn HD-2D as the maps are (docs/art.md): a meadow (FIELD) of the
 * maps' grounds and things, the player's beast near and to the left facing right, the other far and to the right
 * facing left, toward each other; the camera does not move. A beast is its sprite of art/sprites/beasts, seen from the
 * side, stood upright and not lit, a dithered shadow under it; one that has none yet (docs/art.md says how many do)
 * is the placeholder, in its first element's color. sync() shows the beasts the Screen shows, and none for a side
 * whose beast has fallen. What moves is MyPHP's: each figure has an Animator3D (it breathes, it lunges with its move,
 * recoils when struck), cue() shakes the camera and bursts a ParticleEmitter3D with what the Screen's line tells of.
 */
final class Arena
{
    /** The placeholder's color by its beast's first element. */
    public const ELEMENTS = [
        'wood' => '#4f9e52',
        'fire' => '#d8543a',
        'earth' => '#b48e4c',
        'metal' => '#c8c8d0',
        'water' => '#4078c8',
    ];
    /**
     * Where the battle is fought: a meadow as a map's rows (Tile's characters), the beasts in its middle, trees and
     * rocks round its back and sides, tall grass here and there; its middle the scene's origin.
     */
    public const FIELD = [
        'TTTTTTTTTTTTTTTT',
        'TTT.TTTT.TTT.TTT',
        'T.TT.T..TT.TT.TT',
        '.T^..T...T..^.T.',
        '..,,........,,..',
        '................',
        '.,..............',
        '..............,.',
        '................',
        '^.............,^',
        '..,,..........,,',
        '................',
    ];
    /** Where each side's beast stands, x and z, and the way it faces (toward the other). */
    private const PLACE = [[-2.2, 1.6, 'right'], [2.2, -1.2, 'left']];
    /** The camera: where it stands and what it looks at; a sprite stands taller by its slant, as on the maps (Stage::stretch). */
    private const CAMERA = [0.0, 3.4, 8.6], LOOK = [0.0, 0.8, 0.0];

    /** Seconds after a move's lunge begins that it lands: when the one struck recoils. */
    private const LANDS = 0.2;
    private const SPARK = [1.0, 0.92, 0.55, 1.0], DUST = [0.75, 0.75, 0.72, 1.0], BOND = [1.0, 0.85, 0.3, 1.0], MEND = [0.55, 0.95, 0.6, 1.0];

    public readonly Scene $scene;
    /** @var array{0: ?Entity, 1: ?Entity} the figure drawn for each side */
    private array $figures = [null, null];
    /** @var array{0: ?Animator3D, 1: ?Animator3D} what moves each figure */
    private array $animators = [null, null];
    /** @var array{0: ?int, 1: ?int} which of its party each figure is; null with none drawn */
    private array $drawn = [null, null];
    /** @var array{0: ?int, 1: ?int} which of the side's beasts has left the field, the line that tells of it having shown: until then it stands, struck */
    private array $gone = [null, null];
    /** What to do when a beast is struck, told whether critically: the game's part, its sound and its pause. */
    private \Closure $onStruck;
    private ParticleEmitter3D $sparks;
    /** @var list<array{0: float, 1: \Closure}> what is still to happen, each in so many seconds */
    private array $later = [];

    public function __construct(Screen $s, \Closure $onStruck)
    {
        $this->onStruck = $onStruck;
        $this->scene = new Scene(new Camera3D(fovYDegrees: Stage::CAMERA_FOV));
        $this->scene->clearColor = [0.62, 0.78, 0.90, 1.0];
        $this->scene->shadowsEnabled = true;
        $this->scene->shadowBounds = 12.0;
        $this->scene->addSkyDome();
        $this->scene->camera->position = new Vec3(...self::CAMERA);
        $this->scene->camera->target = new Vec3(...self::LOOK);
        $this->field();
        $this->sparks = $this->scene->addEmitter(new ParticleEmitter3D(Mesh::cube(0.12)));
        $this->sync($s);
    }

    /** Draws for each side the beast the Screen shows; none once the line of its falling has shown. */
    public function sync(Screen $s): void
    {
        foreach ([0, 1] as $side) {
            $shown = $s->shown[$side];
            $which = $this->gone[$side] === $shown['which'] ? null : $shown['which'];
            if ($which === $this->drawn[$side]) {
                continue;
            }
            if ($this->figures[$side] !== null) {
                $this->scene->remove($this->figures[$side]);
                $this->scene->animators = array_values(array_filter($this->scene->animators, fn(Animator3D $a) => $a !== $this->animators[$side]));
                [$this->figures[$side], $this->animators[$side]] = [null, null];
            }
            if ($which !== null) {
                [$x, $z, $facing] = self::PLACE[$side];
                $e = self::figure($s->battle->parties[$side][$which], $facing);
                $e->position = new Vec3($x, 0.0, $z);
                $this->figures[$side] = $this->scene->add($e);
                // made once the figure stands where it will: the animator moves it about that place
                $this->animators[$side] = $this->scene->addAnimator(new Animator3D($e))->play(Animator3D::IDLE);
            }
            $this->drawn[$side] = $which;
        }
    }

    /** Acts on what the Screen's line tells of (Screen::$cue), once, when the line shows. */
    public function cue(array $cue): void
    {
        $side = $cue['side'] ?? null;
        switch ($cue['what']) {
            case 'use':
                $this->animators[$side]?->play(Animator3D::LUNGE, direction: $this->toward($side, 1 - $side));
                if ($cue['hit'] !== null) {
                    $this->later[] = [self::LANDS, fn() => $this->struck($cue['hit'], $cue['critical'])];
                }
                break;
            case 'hurt':
                $this->struck($side, false);
                break;
            case 'faint':
                $this->gone[$side] = $this->drawn[$side];
                $this->sparks->burst($this->above($side), 24, self::DUST);
                break;
            case 'heal':
                $this->sparks->burst($this->above($side), 16, self::MEND, 1.0, 3.0);
                break;
            case 'caught':
                $this->gone[1] = $this->drawn[1]; // bound, it is the player's: it leaves the field
                $this->sparks->burst($this->above(1), 48, self::BOND);
                break;
            case 'broke':
                $this->animators[1]?->play(Animator3D::HURT, direction: $this->toward(1, 0));
                break;
        }
    }

    /** Steps what was put off; then the Scene's own step moves the figures, the camera and the sparks. */
    public function step(float $dt): void
    {
        foreach ($this->later as $i => [$in, $do]) {
            if ($in - $dt <= 0.0) {
                unset($this->later[$i]);
                $do();
            } else {
                $this->later[$i][0] = $in - $dt;
            }
        }
        $this->later = array_values($this->later);
        $this->scene->step($dt);
    }

    /** The side's beast is struck: it recoils from the other, the camera shakes, sparks fly; more of each for a critical hit. */
    private function struck(int $side, bool $critical): void
    {
        $this->animators[$side]?->play(Animator3D::HURT, direction: $this->toward($side, 1 - $side));
        $this->scene->camera->shake($critical ? 0.30 : 0.18, 0.25);
        $this->sparks->burst($this->above($side), $critical ? 48 : 24, self::SPARK);
        ($this->onStruck)($critical);
    }

    /** From one side's place toward the other's. */
    private function toward(int $from, int $to): Vec3
    {
        return new Vec3(self::PLACE[$to][0] - self::PLACE[$from][0], 0.0, self::PLACE[$to][1] - self::PLACE[$from][1]);
    }

    /** The middle of the side's beast. */
    private function above(int $side): Vec3
    {
        return new Vec3(self::PLACE[$side][0], 0.6, self::PLACE[$side][1]);
    }

    /** Makes every beast's sprite ahead, both ways, and the placeholder in each element's color: once, as the game starts. */
    public static function prepare(): void
    {
        foreach (glob(Sprites::DIR . '/beasts/*.php') as $file) {
            if (basename($file) !== 'placeholder.php') {
                Sprites::prepare('beasts/' . basename($file, '.php'));
            }
        }
        foreach (self::ELEMENTS as $color) {
            Sprites::prepare('beasts/placeholder', ['c' => $color]);
        }
    }

    /** How much taller a sprite stands than written, so that the camera's slant shows it as written (Stage::stretch). */
    public static function stretch(): float
    {
        [$dy, $dz] = [self::CAMERA[1] - self::LOOK[1], self::CAMERA[2] - self::LOOK[2]];
        return hypot($dy, $dz) / $dz;
    }

    /** The sprite a beast is drawn as, and the colors given it: its own, or the placeholder in its first element's color. @return array{0: string, 1: array<string, string>} */
    public static function sprite(Fighter $f): array
    {
        return self::spriteOf($f->id, $f->species);
    }

    /** sprite() of a kind of beast, by its id and its entry of data/beasts.php (the record of beasts shows it). @return array{0: string, 1: array<string, string>} */
    public static function spriteOf(string $id, array $species): array
    {
        return Sprites::has("beasts/$id") ? ["beasts/$id", []] : ['beasts/placeholder', ['c' => self::ELEMENTS[$species['elements'][0]]]];
    }

    /**
     * A beast's figure: its sprite upright, facing $facing ('left' or 'right'), the side toward the camera, not lit;
     * the shadow under it as wide as it is.
     */
    private static function figure(Fighter $f, string $facing): Entity
    {
        [$sprite, $colors] = self::sprite($f);
        $e = new Entity(Stage::upright($sprite, self::stretch()), Material::unlit(texture: Sprites::image($sprite, $facing, 0, $colors)), name: $f->id);
        $e->castShadow = false;
        $e->pickable = false;
        [$w] = Sprites::size($sprite);
        $under = new Entity(Stage::flat($w * 0.9, $w * 0.35), Material::unlit(texture: Stage::shadowImage()));
        $under->castShadow = false;
        $under->pickable = false;
        $e->addChild($under);
        return $e;
    }

    /** Adds the meadow (FIELD): its ground through its textures, and its things upright, a shadow under each that has one (Stage::THINGS). */
    private function field(): void
    {
        $map = Map::fromArray(['id' => 'field', 'name' => 'field', 'rows' => self::FIELD]);
        $at = new Vec3(-$map->width / 2 + 0.5, 0.0, -$map->height / 2 + 0.5);
        foreach (Stage::byTexture($map, Stage::faces($map, Stage::ground(...)), Stage::GROUND) as $texture => $faces) {
            $e = new Entity(Stage::mesh($faces), new Material(shininess: 8.0, texture: Sprites::image("ground/$texture", 'down', 0)), position: $at);
            $e->castShadow = false;
            $e->pickable = false;
            $this->scene->add($e);
        }
        for ($y = 0; $y < $map->height; $y++) {
            for ($x = 0; $x < $map->width; $x++) {
                $kind = Tile::name($map->tile($x, $y));
                if (!isset(Stage::THINGS[$kind])) {
                    continue;
                }
                [$sprite, $before, $off, $shadow] = Stage::THINGS[$kind];
                // off the tile's middle as on the maps, so that a wood is not a grid, and trees side by side one before the other
                $sx = $x + $off * (2.0 * Sprites::noise($x, $y, 'stand') - 1.0);
                $sz = $y + $before - ($off > 0.0 && $x % 2 !== 0 ? 0.04 : 0.0);
                $place = new Vec3($sx + $at->x, 0.0, $sz + $at->z);
                $thing = new Entity(Stage::upright($sprite, self::stretch()), Material::unlit(texture: Sprites::image($sprite, 'down', 0)), position: $place);
                $thing->castShadow = false;
                $thing->pickable = false;
                if ($shadow !== null) {
                    $under = new Entity(Stage::flat(...$shadow), Material::unlit(texture: Stage::shadowImage()), position: new Vec3(0.0, 0.0, -$before));
                    $under->castShadow = false;
                    $under->pickable = false;
                    $thing->addChild($under);
                }
                $this->scene->add($thing);
            }
        }
    }
}
