<?php
declare(strict_types=1);

namespace Shanhai\World;

use Shanhai\Battle\{Fighter, Medicine};
use Shanhai\Cursor;

/**
 * The menu the menu button opens outside battle, with no drawing: the party's screen (PartyMenu), when there is a
 * party, what its beasts hold (a thing of the bag given to one, or taken back), the bag, whose medicines are used on a beast (docs/numbers.md 21), and saving, which closes the menu with $saves set for the World to do. Cancel goes back, and from the first list closes it; the menu button closes it from anywhere.
 */
final class Menu
{
    public const ENTRIES = ['party' => '异兽', 'hold' => '携带', 'bag' => '背包', 'save' => '存档'];

    /** top, party, bag, hold (the party, each with what it holds), give (the things of the bag a beast can hold) or use (the party, to use a thing of the bag on one) */
    public string $phase = 'top';
    public Cursor $cursor;
    public ?PartyMenu $party = null;
    public bool $done = false;
    /** The player chose to save. */
    public bool $saves = false;
    /** The beast of the party a thing is being chosen for. */
    private int $holder = 0;
    /** The thing of the bag being used on a beast (the 'use' list), an id. */
    private string $using = '';
    /** What was just done or cannot be, shown until confirm or cancel; null with nothing to say. */
    public ?string $note = null;

    /** @param bool $canSave whether the game has a file to save to */
    public function __construct(private readonly bool $canSave)
    {
        $this->cursor = new Cursor();
    }

    /**
     * The first list's entries: the party's only when there is one, what the beasts hold only when there is a thing to
     * hold, saving only when the game can. @param list<Fighter> $party @return list<string> the keys of ENTRIES
     */
    private function entries(array $party, array $bag, array $things): array
    {
        $held = array_filter($party, fn(Fighter $f) => $f->item !== null) !== [] || self::holdable($bag, $things) !== [];
        return [...($party === [] ? [] : ['party', ...($held ? ['hold'] : [])]), 'bag', ...($this->canSave ? ['save'] : [])];
    }

    /** The things of $bag a beast can hold. @return list<string> ids */
    private static function holdable(array $bag, array $things): array
    {
        return array_values(array_filter(array_keys($bag), fn(string $id) => isset($things[$id]['hold'])));
    }

    /**
     * @param list<Fighter> $party which the party's screen may put in another order, and whose beasts may be given things to hold
     * @param array<string, int> $bag which a thing given leaves and one taken back comes to
     * @param array<string, array> $things data/items.php
     */
    public function step(Controls $c, array &$party, array &$bag, array $things): void
    {
        if ($this->note !== null) {
            if ($c->confirm || $c->cancel) {
                $this->note = null;
            }
        } elseif ($c->menu) {
            $this->done = true;
        } elseif ($this->phase === 'party') {
            $this->party->step($c, $party);
            if ($this->party->done) {
                [$this->phase, $this->party] = ['top', null];
            }
        } elseif ($this->cursor->move($c, max(1, count($this->items($party, $bag, $things))))) {
            return;
        } elseif ($c->cancel) {
            if ($this->phase === 'top') {
                $this->done = true;
            } else {
                [$this->phase, $this->cursor] = [['give' => 'hold', 'use' => 'bag'][$this->phase] ?? 'top', new Cursor()];
            }
        } elseif ($c->confirm && $this->phase === 'hold') {
            // confirm on a beast that holds a thing takes it back to the bag; on one that holds none, opens what it could be given
            $f = $party[$this->cursor->at];
            if ($f->item !== null) {
                $bag[$f->item] = ($bag[$f->item] ?? 0) + 1;
                $f->item = null;
            } elseif (self::holdable($bag, $things) !== []) {
                [$this->phase, $this->holder, $this->cursor] = ['give', $this->cursor->at, new Cursor()];
            }
        } elseif ($c->confirm && $this->phase === 'give') {
            $id = self::holdable($bag, $things)[$this->cursor->at];
            if (--$bag[$id] === 0) {
                unset($bag[$id]);
            }
            $party[$this->holder]->item = $id;
            [$this->phase, $this->cursor] = ['hold', new Cursor()];
        } elseif ($c->confirm && $this->phase === 'bag' && $bag !== []) {
            // docs/numbers.md 21: a thing that is used on a beast opens the party to choose one; another is only looked at
            $id = array_keys($bag)[$this->cursor->at];
            if (Medicine::is($things[$id]) && $party !== []) {
                [$this->phase, $this->using, $this->cursor] = ['use', $id, new Cursor()];
            }
        } elseif ($c->confirm && $this->phase === 'use') {
            $f = $party[$this->cursor->at];
            if (!Medicine::apply($things[$this->using], $f)) {
                $this->note = '用了也没有效果。';
                return;
            }
            $this->note = '给' . $f->species['name'] . '用了' . $things[$this->using]['name'] . '。';
            if (--$bag[$this->using] === 0) {
                unset($bag[$this->using]);
                [$this->phase, $this->cursor] = ['bag', new Cursor()];
            }
        } elseif ($c->confirm && $this->phase === 'top') {
            $entry = $this->entries($party, $bag, $things)[$this->cursor->at];
            if ($entry === 'save') {
                [$this->saves, $this->done] = [true, true];
                return;
            }
            $this->phase = $entry;
            $this->cursor = new Cursor();
            $this->party = $entry === 'party' ? new PartyMenu() : null;
        }
    }

    /** The list showing. @param list<Fighter> $party @param array<string, int> $bag @param array<string, array> $table data/items.php @return list<string> */
    public function items(array $party, array $bag, array $table): array
    {
        return match ($this->phase) {
            'top' => array_map(fn(string $key) => self::ENTRIES[$key], $this->entries($party, $bag, $table)),
            'party' => $this->party->items($party),
            'hold' => array_map(fn(Fighter $f) => $f->species['name'] . '  Lv' . $f->level . '  ' . ($f->item === null ? '（空手）' : '持：' . $table[$f->item]['name']), $party),
            'give' => array_map(fn(string $id) => $table[$id]['name'], self::holdable($bag, $table)),
            'bag' => $bag === [] ? ['（空）'] : self::bag($bag, $table),
            'use' => array_map(PartyMenu::line(...), $party),
        };
    }

    /** Where the cursor of the list showing is. */
    public function at(): int
    {
        return $this->phase === 'party' ? $this->party->cursor->at : $this->cursor->at;
    }

    /** A bag's lines, a line a kind of thing with how many. @param array<string, int> $bag @param array<string, array> $table @return list<string> */
    public static function bag(array $bag, array $table): array
    {
        return array_map(fn(string $id, int $n) => $table[$id]['name'] . '  ×' . $n, array_keys($bag), array_values($bag));
    }
}
