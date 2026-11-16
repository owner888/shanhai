<?php
declare(strict_types=1);

namespace Shanhai\Battle;

use InvalidArgumentException;
use LogicException;
use Shanhai\Elements;

/**
 * A battle between two parties, one beast of each out at a time, a turn at a time, with no drawing: turn() takes
 * what each side does and writes what happened to $log. Its numbers are Formulas'; its order of events is
 * docs/numbers.md 11. Random bytes are asked for in a fixed order, so a seed, or a test's script, replays a battle.
 */
final class Battle
{
    /** 11.2, 11.1: a random byte under these — is fully paralyzed; hits itself in confusion; goes first on a speed tie. */
    public const PARALYZED = 63, HITS_ITSELF = 128, TIE_FIRST = 128;
    /** The power of the blow a confused beast deals itself (docs/numbers.md 12: not read yet). */
    public const CONFUSION_POWER = 40;
    public const WEATHER_TURNS = 5;
    /** 11.3: what the weather multiplies a move of an element by, in tenths. */
    public const WEATHER = ['rain' => ['water' => 15, 'fire' => 5], 'sun' => ['fire' => 15, 'water' => 5], 'sandstorm' => []];
    /** 11.3: the elements a sandstorm does not hurt. */
    public const SANDSTORM_SPARES = ['earth', 'metal'];

    /** @var array{0: int, 1: int} which of each party is out */
    public array $active = [0, 0];
    public ?string $weather = null;
    public int $weatherLeft = 0;
    public int $turns = 0;
    /** 0 or 1, the side that won; -1 when the last of both fell in one turn; null while it goes on. */
    public ?int $winner = null;
    /** @var list<array> what happened, in order: [what, side, ...] */
    public array $log = [];

    private Elements $chart;
    /** 19: what a held thing that strengthens an element's moves adds to their damage, in hundredths. */
    public const BOOST = 10;

    /**
     * @param array{0: list<Fighter>, 1: list<Fighter>} $parties
     * @param array<string, array> $elements data/elements.php
     * @param array<string, array> $moves data/moves.php
     * @param array{0: bool, 1: bool} $chooses whether each side chooses its next beast when one falls (send()), or its first that can fight comes out
     * @param array<string, array> $things data/items.php, for what the beasts hold (docs/numbers.md 19); with none, nothing held does anything
     */
    public function __construct(public readonly array $parties, private readonly array $elements, private readonly array $moves, private readonly Rng $rng, private readonly array $chooses = [false, false], private readonly array $things = [])
    {
        $this->chart = new Elements($elements);
        foreach ([0, 1] as $side) { // each side begins with its first that can fight
            foreach ($parties[$side] as $i => $f) {
                if (!$f->fainted()) {
                    $this->active[$side] = $i;
                    break;
                }
            }
        }
    }

    /** The battle as it stands, to play on from apart: its beasts copied, its log empty, its rolls from $rng. */
    public function copy(Rng $rng): self
    {
        $copy = new self(array_map(fn(array $party) => array_map(fn(Fighter $f) => clone $f, $party), $this->parties), $this->elements, $this->moves, $rng, $this->chooses, $this->things);
        [$copy->active, $copy->weather, $copy->weatherLeft, $copy->turns, $copy->winner] = [$this->active, $this->weather, $this->weatherLeft, $this->turns, $this->winner];
        return $copy;
    }

    /** What a beast does when none of its moves has a use left (docs/numbers.md 11.6): the reference's, of no element here. */
    public const STRUGGLE = ['power' => 50, 'accuracy' => 100, 'element' => null];

    public function fighter(int $side): Fighter
    {
        return $this->parties[$side][$this->active[$side]];
    }

    /** The thing $f holds, when what it does held is $kind; else null. */
    private function holds(Fighter $f, string $kind): ?array
    {
        $thing = $f->item === null ? null : ($this->things[$f->item] ?? null);
        return ($thing['hold'] ?? null) === $kind ? $thing : null;
    }

    /** The side's beast's held thing does its work, and, of those that are used up, is gone. */
    private function used(int $side, bool $spent): void
    {
        $f = $this->fighter($side);
        $this->log[] = ['held', $side, $f->item];
        if ($spent) {
            $f->item = null;
        }
    }

