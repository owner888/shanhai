<?php
declare(strict_types=1);

namespace Shanhai;

/** What a move of one element does against a beast of another (docs/elements.md, docs/numbers.md 3.3). */
final class Elements
{
    /** A move's damage is multiplied by one of these, then divided by ten, once for each element of the beast it hits. */
    public const DOUBLE = 20, EVEN = 10, HALF = 5;

    /** @param array<string, array> $elements data/elements.php */
    public function __construct(private readonly array $elements) {}

    /** The multiplier, in tenths, of a move of element $attack against one element $defend. */
    public function factor(string $attack, string $defend): int
    {
        $e = $this->elements[$attack];
        return match ($defend) {
            $e['overcomes'] => self::DOUBLE,
            $e['feeds'] => self::HALF,
            default => self::EVEN,
        };
    }

    /**
     * The multipliers, in tenths, to apply in turn against a beast of elements $defend: what is not EVEN, in the beast's order.
     * @param list<string> $defend
     * @return list<int>
     */
    public function factors(string $attack, array $defend): array
    {
        $out = [];
        foreach ($defend as $d) {
            if (($f = $this->factor($attack, $d)) !== self::EVEN) {
                $out[] = $f;
            }
        }
        return $out;
    }
}
