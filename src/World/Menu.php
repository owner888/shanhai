<?php
declare(strict_types=1);

namespace Shanhai\World;

use Shanhai\Battle\{Fighter, Formulas, Medicine, Screen};
use Shanhai\{Cursor, Save};

/**
 * The menu the menu button opens outside battle, with no drawing: the party's screen (PartyMenu), when there is a
 * party, what its beasts hold (a thing of the bag given to one, or taken back), the bag, whose medicines are used on a beast (docs/numbers.md 21), and saving: a slot chosen (Save::SLOTS), and, when it has a save, asked once whether to write over it, which closes the menu with $saves set for the World to do. Cancel goes back, and from the first list closes it; the menu button closes it from anywhere.
 */
final class Menu
{
    public const ENTRIES = ['party' => '异兽', 'hold' => '携带', 'bag' => '背包', 'detail' => '详情', 'dex' => '图鉴', 'save' => '存档'];

    /** top, party, detail (the party, to look at one), dex (the record of beasts), bag, hold (the party, each with what it holds), give (the things of the bag a beast can hold), use (the party, to use a thing of the bag on one), slots (the saves, to save to one) or overwrite (asked whether to write over the one chosen) */
    public string $phase = 'top';
    public Cursor $cursor;
    public ?PartyMenu $party = null;
    public bool $done = false;
    /** The slot the player chose to save to; null when none was. */
    public ?string $saves = null;
    /** The slot chosen that has a save, asked of. */
    private string $over = '';
    /** The beast of the party a thing is being chosen for. */
    private int $holder = 0;
    /** The thing of the bag being used on a beast (the 'use' list), an id. */
    private string $using = '';
    /** What was just done or cannot be, shown until confirm or cancel; null with nothing to say. */
    public ?string $note = null;
    /** @var list<string> what is shown after the note, a string a screenful */
    private array $more = [];

    /** @param array<string, ?array{0: string, 1: int}> $slots the slots the player saves to (World::slots()); none when the game cannot be saved */
    public function __construct(private readonly array $slots = [])
    {
        $this->cursor = new Cursor();
    }

    /**
     * The first list's entries: the party's only when there is one, what the beasts hold only when there is a thing to
     * hold, saving only when the game can. @param list<Fighter> $party @return list<string> the keys of ENTRIES
     */
    private function entries(array $party, array $bag, array $things, ?Dex $dex = null): array
    {
        $held = array_filter($party, fn(Fighter $f) => $f->item !== null) !== [] || self::holdable($bag, $things) !== [];
        return [...($party === [] ? [] : ['party', ...($held ? ['hold'] : [])]), 'bag', ...($party === [] ? [] : ['detail']), ...(($dex?->seen ?? []) === [] ? [] : ['dex']), ...($this->slots === [] ? [] : ['save'])];
    }

    /**
     * What is shown of one beast (docs/numbers.md 24), a string a screenful: what it is and how it stands; its stats;
     * its experience and what it holds; its moves; and the word for its individual values (23).
     * @param array{moves: array, elements: array, items: array} $book @return list<string>
     */
    /** How a gender is written after a name, in the classic's words (docs/numbers.md 29). */
    public const GENDER = ['male' => '（牡）', 'female' => '（牝）'];

    public static function detail(Fighter $f, array $book): array
    {
        $s = $f->species;
        $next = $f->level >= Formulas::MAX_LEVEL ? '已到顶' : '到下一级还差 ' . (Formulas::expAtLevel($s['growth'], $f->level + 1) - $f->exp);
        return [
            sprintf('%s%s  Lv%d  %s  体力 %d/%d%s', $s['name'], self::GENDER[$f->gender() ?? ''] ?? '', $f->level, implode('、', array_map(fn(string $e) => $book['elements'][$e]['name'], $s['elements'])), $f->hp, $f->maxHp,
                $f->fainted() ? '  倒' : ($f->status === null ? '' : '  ' . Screen::STATUS[$f->status])),
            sprintf('攻击 %d  防御 %d  特攻 %d  特防 %d  速度 %d', $f->stats['atk'], $f->stats['def'], $f->stats['spa'], $f->stats['spd'], $f->stats['spe']),
            '经验 ' . $f->exp . '，' . $next . '  ' . ($f->item === null ? '空手' : '持：' . $book['items'][$f->item]['name']),
            '招式：' . implode('、', array_map(fn(array $m) => $book['moves'][$m['id']]['name'] . ' ' . $m['pp'] . '/' . $book['moves'][$m['id']]['pp'], $f->moves)),
            '天分：' . Formulas::talent($f->state()['dvs']) . '  和你：' . Formulas::bondWord($f->bond) . ($f->rank === 0 ? '' : '  已受' . Formulas::RITES[$f->rank]['name'] . '礼'),
        ];
    }

    /** The things of $bag a beast can hold. @return list<string> ids */
    public static function holdable(array $bag, array $things): array
    {
        return array_values(array_filter(array_keys($bag), fn(string $id) => isset($things[$id]['hold'])));
    }

