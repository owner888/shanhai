<?php
declare(strict_types=1);

namespace Shanhai\World;

use InvalidArgumentException;

/**
 * One map: its tiles and what stands on them. Made from a data/maps/*.php array:
 *
 *     id, name, rows (strings of Tile characters, all one length),
 *     warps    [[x, y, toMap, toX, toY, dir], ...]   stepping on x,y goes there
 *     signs    [[x, y, script], ...]
 *     npcs     [[id, x, y, dir, look, script], ...]
 *     triggers [[x, y, flag, script], ...]            runs once, then sets flag
 */
final class Map
{
    /**
     * @param list<string> $rows
     * @param list<array{0: int, 1: int, 2: string, 3: int, 4: int, 5: string}> $warps
     * @param list<array{0: int, 1: int, 2: list<array>}> $signs
     * @param list<array{0: string, 1: int, 2: int, 3: string, 4: string, 5: list<array>}> $npcs
     * @param list<array{0: int, 1: int, 2: string, 3: list<array>}> $triggers
     */
    private function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly array $rows,
        public readonly int $width,
        public readonly int $height,
        public readonly array $warps,
        public readonly array $signs,
        public readonly array $npcs,
        public readonly array $triggers,
    ) {}

    public static function fromArray(array $a): self
    {
        $rows = $a['rows'];
        $width = strlen($rows[0]);
        foreach ($rows as $y => $row) {
            if (strlen($row) !== $width) {
                throw new InvalidArgumentException("map {$a['id']}: row $y is " . strlen($row) . " wide, not $width");
            }
            foreach (str_split($row) as $x => $char) {
                if (!isset(Tile::KINDS[$char])) {
                    throw new InvalidArgumentException("map {$a['id']}: unknown tile '$char' at $x,$y");
                }
            }
        }
        return new self($a['id'], $a['name'], $rows, $width, count($rows),
            $a['warps'] ?? [], $a['signs'] ?? [], $a['npcs'] ?? [], $a['triggers'] ?? []);
    }

    public function inside(int $x, int $y): bool
    {
        return $x >= 0 && $y >= 0 && $x < $this->width && $y < $this->height;
    }

    public function tile(int $x, int $y): string
    {
        return $this->rows[$y][$x];
    }

    public function walkable(int $x, int $y): bool
    {
        return $this->inside($x, $y) && Tile::walkable($this->rows[$y][$x]);
    }

    /** @return ?array{0: int, 1: int, 2: string, 3: int, 4: int, 5: string} */
    public function warpAt(int $x, int $y): ?array
    {
        foreach ($this->warps as $w) {
            if ($w[0] === $x && $w[1] === $y) {
                return $w;
            }
        }
        return null;
    }

    /** @return ?array{0: int, 1: int, 2: list<array>} */
    public function signAt(int $x, int $y): ?array
    {
        foreach ($this->signs as $s) {
            if ($s[0] === $x && $s[1] === $y) {
                return $s;
            }
        }
        return null;
    }

    /** @return ?array{0: int, 1: int, 2: string, 3: list<array>} */
    public function triggerAt(int $x, int $y): ?array
    {
        foreach ($this->triggers as $t) {
            if ($t[0] === $x && $t[1] === $y) {
                return $t;
            }
        }
        return null;
    }
}
