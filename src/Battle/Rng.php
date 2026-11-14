<?php
declare(strict_types=1);

namespace Shanhai\Battle;

use LogicException;

/** The battle's dice: bytes, 0 to 255, as every rule in docs/numbers.md asks for. From a seed, the same battle again; from a script, a test's own rolls. */
final class Rng
{
    private int $state = 0;
    /** @var ?list<int> */
    private ?array $script = null;

    public static function seeded(int $seed): self
    {
        $r = new self();
        $r->state = $seed & 0x7fffffff;
        return $r;
    }

    /** @param list<int> $bytes the rolls, in the order they are asked for; asking for one more is an error */
    public static function scripted(array $bytes): self
    {
        $r = new self();
        $r->script = $bytes;
        return $r;
    }

    public function byte(): int
    {
        if ($this->script !== null) {
            if ($this->script === []) {
                throw new LogicException('the scripted rolls ran out');
            }
            return array_shift($this->script);
        }
        $this->state = ($this->state * 1103515245 + 12345) & 0x7fffffff;
        return ($this->state >> 16) & 0xff;
    }

    /** Adds rolls to a script, for a test's next turn. @param list<int> $bytes */
    public function more(array $bytes): void
    {
        $this->script = [...($this->script ?? []), ...$bytes];
    }

    /** How many scripted rolls are left: a test checks none are. */
    public function left(): int
    {
        return count($this->script ?? []);
    }
}
