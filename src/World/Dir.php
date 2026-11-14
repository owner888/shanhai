<?php
declare(strict_types=1);

namespace Shanhai\World;

/** The four ways an actor faces and steps on the grid. y grows downward (south), as the rows of a map. */
enum Dir: string
{
    case Up = 'up';
    case Down = 'down';
    case Left = 'left';
    case Right = 'right';

    public function dx(): int
    {
        return match ($this) { self::Left => -1, self::Right => 1, default => 0 };
    }

    public function dy(): int
    {
        return match ($this) { self::Up => -1, self::Down => 1, default => 0 };
    }

    public function opposite(): self
    {
        return match ($this) { self::Up => self::Down, self::Down => self::Up, self::Left => self::Right, self::Right => self::Left };
    }
}
