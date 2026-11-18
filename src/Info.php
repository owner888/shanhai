<?php
declare(strict_types=1);

namespace Shanhai;

/**
 * What the box beside a list says of the entry the cursor is on (Render\Ui::info): a move's element, power and the
 * rest, a thing's use and where it comes from. Lines, the first the one that names it; Ui wraps them to the box.
 * PHP alone, so that the tests read them.
 */
final class Info
{
    /**
     * A move: its element (or none), power, accuracy and how many times it is left of how many, and where it comes from.
     * @param array $move data/moves.php's
     * @param array<string, array> $elements data/elements.php
     * @return list<string>
     */
    public static function move(array $move, array $elements, int $pp): array
    {
        $element = $move['element'] === null ? '无属性' : $elements[$move['element']]['name'] . '属性';
        $power = $move['power'] > 0 ? '威力 ' . $move['power'] : '不造成伤害';
        return [
            $move['name'],
            sprintf('%s　%s　命中 %d　次数 %d/%d', $element, $power, $move['accuracy'], $pp, $move['pp']),
            $move['from'] ?? '',
        ];
    }

    /**
     * A thing of the bag: its name, and what it does and where it comes from; with its price where it is sold.
     * @param array $thing data/items.php's
     * @return list<string>
     */
    public static function thing(array $thing, ?int $price = null): array
    {
        return array_values(array_filter([
            $thing['name'] . ($price === null ? '' : '　' . $price . ' 贝'),
            $thing['from'] ?? '',
        ], fn(string $l) => $l !== ''));
    }
}
