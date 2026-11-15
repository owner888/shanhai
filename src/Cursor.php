<?php
declare(strict_types=1);

namespace Shanhai;

use Shanhai\World\{Controls, Dir};

/** Where a list's cursor is: it moves when up or down is pressed, not while held, and stays inside the list. */
final class Cursor
{
    public int $at = 0;
    private ?Dir $held = null;

    /** Takes an update's Controls for a list of $count entries; true when the cursor moved. */
    public function move(Controls $c, int $count): bool
    {
        $pressed = $c->dir !== $this->held ? $c->dir : null;
        $this->held = $c->dir;
        if ($pressed !== Dir::Up && $pressed !== Dir::Down) {
            return false;
        }
        $to = max(0, min($count - 1, $this->at + ($pressed === Dir::Down ? 1 : -1)));
        [$moved, $this->at] = [$to !== $this->at, $to];
        return $moved;
    }
}
