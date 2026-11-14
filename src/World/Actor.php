<?php
declare(strict_types=1);

namespace Shanhai\World;

/** Someone on the grid: the player or an NPC. A step takes World::WALK_STEPS updates, from x,y to toX,toY. */
final class Actor
{
    public bool $moving = false;
    public int $toX = 0, $toY = 0;
    /** How many updates into the step. */
    public int $progress = 0;

    public function __construct(
        public readonly string $id,
        public int $x,
        public int $y,
        public Dir $dir,
        public readonly string $look,
    ) {}

    /** Whether the actor is on, or stepping onto, the tile. */
    public function occupies(int $x, int $y): bool
    {
        return ($this->x === $x && $this->y === $y) || ($this->moving && $this->toX === $x && $this->toY === $y);
    }

    /**
     * Where to draw the actor, in tiles, $alpha (0 to 1) into the next update.
     * @return array{0: float, 1: float}
     */
    public function place(float $alpha): array
    {
        if (!$this->moving) {
            return [(float)$this->x, (float)$this->y];
        }
        $t = min(1.0, ($this->progress + $alpha) / World::WALK_STEPS);
        return [$this->x + ($this->toX - $this->x) * $t, $this->y + ($this->toY - $this->y) * $t];
    }
}
