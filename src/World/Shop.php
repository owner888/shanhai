<?php
declare(strict_types=1);

namespace Shanhai\World;

use Shanhai\Battle\Formulas;

/**
 * Buying from one who sells, with no drawing (docs/numbers.md 17): the things sold, each with its price; one chosen,
 * how many (up and down by one, right and left by ten); confirmed, they are in the bag and paid for. Cancel goes back,
 * and from the list leaves.
 */
final class Shop
{
    /** list, or count (how many of the one chosen) */
    public string $phase = 'list';
    /** Where the cursor is in the list: a thing sold, or after them leaving. */
    public int $at = 0;
    /** How many of the thing chosen. */
    public int $count = 1;
    /** What was just done or cannot be, shown until confirm or cancel; null with nothing to say. */
    public ?string $note = null;
    public bool $done = false;
    private ?Dir $held = null;

    /** @param list<string> $sells ids of data/items.php */
    public function __construct(public readonly array $sells)
    {
    }

    /**
     * @param array<string, int> $bag
     * @param array<string, array> $things data/items.php
     */
    public function step(Controls $c, array &$bag, int &$money, array $things): void
    {
        $pressed = $c->dir !== $this->held ? $c->dir : null;
        $this->held = $c->dir;
        if ($this->note !== null) {
            if ($c->confirm || $c->cancel) {
                $this->note = null;
            }
        } elseif ($this->phase === 'list') {
            if ($pressed === Dir::Up || $pressed === Dir::Down) {
                $this->at = max(0, min(count($this->sells), $this->at + ($pressed === Dir::Down ? 1 : -1)));
            } elseif ($c->cancel || ($c->confirm && $this->at === count($this->sells))) {
                $this->done = true;
            } elseif ($c->confirm) {
                [$this->phase, $this->count] = ['count', 1];
            }
        } elseif ($pressed !== null) {
            $by = match ($pressed) { Dir::Up => 1, Dir::Down => -1, Dir::Right => 10, Dir::Left => -10 };
            $this->count = max(1, min(Formulas::MAX_STACK, $this->count + $by));
        } elseif ($c->cancel) {
            $this->phase = 'list';
        } elseif ($c->confirm) {
            $id = $this->sells[$this->at];
            $cost = $things[$id]['price'] * $this->count;
            if ($cost > $money) {
                $this->note = '贝不够。';
            } elseif (($bag[$id] ?? 0) + $this->count > Formulas::MAX_STACK) {
                $this->note = '背包里放不下这么多。';
            } else {
                $money -= $cost;
                $bag[$id] = ($bag[$id] ?? 0) + $this->count;
                $this->note = '买了' . $this->count . '枚' . $things[$id]['name'] . '，花了' . $cost . '贝。';
            }
            $this->phase = 'list';
        }
    }

    /** The list showing. @param array<string, array> $things @return list<string> */
    public function items(array $things): array
    {
        if ($this->phase === 'count') {
            $thing = $things[$this->sells[$this->at]];
            return [$thing['name'] . ' ×' . $this->count . '  ' . $thing['price'] * $this->count . '贝'];
        }
        return [...array_map(fn(string $id) => $things[$id]['name'] . '  ' . $things[$id]['price'] . '贝', $this->sells), '离开'];
    }

    /** Where the cursor of the list showing is. */
    public function at(): int
    {
        return $this->phase === 'count' ? 0 : $this->at;
    }
}