    /**
     * 19: what is held to mend does so, the player's beast first: a thing that restores hp, under half of it; one
     * that cures a status, with that status; one that clears confusion, confused. Each is used up.
     */
    private function mendHeld(): void
    {
        foreach ([0, 1] as $side) {
            $f = $this->fighter($side);
            if ($f->fainted()) {
                continue;
            }
            if (($thing = $this->holds($f, 'heal')) !== null && 2 * $f->hp < $f->maxHp) {
                $this->used($side, true);
                $this->heal($f, $thing['by'], $side);
            }
            $thing = $this->holds($f, 'cure');
            if ($thing !== null && $f->status !== null && in_array($thing['by'], [$f->status, 'any'], true)) {
                $this->used($side, true);
                $this->log[] = ['cured', $side, $f->status];
                [$f->status, $f->sleep] = [null, 0];
            }
            $thing = $this->holds($f, 'cure');
            if ($thing !== null && $f->confused > 0 && in_array($thing['by'], ['confuse', 'any'], true)) {
                $this->used($side, true);
                $this->log[] = ['cured', $side, 'confuse'];
                $f->confused = 0;
            }
        }
    }

    /**
     * 19: at the turn's end, the player's beast first: what is held to restore a little hp each turn does, when the
     * beast is not whole; what restores a move's uses does so for the first of its moves that has none, and is used up.
     */
    private function endHeld(): void
    {
        foreach ([0, 1] as $side) {
            $f = $this->fighter($side);
            if ($f->fainted()) {
                continue;
            }
            if (($thing = $this->holds($f, 'leftovers')) !== null && $f->hp < $f->maxHp) {
                $this->used($side, false);
                $this->heal($f, max(1, intdiv($f->maxHp, $thing['by'])), $side);
            }
            if (($thing = $this->holds($f, 'pp')) !== null) {
                foreach ($f->moves as $i => $m) {
                    if ($m['pp'] === 0) {
                        $this->used($side, true);
                        $f->moves[$i]['pp'] = min($thing['by'], $this->moves[$m['id']]['pp']);
                        break;
                    }
                }
            }
        }
    }

    /** The moves the side's beast can still use, by their place among its four. @return list<int> */
    public function usable(int $side): array
    {
        $out = [];
        foreach ($this->fighter($side)->moves as $i => $m) {
            if ($m['pp'] > 0) {
                $out[] = $i;
            }
        }
        return $out;
    }

    /**
     * One turn. Each side's action is ['move', which of its four], ['switch', which of its party] or ['pass', 0]:
     * nothing, as when a try to flee fails.
     * A beast with no move left to use struggles, whatever it was told.
     * @param array{0: string, 1: int} $a side 0's
     * @param array{0: string, 1: int} $b side 1's
     */
    public function turn(array $a, array $b): void
    {
        if ($this->winner !== null) {
            throw new LogicException('the battle is over');
        }
        if ($this->awaits(0) || $this->awaits(1)) {
            throw new LogicException('a side has yet to send its next beast');
        }
        $this->turns++;
        $actions = [$a, $b];
        // 11.1: switching comes before any move.
        foreach ($actions as $side => [$what, $which]) {
            if ($what === 'switch') {
                $this->switchTo($side, $which);
            } elseif ($what !== 'move' && $what !== 'pass') {
                throw new InvalidArgumentException("no action '$what'");
            }
        }
        $movers = array_keys(array_filter($actions, fn(array $x) => $x[0] === 'move'));
        if (count($movers) === 2) {
            // 19: one holding what lets it go first does, on a byte under the thing's number: the other side's is rolled before the player's
            $first = null;
            foreach ([1, 0] as $side) {
                if ($first === null && ($thing = $this->holds($this->fighter($side), 'first')) !== null && $this->rng->byte() < $thing['by']) {
                    $first = $side;
                    $this->used($side, false);
                }
            }
            [$s0, $s1] = [$this->fighter(0)->stat('spe'), $this->fighter(1)->stat('spe')];
            $first ??= $s0 === $s1 ? ($this->rng->byte() < self::TIE_FIRST ? 0 : 1) : ($s0 > $s1 ? 0 : 1);
            $movers = [$first, 1 - $first];
        }
        foreach ($movers as $i => $side) {
            if ($this->fighter($side)->fainted() || $this->fighter(1 - $side)->fainted()) {
                continue;
            }
            $this->act($side, $actions[$side][1], $i === 0 && count($movers) === 2);
            $this->mendHeld();
            $this->residual($side);
        }
        foreach ([0, 1] as $side) {
            if ($actions[$side][0] === 'pass') {
                $this->residual($side);
            }
            $this->fighter($side)->flinched = false;
        }
        $this->endHeld();
        $this->mendHeld();
        $this->endWeather();
        $this->replaceFallen();
    }

