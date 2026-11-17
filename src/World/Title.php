<?php
declare(strict_types=1);

namespace Shanhai\World;

use Shanhai\{Cursor, Save};

/**
 * The title's list, as the reference's title screen, with no drawing: go on with the save made last, choose a save to
 * load, or begin a new game (asked once more, the saves being kept). When it is done, $load is the slot to load, or
 * null for a new game. Shown only with a save to go on with.
 */
final class Title
{
    public const ENTRIES = ['continue' => '继续', 'load' => '读取记录', 'new' => '新游戏'];

    /** top, load (the saves, to choose one), anew (asked whether to begin a new game) */
    public string $phase = 'top';
    public Cursor $cursor;
    public bool $done = false;
    /** The slot to load when done; null for a new game. */
    public ?string $load = null;
    /** What cannot be done, shown until confirm or cancel; null with nothing to say. */
    public ?string $note = null;

    /** @param array<string, ?array{0: string, 1: int}> $slots World::slots(): each slot's line and when it was saved, null for an empty one */
    public function __construct(private readonly array $slots)
    {
        $this->cursor = new Cursor();
    }

    public function step(Controls $c): void
    {
        if ($this->note !== null) {
            if ($c->confirm || $c->cancel) {
                $this->note = null;
            }
        } elseif ($this->cursor->move($c, count($this->items()))) {
            return;
        } elseif ($c->cancel) {
            if ($this->phase !== 'top') {
                [$this->phase, $this->cursor] = ['top', new Cursor()];
            }
        } elseif ($c->confirm && $this->phase === 'top') {
            match (array_keys(self::ENTRIES)[$this->cursor->at]) {
                'continue' => [$this->load, $this->done] = [$this->latest(), true],
                'load' => [$this->phase, $this->cursor] = ['load', new Cursor()],
                'new' => [$this->phase, $this->cursor] = ['anew', new Cursor()],
            };
        } elseif ($c->confirm && $this->phase === 'load') {
            $slot = (string)array_keys($this->slots)[$this->cursor->at]; // '1' as a key is 1
            if ($this->slots[$slot] === null) {
                $this->note = '这里没有记录。';
            } else {
                [$this->load, $this->done] = [$slot, true];
            }
        } elseif ($c->confirm && $this->phase === 'anew') {
            if ($this->cursor->at === 0) {
                $this->done = true;
            } else {
                [$this->phase, $this->cursor] = ['top', new Cursor()];
            }
        }
    }

    /** The list showing. @return list<string> */
    public function items(): array
    {
        return match ($this->phase) {
            'top' => array_values(self::ENTRIES),
            'load' => array_map(fn(int|string $slot) => self::line((string)$slot, $this->slots[$slot]), array_keys($this->slots)),
            'anew' => ['开始新的旅程（记录都还留着）', '返回'],
        };
    }

    /** A save as a list shows it: the slot's name, and its line or that it is empty. @param ?array{0: string, 1: int} $save */
    public static function line(string $slot, ?array $save): string
    {
        return Save::NAMES[$slot] . '  ' . ($save[0] ?? '（空）');
    }

    /** The slot saved last. */
    private function latest(): string
    {
        $saved = array_filter($this->slots);
        uasort($saved, fn(array $a, array $b) => $b[1] <=> $a[1]);
        return (string)array_key_first($saved);
    }
}
