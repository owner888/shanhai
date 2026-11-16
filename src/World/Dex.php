<?php
declare(strict_types=1);

namespace Shanhai\World;

/**
 * The record of beasts (图鉴, docs/numbers.md 24): which kinds the player has seen, in a battle, and which it has had
 * bound to it. A kind seen shows its name and where it lives; one bound, what the classic says of it besides.
 */
final class Dex
{
    public const KINDS = ['common' => '常兽', 'strange' => '异兽'];

    /** @var array<string, true> */
    public array $seen = [];
    /** @var array<string, true> */
    public array $caught = [];

    public function see(string $id): void
    {
        $this->seen[$id] = true;
    }

    /** A kind bound is seen too. */
    public function bind(string $id): void
    {
        $this->seen[$id] = $this->caught[$id] = true;
    }

    /** A line a kind, in the order of data/beasts.php: its number, its name once seen, and whether it has been bound. @return list<string> */
    public function lines(array $beasts): array
    {
        $lines = [];
        foreach (array_keys($beasts) as $i => $id) {
            $name = isset($this->seen[$id]) ? $beasts[$id]['name'] : '？？？';
            $lines[] = sprintf('%03d  %s%s', $i + 1, $name, isset($this->caught[$id]) ? '  已结契' : '');
        }
        return $lines;
    }

    /**
     * What is known of the kind at $index of data/beasts.php, a string a screenful; null of one not seen.
     * @param array<string, array> $elements data/elements.php
     * @return ?list<string>
     */
    public function entry(array $beasts, array $elements, int $index): ?array
    {
        $id = array_keys($beasts)[$index];
        if (!isset($this->seen[$id])) {
            return null;
        }
        $b = $beasts[$id];
        $first = sprintf('%03d  %s  %s  出没：%s', $index + 1, $b['name'], self::KINDS[$b['kind']], $b['mountain']);
        if (!isset($this->caught[$id])) {
            return [$first];
        }
        $said = array_filter(['叫声' => $b['sound'] ?? null, '征兆' => $b['omen'] ?? null, '功效' => $b['lore'] ?? null]);
        return [
            $first . '  属性：' . implode('、', array_map(fn(string $e) => $elements[$e]['name'], $b['elements'])),
            $b['looks'],
            ...($said === [] ? [] : [implode('  ', array_map(fn(string $what, string $text) => $what . '：' . $text, array_keys($said), $said))]),
        ];
    }
}