    /** Whether the side chooses its next beast, its beast has fallen and it has yet to send() it. */
    public function awaits(int $side): bool
    {
        return $this->chooses[$side] && $this->winner === null && $this->fighter($side)->fainted();
    }

    /** The side that awaits sends its next beast, between turns. */
    public function send(int $side, int $which): void
    {
        if (!$this->awaits($side)) {
            throw new LogicException("side $side's beast has not fallen");
        }
        $this->switchTo($side, $which);
    }

    private function switchTo(int $side, int $which): void
    {
        $next = $this->parties[$side][$which] ?? throw new InvalidArgumentException("side $side has no beast $which");
        if ($which === $this->active[$side] || $next->fainted()) {
            throw new InvalidArgumentException("side $side cannot switch to beast $which");
        }
        $this->fighter($side)->leave();
        $this->active[$side] = $which;
        $this->log[] = ['switch', $side, $which];
    }

    /** The side's beast takes its turn: what may stop it, then its move. $foeYetToAct: the other has not moved this turn, so it can flinch. */
    private function act(int $side, int $which, bool $foeYetToAct): void
    {
        $u = $this->fighter($side);
        $t = $this->fighter(1 - $side);
        // 11.2, in this order: sleep, flinch, confusion, paralysis.
        if ($u->status === 'sleep') {
            if (--$u->sleep > 0) {
                $this->log[] = ['cant', $side, 'sleep'];
                return;
            }
            $u->status = null;
            $this->log[] = ['woke', $side];
        }
        if ($u->flinched) {
            $this->log[] = ['cant', $side, 'flinch'];
            return;
        }
        if ($u->confused > 0) {
            if (--$u->confused === 0) {
                $this->log[] = ['clear', $side];
            } elseif ($this->rng->byte() < self::HITS_ITSELF) {
                $n = $this->hurt($u, Formulas::damage($u->level, self::CONFUSION_POWER, $u->stat('atk'), $u->stat('def'), false));
                $this->log[] = ['hurt', $side, $n, 'confusion'];
                return;
            }
        }
        if ($u->status === 'paralyze' && $this->rng->byte() < self::PARALYZED) {
            $this->log[] = ['cant', $side, 'paralyze'];
            return;
        }
        // docs/numbers.md 11.6: with no move left to use, the beast struggles, whatever it was told.
        $struggles = $this->usable($side) === [];
        if ($struggles) {
            $m = self::STRUGGLE;
            $this->log[] = ['struggle', $side];
        } else {
            $slot = $u->moves[$which] ?? throw new InvalidArgumentException("side $side's beast has no move $which");
            if ($slot['pp'] === 0) {
                throw new InvalidArgumentException("side $side's move $which has no uses left");
            }
            $u->moves[$which]['pp']--;
            $m = $this->moves[$slot['id']];
            $this->log[] = ['use', $side, $slot['id']];
        }
        [$effect, $chance] = $m['effect'] ?? [null, 0];

        // 3 and 4: the damage, worked out before the move is known to hit.
        $damage = 0;
        $critical = false;
        $tenths = [];
        if ($m['power'] > 0) {
            // 4, 19: a move that strikes true is two levels up; a held thing that sharpens the eye, one more
            $critical = $this->rng->byte() < Formulas::CRITICAL[($effect === 'crit' ? 2 : 0) + ($this->holds($u, 'crit') !== null ? 1 : 0)];
            [$damage, $tenths] = $this->damage($u, $t, $m, $critical);
            if ($damage >= 2) {
                $damage = Formulas::vary($damage, Formulas::varyRoll($this->rng));
            }
        }

        // 6: whether it hits. What a beast does to itself, or to the weather, does not miss.
        $onFoe = $m['power'] > 0 || !in_array($effect, ['rain', 'sun', 'sandstorm', 'heal'], true) && !str_starts_with((string)$effect, 'up:');
        if ($onFoe) {
            $accuracy = Formulas::accuracy($m['accuracy'], $u->stages['acc'], $t->stages['eva']);
            if (($thing = $this->holds($t, 'evade')) !== null) { // 19: taken from what the move needs, before a move that cannot miss is known for one
                $accuracy = max(0, $accuracy - $thing['by']);
            }
            if ($accuracy < 255 && $this->rng->byte() >= $accuracy) {
                $this->log[] = ['miss', $side];
                return;
            }
        }
        if ($m['power'] > 0) {
            // 19: one holding what lets it bear a blow is left with 1 hp by one that would fell it, on a byte under the thing's number, rolled at every blow
            if (($thing = $this->holds($t, 'endure')) !== null && $this->rng->byte() < $thing['by'] && $damage >= $t->hp) {
                $damage = $t->hp - 1;
                $this->used(1 - $side, false);
            }
            $dealt = $this->hurt($t, $damage);
            $this->log[] = ['hit', 1 - $side, $dealt, $critical, $tenths];
            if ($effect === 'drain') {
                $this->heal($u, max(1, intdiv($dealt, 2)), $side);
            }
            if ($t->fainted()) {
                $this->log[] = ['faint', 1 - $side];
            }
            if ($struggles) { // a quarter of what it dealt comes back on it, one at least
                $this->log[] = ['hurt', $side, $this->hurt($u, max(1, intdiv($dealt, 4))), 'recoil'];
                if ($u->fainted()) {
                    $this->log[] = ['faint', $side];
                }
            }
        }
        // 19: a move of power with no effect of its own, from one holding what cows, makes the other flinch on a byte under the thing's number
        if ($effect === null && $m['power'] > 0 && !$struggles && ($thing = $this->holds($u, 'flinch')) !== null && $this->rng->byte() < $thing['by']) {
            $t->flinched = $foeYetToAct && !$t->fainted();
        }
        if ($effect === null || $effect === 'crit' || $effect === 'drain') {
            return;
        }
        if ($chance < 100 && $this->rng->byte() >= Formulas::chance($chance)) {
            return;
        }
        $this->effect($side, $effect, $foeYetToAct, $m['power'] === 0);
    }

