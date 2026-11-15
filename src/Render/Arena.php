<?php
declare(strict_types=1);

namespace Shanhai\Render;

use MyPHP\Plugins\Scene\{Camera3D, Entity, Material, Mesh, Scene};
use MyPHP\Plugins\Scene\Math\Vec3;
use Shanhai\Battle\{Fighter, Screen};

/**
 * The 3D scene of a battle (MyPHP's Plugins\Scene): a round of ground, the player's beast near and to the left with
 * its back to the camera, the other far and to the right, facing it; the camera does not move. sync() shows the
 * beasts the Screen shows, and none for a side whose beast has fallen. A beast's figure is a body of its first
 * element's color and a head, until the beasts have models (roadmap phase 4).
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
    private const GROUND = [0.42, 0.60, 0.36, 1.0];
    /** Where each side's beast stands, and the turn about the vertical axis that faces it to the other. */
    private const PLACE = [[-1.6, 1.4, M_PI * 0.75], [1.6, -1.4, -M_PI * 0.25]];

    public readonly Scene $scene;
    /** @var array{0: ?Entity, 1: ?Entity} the figure drawn for each side */
    private array $figures = [null, null];
    /** @var array{0: ?int, 1: ?int} which of its party each figure is; null with none drawn */
    private array $drawn = [null, null];

    public function __construct(Screen $s)
    {
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
        $this->sync($s);
    }

    /** Draws for each side the beast the Screen shows, or none when its hp shown is 0. */
    public function sync(Screen $s): void
    {
        foreach ([0, 1] as $side) {
            $which = $s->shown[$side]['hp'] > 0 ? $s->shown[$side]['which'] : null;
            if ($which === $this->drawn[$side]) {
                continue;
            }
            if ($this->figures[$side] !== null) {
                $this->scene->remove($this->figures[$side]);
                $this->figures[$side] = null;
            }
            if ($which !== null) {
                [$x, $z, $turn] = self::PLACE[$side];
                $e = self::figure($s->battle->parties[$side][$which]);
                $e->position = new Vec3($x, 0.45, $z);
                $e->rotation->y = $turn;
                $this->figures[$side] = $this->scene->add($e);
            }
            $this->drawn[$side] = $which;
        }
    }

    /** A beast's figure, built facing south: a body, a head, and a snout that shows which way it faces. */
    private static function figure(Fighter $f): Entity
    {
        $color = self::ELEMENTS[$f->species['elements'][0]];
        $body = new Entity(Mesh::sphere(0.45, 16, 12, $color), new Material($color, shininess: 16.0), name: $f->id);
        $body->addChild(new Entity(Mesh::sphere(0.28, 14, 10, $color), new Material($color, shininess: 16.0), position: new Vec3(0.0, 0.42, 0.30)));
        $body->addChild(new Entity(Mesh::cube(0.12, $color), new Material($color), position: new Vec3(0.0, 0.40, 0.58)));
        return $body;
    }
}
