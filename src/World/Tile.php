<?php
declare(strict_types=1);

namespace Shanhai\World;

/** The kinds of tile a map's rows are written in: one character each. */
final class Tile
{
    /** char => [name, walkable, wild beasts appear] */
    public const KINDS = [
        '.' => ['grass', true, false],
        ',' => ['tallGrass', true, true],
        '=' => ['path', true, false],
        '_' => ['floor', true, false],
        'D' => ['door', true, false],
        'T' => ['tree', false, false],
        '~' => ['water', false, false],
        '^' => ['rock', false, false],
        'H' => ['wall', false, false],
    ];

    public static function name(string $char): string
    {
        return self::KINDS[$char][0];
    }

    public static function walkable(string $char): bool
    {
        return self::KINDS[$char][1];
    }

    public static function wild(string $char): bool
    {
        return self::KINDS[$char][2];
    }
}