    /**
     * 3.1 to 3.3: what a move of power does to $t before the random spread, and the elements' factors it met, in tenths.
     * @return array{0: int, 1: list<int>}
     */
    private function damage(Fighter $u, Fighter $t, array $m, bool $critical): array
    {
        $physical = $m['element'] === null || $this->elements[$m['element']]['category'] === 'physical';
        [$atk, $def] = $physical ? ['atk', 'def'] : ['spa', 'spd'];
        // 3.1: a critical hit takes both stats as they are without stages, when the defender's stage is not below the attacker's.
        $raw = $critical && $t->stages[$def] >= $u->stages[$atk];
        // 19: a held thing that strengthens the move's element, or the moves of none, adds a tenth
        $boost = ($this->holds($u, 'boost')['by'] ?? null) === ($m['element'] ?? 'none') ? self::BOOST : 0;
        $power = ($m['bond'] ?? false) ? Formulas::bondPower($u->bond) : $m['power']; // 25
        $damage = Formulas::damage($u->level, $power, $raw ? $u->stats[$atk] : $u->stat($atk), $raw ? $t->stats[$def] : $t->stat($def), $critical, $boost);
        $tenths = [];
        if ($m['element'] !== null) {
            $weather = self::WEATHER[$this->weather ?? ''][$m['element']] ?? null;
            if ($weather !== null) {
                $damage = Formulas::times($damage, $weather);
            }
            if (in_array($m['element'], $u->species['elements'], true)) {
                $damage = Formulas::sameElement($damage);
            }
            $tenths = $this->chart->factors($m['element'], $t->species['elements']);
            foreach ($tenths as $f) {
                $damage = Formulas::times($damage, $f);
            }
        }
        return [$damage, $tenths];
    }

    /**
     * What a move of $u's would do to $t now, with no critical hit and before the random spread, times its chance to
     * hit: what a side that only weighs damage goes by. 0 for a move that does none. $u and $t need not be out.
     */
    public function estimate(Fighter $u, Fighter $t, int $which): float
    {
        $m = $this->moves[$u->moves[$which]['id']];
        if ($m['power'] === 0 || $u->moves[$which]['pp'] === 0) {
            return 0.0;
        }
        return $this->damage($u, $t, $m, false)[0] * Formulas::accuracy($m['accuracy'], $u->stages['acc'], $t->stages['eva']) / 255;
    }

