<?php
declare(strict_types=1);

namespace Shanhai\Battle;

/**
 * What a thing of the bag does used on a beast (docs/numbers.md 21), in a battle or out of one: a pure rule, with no
 * drawing and no bag. A thing that would do nothing is not used.
 */
final class Medicine
{
    /** The uses that are used on a beast of the party. */
    public const USES = ['heal', 'cure', 'revive', 'restore'];

    /** Whether $thing is one used on a beast of the party. */
    public static function is(array $thing): bool
    {
        return in_array($thing['use'] ?? null, self::USES, true);
    }

    /**
     * Uses $thing (an entry of data/items.php) on $f. False, with nothing changed, when it would do nothing:
     *   heal     restores `by` hp, or all of it ('all'): not to one that has fallen or is whole
     *   cure     ends the status `by` names, or any ('any'); 'confuse' and 'any' end confusion too: not without one
     *   revive   one that has fallen stands again with half its hp: not one that stands
     *   restore  all its hp and no status or confusion: not to one that has fallen, or is whole and well
     */
    public static function apply(array $thing, Fighter $f): bool
    {
        $by = $thing['by'] ?? null;
        switch ($thing['use'] ?? null) {
            case 'heal':
                if ($f->fainted() || $f->hp === $f->maxHp) {
                    return false;
                }
                $f->hp = $by === 'all' ? $f->maxHp : min($f->maxHp, $f->hp + $by);
                return true;
            case 'cure':
                $status = $f->status !== null && in_array($by, [$f->status, 'any'], true);
                $confused = $f->confused > 0 && in_array($by, ['confuse', 'any'], true);
                if ($f->fainted() || (!$status && !$confused)) {
                    return false;
                }
                if ($status) {
                    [$f->status, $f->sleep] = [null, 0];
                }
                if ($confused) {
                    $f->confused = 0;
                }
                return true;
            case 'revive':
                if (!$f->fainted()) {
                    return false;
                }
                $f->hp = max(1, intdiv($f->maxHp, 2));
                return true;
            case 'restore':
                if ($f->fainted() || ($f->hp === $f->maxHp && $f->status === null && $f->confused === 0)) {
                    return false;
                }
                [$f->hp, $f->status, $f->sleep, $f->confused] = [$f->maxHp, null, 0, 0];
                return true;
        }
        return false;
    }
}
