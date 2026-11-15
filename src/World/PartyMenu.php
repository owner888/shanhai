<?php
declare(strict_types=1);

namespace Shanhai\World;

use Shanhai\Battle\{Fighter, Screen};
use Shanhai\Cursor;

/**
 * The party's screen outside battle, with no drawing: the beasts in their order, each with its level, hp and status.
 * Confirm on one, then on another, and the two change places: the first of the party is the one a battle begins with.
 * Cancel lets go of the one picked, or closes the screen.
 */
final class PartyMenu
{
    public readonly Cursor $cursor;
    /** The beast picked to change places; null with none. */
    public ?int $picked = null;
    public bool $done = false;

    public function __construct()
    {
        $this->cursor = new Cursor();
    }

    /** @param list<Fighter> $party which this may put in another order */
    public function step(Controls $c, array &$party): void
    {
        if ($this->cursor->move($c, count($party))) {
            return;
        }
        if ($c->cancel || $c->menu) {
            if ($this->picked === null || $c->menu) {
                $this->done = true;
            }
            $this->picked = null;
        } elseif ($c->confirm) {
            $at = $this->cursor->at;
            if ($this->picked === null) {
                $this->picked = $at;
            } else {
                [$party[$this->picked], $party[$at]] = [$party[$at], $party[$this->picked]];
                $this->picked = null;
            }
        }
    }

    /** A line a beast, as the party's lists tell it. */
    public static function line(Fighter $f): string
    {
        $status = $f->fainted() ? '  倒' : ($f->status === null ? '' : '  ' . Screen::STATUS[$f->status]);
        return sprintf('%s  Lv%d  %d/%d%s', $f->species['name'], $f->level, $f->hp, $f->maxHp, $status);
    }

    /** @param list<Fighter> $party @return list<string> the one picked marked */
    public function items(array $party): array
    {
        return array_map(fn(Fighter $f, int $i) => ($i === $this->picked ? '◆ ' : '') . self::line($f), $party, array_keys($party));
    }
}