    /** What a move does besides damage. $told: the effect is the whole move, so its failing is said. */
    private function effect(int $side, string $effect, bool $foeYetToAct, bool $told): void
    {
        $u = $this->fighter($side);
        $t = $this->fighter(1 - $side);
        $done = false;
        switch (true) {
            case in_array($effect, ['burn', 'poison', 'paralyze', 'sleep'], true):
                if (!$t->fainted() && $t->status === null) {
                    $t->status = $effect;
                    if ($effect === 'sleep') {
                        do { // 11.2: 2 to 7
                            $n = $this->rng->byte() & 7;
                        } while ($n === 0 || $n === 7);
                        $t->sleep = $n + 1;
                    }
                    $this->log[] = ['status', 1 - $side, $effect];
                    $done = true;
                }
                break;
            case $effect === 'confuse':
                if (!$t->fainted() && $t->confused === 0) {
                    $t->confused = ($this->rng->byte() & 3) + 2; // 11.2: 2 to 5
                    $this->log[] = ['status', 1 - $side, 'confuse'];
                    $done = true;
                }
                break;
            case $effect === 'flinch':
                $t->flinched = $foeYetToAct && !$t->fainted();
                $done = true;
                break;
            case isset(self::WEATHER[$effect]):
                if ($this->weather !== $effect) {
                    [$this->weather, $this->weatherLeft] = [$effect, self::WEATHER_TURNS];
                    $this->log[] = ['weather', $side, $effect];
                    $done = true;
                }
                break;
            case $effect === 'heal':
                if ($u->hp < $u->maxHp) {
                    $this->heal($u, intdiv($u->maxHp, 2), $side);
                    $done = true;
                }
                break;
            default:
                [$way, $stat, $by] = explode(':', $effect);
                $who = $way === 'up' ? $u : $t;
                $to = max(-6, min(6, $who->stages[$stat] + ($way === 'up' ? (int)$by : -(int)$by)));
                if (!$who->fainted() && $to !== $who->stages[$stat]) {
                    $who->stages[$stat] = $to;
                    $this->log[] = ['stage', $way === 'up' ? $side : 1 - $side, $stat, $to];
                    $done = true;
                }
        }
        if (!$done && $told) {
            $this->log[] = ['fail', $side];
        }
    }

    /** 11.2: what poison and burn take at the end of a beast's turn. */
    private function residual(int $side): void
    {
        $u = $this->fighter($side);
        if (!$u->fainted() && ($u->status === 'poison' || $u->status === 'burn')) {
            $this->log[] = ['hurt', $side, $this->hurt($u, max(1, intdiv($u->maxHp, 8))), $u->status];
            if ($u->fainted()) {
                $this->log[] = ['faint', $side];
            }
        }
    }

    /** 11.3: the weather's turn passes; a sandstorm hurts those it does not spare. */
    private function endWeather(): void
    {
        if ($this->weather === null) {
            return;
        }
        if (--$this->weatherLeft === 0) {
            $this->log[] = ['weather', null, null];
            $this->weather = null;
            return;
        }
        if ($this->weather !== 'sandstorm') {
            return;
        }
        foreach ([0, 1] as $side) {
            $f = $this->fighter($side);
            if (!$f->fainted() && array_intersect($f->species['elements'], self::SANDSTORM_SPARES) === []) {
                $this->log[] = ['hurt', $side, $this->hurt($f, max(1, intdiv($f->maxHp, 8))), 'sandstorm'];
                if ($f->fainted()) {
                    $this->log[] = ['faint', $side];
                }
            }
        }
    }

    /** A side whose beast fell sends its next that can fight, unless it chooses (send()); one with none left has lost. */
    private function replaceFallen(): void
    {
        $out = [];
        foreach ([0, 1] as $side) {
            if (!$this->fighter($side)->fainted()) {
                continue;
            }
            $next = null;
            foreach ($this->parties[$side] as $i => $f) {
                if (!$f->fainted()) {
                    $next = $i;
                    break;
                }
            }
            if ($next === null) {
                $out[] = $side;
            } elseif (!$this->chooses[$side]) {
                $this->fighter($side)->leave();
                $this->active[$side] = $next;
                $this->log[] = ['switch', $side, $next];
            }
        }
        if ($out !== []) {
            $this->winner = count($out) === 2 ? -1 : 1 - $out[0];
        }
    }

    private function hurt(Fighter $f, int $n): int
    {
        $dealt = min($f->hp, $n);
        $f->hp -= $dealt;
        return $dealt;
    }

    private function heal(Fighter $f, int $n, int $side): void
    {
        $gain = min($f->maxHp - $f->hp, $n);
        $f->hp += $gain;
        $this->log[] = ['heal', $side, $gain];
    }
}
