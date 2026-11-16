<?php
declare(strict_types=1);

namespace Shanhai\World;

use Shanhai\Battle\Fighter;
use Shanhai\Cursor;

/**
 * The pen (兽栏) where beasts not with the player are kept, with no drawing: fourteen boxes of twenty, one of them
 * the current one (docs/numbers.md 16). A beast is taken from the current box into the party, left there from the
 * party, or set free from it; another box is made the current one. Cancel goes back, and from the first list leaves.
 */
final class Pen
{
    /** How many boxes there are, and how many beasts one holds. */
    public const BOXES = 14, BOX = 20;
    /** How many lines of a list show at once. */
    public const ROWS = 8;
    public const ENTRIES = ['take' => '取出', 'leave' => '寄存', 'free' => '放归', 'box' => '换栏'];
    private const NUMERALS = ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二', '十三', '十四'];

    /** top, take, leave, free, sure (whether to set the one picked free) or box */
    public string $phase = 'top';
    public Cursor $cursor;
    /** What was just done or cannot be, shown until confirm or cancel; null with nothing to say. */
    public ?string $note = null;
    public bool $done = false;
    /** The beast of the current box picked to be set free. */
    private int $picked = 0;

    public function __construct()
    {
        $this->cursor = new Cursor();
    }

    /** What a box is called. */
    public static function name(int $box): string
    {
        return '第' . self::NUMERALS[$box] . '栏';
    }

    /** Fourteen boxes with nothing in them. @return list<list<Fighter>> */
    public static function emptied(): array
    {
        return array_fill(0, self::BOXES, []);
    }

    /**
     * @param list<Fighter> $party
     * @param list<list<Fighter>> $boxes
     * @param int $box the current one
     * @param array<string, array> $moves data/moves.php, by which a beast left here is made whole
     */
    public function step(Controls $c, array &$party, array &$boxes, int &$box, array $moves): void
    {
        if ($this->note !== null) {
            if ($c->confirm || $c->cancel) {
                $this->note = null;
            }
            return;
        }
        if ($this->cursor->move($c, max(1, $this->count($party, $boxes, $box)))) {
            return;
        }
        if ($c->cancel) {
            if ($this->phase === 'top') {
                $this->done = true;
            } else {
                $this->to($this->phase === 'sure' ? 'free' : 'top');
            }
            return;
        }
        if (!$c->confirm) {
            return;
        }
        $at = $this->cursor->at;
        switch ($this->phase) {
            case 'top':
                $this->to(array_keys(self::ENTRIES)[$at]);
                break;
            case 'box':
                $box = $at;
                $this->to('top');
                break;
            case 'take':
                if (!isset($boxes[$box][$at])) {
                    break;
                }
                if (count($party) >= World::PARTY) {
                    $this->note = '队伍已经满了。';
                    break;
                }
                $party[] = $f = array_splice($boxes[$box], $at, 1)[0];
                $this->note = '把' . $f->species['name'] . '带回了队伍。';
                $this->to('take');
                break;
            case 'leave':
                $f = $party[$at];
                if (count($party) === 1) {
                    $this->note = '最后一只不能寄存。';
                } elseif (array_filter($party, fn(Fighter $other) => $other !== $f && !$other->fainted()) === []) {
                    $this->note = '队伍里要留一只能战斗的。';
                } elseif (count($boxes[$box]) >= self::BOX) {
                    $this->note = self::name($box) . '满了。';
                } else {
                    array_splice($party, $at, 1);
                    $f->restore($moves);
                    $boxes[$box][] = $f;
                    $this->note = '把' . $f->species['name'] . '寄在了' . self::name($box) . '。';
                    $this->to('leave');
                }
                break;
            case 'free':
                if (isset($boxes[$box][$at])) {
                    $this->picked = $at;
                    $this->to('sure');
                }
                break;
            case 'sure':
                if ($at === 1) {
                    $f = array_splice($boxes[$box], $this->picked, 1)[0];
                    $this->note = $f->species['name'] . '回山里去了。';
                }
                $this->to('free');
                break;
        }
    }

    private function to(string $phase): void
    {
        [$this->phase, $this->cursor] = [$phase, new Cursor()];
    }

    /** How many entries the list showing has. */
    private function count(array $party, array $boxes, int $box): int
    {
        return match ($this->phase) {
            'top' => count(self::ENTRIES),
            'take', 'free' => count($boxes[$box]),
            'leave' => count($party),
            'sure' => 2,
            'box' => self::BOXES,
        };
    }

    /**
     * The list showing, whole. @param list<Fighter> $party @param list<list<Fighter>> $boxes @return list<string>
     */
    public function items(array $party, array $boxes, int $box): array
    {
        $lines = fn(array $beasts) => $beasts === [] ? ['（空）'] : array_map(PartyMenu::line(...), $beasts);
        return match ($this->phase) {
            'top' => [self::ENTRIES['take'], self::ENTRIES['leave'], self::ENTRIES['free'], self::ENTRIES['box'] . '  ' . self::name($box) . ' ' . count($boxes[$box]) . '/' . self::BOX],
            'take', 'free' => $lines($boxes[$box]),
            'leave' => $lines($party),
            'sure' => ['留下', '放归' . $boxes[$box][$this->picked]->species['name']],
            'box' => array_map(fn(int $i) => self::name($i) . '  ' . count($boxes[$i]) . '/' . self::BOX . ($i === $box ? '  ◆' : ''), array_keys($boxes)),
        };
    }

    /**
     * The lines of $items that show with the cursor at $at, ROWS at most, and where the cursor is among them.
     * @param list<string> $items @return array{0: list<string>, 1: int}
     */
    public static function window(array $items, int $at): array
    {
        $from = max(0, min($at - intdiv(self::ROWS, 2), count($items) - self::ROWS));
        return [array_slice($items, $from, self::ROWS), $at - $from];
    }
}
