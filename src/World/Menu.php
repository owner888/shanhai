<?php
declare(strict_types=1);

namespace Shanhai\World;

use Shanhai\Battle\Fighter;
use Shanhai\Cursor;

/**
 * The menu the menu button opens outside battle, with no drawing: the party's screen (PartyMenu), when there is a
 * party, the bag, which is only looked at, and saving, which closes the menu with $saves set for the World to do. Cancel goes back, and from the first list closes it; the menu button closes it from anywhere.
 */
final class Menu
{
    public const ENTRIES = ['party' => '异兽', 'bag' => '背包', 'save' => '存档'];

    /** top, party or bag */
    public string $phase = 'top';
    public Cursor $cursor;
    public ?PartyMenu $party = null;
    public bool $done = false;
    /** The player chose to save. */
    public bool $saves = false;

    /** @param bool $canSave whether the game has a file to save to */
    public function __construct(private readonly bool $canSave)
    {
        $this->cursor = new Cursor();
    }

    /** The first list's entries: the party's only when there is one, saving only when the game can. @param list<Fighter> $party @return list<string> the keys of ENTRIES */
    private function entries(array $party): array
    {
        return [...($party === [] ? [] : ['party']), 'bag', ...($this->canSave ? ['save'] : [])];
    }

    /**
     * @param list<Fighter> $party which the party's screen may put in another order
     * @param array<string, int> $bag
     */
    public function step(Controls $c, array &$party, array $bag): void
    {
        if ($c->menu) {
            $this->done = true;
        } elseif ($this->phase === 'party') {
            $this->party->step($c, $party);
            if ($this->party->done) {
                [$this->phase, $this->party] = ['top', null];
            }
        } elseif ($this->cursor->move($c, $this->phase === 'top' ? count($this->entries($party)) : max(1, count($bag)))) {
            return;
        } elseif ($c->cancel) {
            if ($this->phase === 'top') {
                $this->done = true;
            } else {
                [$this->phase, $this->cursor] = ['top', new Cursor()];
            }
        } elseif ($c->confirm && $this->phase === 'top') {
            $entry = $this->entries($party)[$this->cursor->at];
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
            'top' => array_map(fn(string $key) => self::ENTRIES[$key], $this->entries($party)),
            'party' => $this->party->items($party),
            'bag' => $bag === [] ? ['（空）'] : self::bag($bag, $table),
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