    /**
     * @param list<Fighter> $party which the party's screen may put in another order, and whose beasts may be given things to hold
     * @param array<string, int> $bag which a thing given leaves and one taken back comes to
     * @param array<string, array> $things data/items.php
     * @param ?Dex $dex the record of beasts, when there is one to look at
     * @param array $book the tables what is shown of a beast or a kind is read from: beasts, moves, elements, items
     */
    public function step(Controls $c, array &$party, array &$bag, array $things, ?Dex $dex = null, array $book = []): void
    {
        if ($this->note !== null) {
            if ($c->confirm || $c->cancel) {
                $this->note = array_shift($this->more);
            }
        } elseif ($c->menu) {
            $this->done = true;
        } elseif ($this->phase === 'party') {
            $this->party->step($c, $party);
            if ($this->party->done) {
                [$this->phase, $this->party] = ['top', null];
            }
        } elseif ($this->cursor->move($c, max(1, count($this->items($party, $bag, $things, $dex, $book['beasts'] ?? []))))) {
            return;
        } elseif ($this->phase === 'dex' && $this->paged($c, count($book['beasts']))) {
            return;
        } elseif ($c->cancel) {
            if ($this->phase === 'top') {
                $this->done = true;
            } else {
                [$this->phase, $this->cursor] = [['give' => 'hold', 'use' => 'bag', 'overwrite' => 'slots'][$this->phase] ?? 'top', new Cursor()];
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
        } elseif ($c->confirm && $this->phase === 'detail') {
            $this->more = self::detail($party[$this->cursor->at], $book);
            $this->note = array_shift($this->more);
        } elseif ($c->confirm && $this->phase === 'dex') {
            $this->more = $dex->entry($book['beasts'], $book['elements'], $this->cursor->at) ?? ['还没有见过。'];
            $this->note = array_shift($this->more);
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
        } elseif ($c->confirm && $this->phase === 'slots') {
            $slot = (string)array_keys($this->slots)[$this->cursor->at]; // '1' as a key is 1
            if ($this->slots[$slot] === null) {
                [$this->saves, $this->done] = [$slot, true];
            } else {
                [$this->phase, $this->over, $this->cursor] = ['overwrite', $slot, new Cursor()];
            }
        } elseif ($c->confirm && $this->phase === 'overwrite') {
            if ($this->cursor->at === 0) {
                [$this->saves, $this->done] = [$this->over, true];
            } else {
                [$this->phase, $this->cursor] = ['slots', new Cursor()];
            }
        } elseif ($c->confirm && $this->phase === 'top') {
            $entry = $this->entries($party, $bag, $things, $dex)[$this->cursor->at];
            if ($entry === 'save') {
                [$this->phase, $this->cursor] = ['slots', new Cursor()];
                return;
            }
            $this->phase = $entry;
            $this->cursor = new Cursor();
            $this->party = $entry === 'party' ? new PartyMenu() : null;
        }
    }

    /** The left or right the record's list last saw held, a page being turned when one is pressed, not while held. */
    private ?Dir $turned = null;

    /** In the record's list, left and right go a page (Pen::ROWS) back and on; true when the cursor moved. */
    private function paged(Controls $c, int $count): bool
    {
        $pressed = $c->dir !== $this->turned ? $c->dir : null;
        $this->turned = $c->dir;
        if ($pressed !== Dir::Left && $pressed !== Dir::Right) {
            return false;
        }
        $to = max(0, min($count - 1, $this->cursor->at + ($pressed === Dir::Right ? Pen::ROWS : -Pen::ROWS)));
        [$moved, $this->cursor->at] = [$to !== $this->cursor->at, $to];
        return $moved;
    }

    /**
     * The list showing. @param list<Fighter> $party @param array<string, int> $bag @param array<string, array> $table data/items.php
     * @param array<string, array> $beasts data/beasts.php, for the record's list @return list<string>
     */
    public function items(array $party, array $bag, array $table, ?Dex $dex = null, array $beasts = []): array
    {
        return match ($this->phase) {
            'top' => array_map(fn(string $key) => self::ENTRIES[$key], $this->entries($party, $bag, $table, $dex)),
            'detail' => array_map(PartyMenu::line(...), $party),
            'dex' => $dex->lines($beasts),
            'party' => $this->party->items($party),
            'hold' => array_map(fn(Fighter $f) => $f->species['name'] . '  Lv' . $f->level . '  ' . ($f->item === null ? '（空手）' : '持：' . $table[$f->item]['name']), $party),
            'give' => array_map(fn(string $id) => $table[$id]['name'], self::holdable($bag, $table)),
            'bag' => $bag === [] ? ['（空）'] : self::bag($bag, $table),
            'use' => array_map(PartyMenu::line(...), $party),
            'slots' => array_map(fn(int|string $slot) => Title::line((string)$slot, $this->slots[$slot]), array_keys($this->slots)),
            'overwrite' => ['覆盖' . Save::NAMES[$this->over] . '原来的内容', '不覆盖'],
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
