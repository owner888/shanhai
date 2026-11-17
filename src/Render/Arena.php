<?php
declare(strict_types=1);

namespace Shanhai\Render;

use MyPHP\Plugins\Scene\{Animator3D, Camera3D, Entity, Material, Mesh, ParticleEmitter3D, Scene};
use MyPHP\Plugins\Scene\Math\Vec3;
use Shanhai\Battle\{Fighter, Screen};

/**
 * The 3D scene of a battle (MyPHP's Plugins\Scene): a round of ground, the player's beast near and to the left with
 * its back to the camera, the other far and to the right, facing it; the camera does not move. sync() shows the
 * beasts the Screen shows, and none for a side whose beast has fallen. A beast's figure is its model of art/beasts;
 * one that has none yet (docs/art.md says how many do) is a body of its first element's color, a head and a tail.
 * What moves is MyPHP's: each figure has an Animator3D (it breathes, its parts swing as its model says, it lunges
 * with its move, recoils when struck), cue() shakes the camera
 * and bursts a ParticleEmitter3D with what the Screen's line tells of.
 */
final class Arena
{
    /** A figure's color by its beast's first element. */
    public const ELEMENTS = [
        'wood' => [0.30, 0.62, 0.32, 1.0],
        'fire' => [0.85, 0.33, 0.20, 1.0],
        'earth' => [0.70, 0.56, 0.30, 1.0],
        'metal' => [0.78, 0.78, 0.82, 1.0],
        'water' => [0.25, 0.48, 0.80, 1.0],
    ];
    private const GROUND = [0.45, 0.66, 0.42, 1.0];
    /** Where each side's beast stands, and the turn about the vertical axis that faces it to the other. */
    private const PLACE = [[-1.6, 1.4, M_PI * 0.75], [1.6, -1.4, -M_PI * 0.25]];

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
        $this->scene = new Scene(new Camera3D(fovYDegrees: 40.0));
        $this->scene->clearColor = [0.62, 0.78, 0.90, 1.0];
        $this->scene->shadowsEnabled = true;
        $this->scene->shadowBounds = 8.0;
        $this->scene->addSkyDome();
        $this->scene->camera->position = new Vec3(-3.2, 3.0, 6.0);
        $this->scene->camera->target = new Vec3(0.2, 0.5, 0.0);
        $ground = new Entity(Mesh::cylinder(5.0, 5.0, 0.2, 40), new Material(self::GROUND, shininess: 4.0), position: new Vec3(0.0, -0.1, 0.0));
        $ground->castShadow = false;
        $ground->pickable = false;
        $this->scene->add($ground);
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
                [$x, $z, $turn] = self::PLACE[$side];
                [$e, $y, $limbs] = self::figure($s->battle->parties[$side][$which]);
                $e->position = new Vec3($x, $y, $z);
                $e->rotation->y = $turn;
                $this->figures[$side] = $this->scene->add($e);
                // made once the figure stands where it will: the animator moves it about that place
                $animator = $this->scene->addAnimator(new Animator3D($e));
                foreach ($limbs as [$limb, $axis, $amplitude, $frequency]) {
                    $animator->addLimb($limb, axis: $axis, amplitude: $amplitude, frequency: $frequency);
                }
                $this->animators[$side] = $animator->play(Animator3D::IDLE);
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

    /**
     * A beast's figure, built facing south: its model, its parts swinging as the model says; or, with none yet, a body;
     * a head, with a snout that shows which way it faces and two eyes that the light does not dim; a tail.
     * @return array{0: Entity, 1: float, 2: list<array{0: Entity, 1: string, 2: float, 3: float}>} the figure, how high it
     *     stands (a model from its feet, the placeholder from its middle), and each limb that swings with its axis, amplitude, frequency
     */
    private static function figure(Fighter $f): array
    {
        if (Voxels::has("beasts/{$f->id}")) {
            $model = Voxels::load("beasts/{$f->id}");
            [$body, $parts] = Voxels::entity("beasts/{$f->id}", name: $f->id);
            $limbs = [];
            foreach ($model['parts'] as $part => $p) {
                if (isset($p['swing'])) {
                    $limbs[] = [$parts[$part], ...$p['swing']];
                }
            }
            return [$body, 0.0, $limbs];
        }
        $color = self::ELEMENTS[$f->species['elements'][0]];
        $skin = fn() => new Material($color, shininess: 16.0);
        $body = new Entity(Mesh::sphere(0.45, 16, 12, $color), $skin(), name: $f->id);
        $head = new Entity(Mesh::sphere(0.28, 14, 10, $color), $skin(), position: new Vec3(0.0, 0.42, 0.30));
        $head->addChild(new Entity(Mesh::cube(0.12, $color), $skin(), position: new Vec3(0.0, -0.02, 0.28)));
        foreach ([-0.11, 0.11] as $x) {
            $head->addChild(new Entity(Mesh::sphere(0.045, 8, 6), Material::unlit([0.06, 0.06, 0.08, 1.0]), position: new Vec3($x, 0.08, 0.24)));
        }
        // a cone lying back from the body: stood on end by Mesh::cone, so turned about x to point behind
        $tail = new Entity(Mesh::cone(0.12, 0.5, 10, $color), $skin(), position: new Vec3(0.0, 0.05, -0.55));
        $tail->rotation->x = -M_PI / 2;
        $body->addChild($head);
        $body->addChild($tail);
        return [$body, 0.45, [[$head, 'x', 0.10, 2.5], [$tail, 'z', 0.30, 3.0]]];
    }
}
