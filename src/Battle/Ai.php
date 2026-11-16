<?php
declare(strict_types=1);

namespace Shanhai\Battle;

/**
 * What the one who sets a trial does with a turn (docs/numbers.md 26): the reference's, layer for layer and byte for
 * byte, for the effects our moves have. Each move it can use starts at 20 and the layers add to that or take from it;
 * it uses one of those left lowest. Before that it may send in another beast, or use a thing on the one out. A pure
 * rule: it changes nothing of the battle, and keeps only what it has seen of it.
 */
final class Ai
{
    /** The layers a god's trial goes by: the reference's gym leaders'. */
    public const GOD = ['basic', 'setup', 'smart', 'aggressive', 'cautious', 'risky'];
    /** Those one met on the road goes by: the layers more than half of the reference's ordinary classes have. */
    public const ROAD = ['basic', 'types'];
    /** The order the layers are gone through in, whichever of them one has. */
    public const LAYERS = ['basic', 'setup', 'types', 'offensive', 'smart', 'opportunist', 'aggressive', 'cautious', 'risky'];

    public const START = 20, DISMISS = 10;
    /** A byte under these and the thing is so, or not so: the reference's numbers, each named for what it decides. */
    public const HALF = 128, FIFTH = 50, SETUP_ANYWAY = 30, DRAIN_ANYWAY = 100, EVADE_WHOLE = 178, EVADE_HURT = 10, HEAL_NOT = 25, CONFUSE_ANYWAY = 25, CAUTIOUS = 230;
    /** Switching: where the weighing starts, under which it looks for another, and the byte under which it then sends it. */
    public const SWITCH_START = 10, SWITCH_ROLL = 50;
    /** The things it can use, in the order it looks at them. */
    public const THINGS = ['dixiu', 'youmu', 'licao', 'yupei'];

    /** @var array{0: ?int, 1: ?int} which beast of each side was out when it last chose */
    private array $out = [null, null];
    /** @var array{0: int, 1: int} the turns each side's beast has used a move in since it came out */
    private array $turns = [0, 0];
    /** @var list<string> the moves the player's beast has used since it came out, and the last of them */
    private array $used = [];
    private ?string $last = null;
    private bool $playerCan = true;

    /**
     * @param list<string> $layers
     * @param array<string, array> $moves data/moves.php
     * @param array<string, array> $things data/items.php
     * @param list<string> $has the things it has to use, each once
     */
    public function __construct(private readonly array $layers, private readonly array $moves, private readonly array $things, private array $has = []) {}

    /** What side 1 does this turn: ['switch', which], ['item', id] (which it no longer has after) or ['move', which]. */
    public function act(Battle $b, Rng $rng): array
    {
        foreach ([0, 1] as $side) {
            if ($this->out[$side] !== $b->active[$side]) {
                [$this->out[$side], $this->turns[$side]] = [$b->active[$side], 0];
                if ($side === 0) {
                    [$this->used, $this->last] = [[], null];
                }
            }
        }
        $this->playerCan = $b->usable(0) !== [];
        if (($to = $this->another($b)) !== null && $rng->byte() < self::SWITCH_ROLL) {
            return ['switch', $to];
        }
        if (($id = $this->thing($b, $rng)) !== null) {
            unset($this->has[array_search($id, $this->has, true)]);
            return ['item', $id];
        }
        $usable = $b->usable(1);
        if ($usable === []) {
            return ['move', 0]; // it struggles (docs/numbers.md 11.6)
        }
        $scores = $this->scores($b, $rng);
        $least = min(array_intersect_key($scores, array_flip($usable)));
        do {
            $which = $rng->byte() & 3;
        } while (!in_array($which, $usable, true) || $scores[$which] !== $least);
        return ['move', $which];
    }

    /** After the turn: what each side did in it. */
    public function seen(Battle $b, array $player, array $foe): void
    {
        if ($player[0] === 'move') {
            $this->turns[0]++;
            if ($this->playerCan) {
                $this->last = $b->parties[0][$this->out[0]]->moves[$player[1]]['id'];
                $this->used = array_values(array_unique([...$this->used, $this->last]));
            }
        }
        if ($foe[0] === 'move') {
            $this->turns[1]++;
        }
    }

    /** Each of its beast's moves' score, the lower the likelier. @return list<int> */
    public function scores(Battle $b, Rng $rng): array
    {
        $scores = array_fill(0, count($b->fighter(1)->moves), self::START);
        foreach (self::LAYERS as $layer) {
            if (in_array($layer, $this->layers, true)) {
                $this->$layer($b, $rng, $scores);
            }
        }
        return $scores;
    }

    /** @return list<array{0: int, 1: array, 2: ?string, 3: bool}> each move of side 1's beast: its place, its entry, its effect, whether the effect is all it does */
    private function each(Battle $b): array
    {
        $out = [];
        foreach ($b->fighter(1)->moves as $i => $known) {
            $m = $this->moves[$known['id']];
            $out[] = [$i, $m, $m['effect'][0] ?? null, $m['power'] === 0];
        }
        return $out;
    }

    /** Nothing that would be for nothing: healing one that is whole, confusing one confused, the weather there is, a status on one that has one. */
    private function basic(Battle $b, Rng $rng, array &$scores): void
    {
        [$me, $you] = [$b->fighter(1), $b->fighter(0)];
        foreach ($this->each($b) as [$i, $m, $effect, $sole]) {
            $pointless = match (true) {
                !$sole => false,
                $effect === 'heal' => $me->hp === $me->maxHp,
                $effect === 'confuse' => $you->confused > 0,
                in_array($effect, ['rain', 'sun', 'sandstorm'], true) => $b->weather === $effect,
                in_array($effect, ['sleep', 'poison', 'paralyze'], true) => $you->status !== null,
                default => false,
            };
            $scores[$i] += $pointless ? self::DISMISS : 0;
        }
    }

    /** A move that only raises its own stats, on its beast's first turn out, or only lowers the other's, on the other's first: as often as not, much likelier. After that, nearly always much less likely. */
    private function setup(Battle $b, Rng $rng, array &$scores): void
    {
        foreach ($this->each($b) as [$i, $m, $effect, $sole]) {
            $side = !$sole || $effect === null ? null : (str_starts_with($effect, 'up:') ? 1 : (str_starts_with($effect, 'down:') ? 0 : null));
            if ($side === null) {
                continue;
            }
            if ($this->turns[$side] === 0) {
                $scores[$i] -= $rng->byte() < self::HALF ? 0 : 2;
            } else {
                $scores[$i] += $rng->byte() < self::SETUP_ANYWAY ? 0 : 2;
            }
        }
    }

    /** A move that does damage and whose element overcomes the other's is likelier; one whose element is weak against it less likely, having a move of another element that does damage. */
    private function types(Battle $b, Rng $rng, array &$scores): void
    {
        $you = $b->fighter(0);
        $all = $this->each($b);
        foreach ($all as [$i, $m]) {
            $x = $b->matchup($m['element'], $you);
            if ($x > 1 && $m['power'] > 0) {
                $scores[$i]--;
            } elseif ($x < 1 && array_filter($all, fn(array $o) => $o[1]['element'] !== $m['element'] && $o[1]['power'] > 0) !== []) {
                $scores[$i]++;
            }
        }
    }

    /** A move that does no damage is much less likely. */
    private function offensive(Battle $b, Rng $rng, array &$scores): void
    {
        foreach ($this->each($b) as [$i, $m]) {
            $scores[$i] += $m['power'] === 0 ? 2 : 0;
        }
    }

    /** What each effect is worth as things stand. */
    private function smart(Battle $b, Rng $rng, array &$scores): void
    {
        [$me, $you] = [$b->fighter(1), $b->fighter(0)];
        $whole = fn(Fighter $f) => $f->hp === $f->maxHp;
        $overHalf = fn(Fighter $f) => 2 * $f->hp > $f->maxHp;
        $overQuarter = fn(Fighter $f) => 4 * $f->hp > $f->maxHp;
        $under = fn(int $n) => $rng->byte() < $n;
        $has = fn(string $element) => array_filter($this->each($b), fn(array $o) => $o[1]['element'] === $element && $o[1]['power'] > 0) !== [];
        foreach ($this->each($b) as [$i, $m, $effect, $sole]) {
            $s = &$scores[$i];
            if ($effect === 'drain') {
                $x = $b->matchup($m['element'], $you);
                if ($x < 1) {
                    $s += $under(self::DRAIN_ANYWAY) ? 0 : 1;
                } elseif ($x > 1 && !$whole($me)) {
                    $s -= $under(self::FIFTH) ? 0 : 1;
                }
            } elseif ($sole && $effect === 'up:eva:1') {
                $s += $this->evade($me, $whole, $overHalf, $overQuarter, $under);
            } elseif ($sole && $effect === 'heal') {
                if (!$overQuarter($me)) {
                    $s -= $under(self::HEAL_NOT) ? 0 : 2;
                } elseif ($overHalf($me)) {
                    $s++;
                }
            } elseif ($sole && $effect === 'confuse') {
                if (!$overHalf($you)) {
                    $s += $under(self::CONFUSE_ANYWAY) ? 0 : 1;
                    $s += $overQuarter($you) ? 0 : 1;
                }
            } elseif ($sole && $effect === 'up:spd:2') {
                if (!$overHalf($me) || $me->stages['spd'] >= 4) {
                    $s++;
                } elseif ($me->stages['spd'] < 2 && array_filter($you->species['elements'], fn(string $e) => $b->special($e)) !== []) {
                    $s -= $under(self::FIFTH) ? 0 : 2;
                }
            } elseif ($sole && $effect === 'paralyze') {
                if (!$overQuarter($you)) {
                    $s += $under(self::HALF) ? 0 : 1;
                } elseif ($me->stat('spe') <= $you->stat('spe') && $overQuarter($me)) {
                    $s -= $under(self::FIFTH) ? 0 : 2;
                }
            } elseif ($sole && $effect === 'sandstorm') {
                if (array_intersect($you->species['elements'], Battle::SANDSTORM_SPARES) !== []) {
                    $s += 2;
                } elseif (!$overHalf($you)) {
                    $s++;
                } else {
                    $s -= $under(self::HALF) ? 0 : 1;
                }
            } elseif ($sole && ($effect === 'rain' || $effect === 'sun')) {
                [$helped, $hurt] = $effect === 'rain' ? ['water', 'fire'] : ['fire', 'water'];
                $which = null; // the first of the other's elements the weather bears on
                foreach ($you->species['elements'] as $e) {
                    $which ??= $e === $helped || $e === $hurt ? $e : null;
                }
                if ($which === $hurt) {
                    $s -= $overHalf($you) && ($this->turns[0] === 0 || $this->turns[1] === 0) ? 2 : 0;
                } elseif ($which === $helped || !$has($helped) || !$overHalf($you)) {
                    $s += 3;
                } else {
                    $s -= $under(self::HALF) ? 0 : 1;
                }
            }
            unset($s);
        }
    }

    /** What raising its evasion is worth: most with all its hp, least with a quarter or less. */
    private function evade(Fighter $me, \Closure $whole, \Closure $overHalf, \Closure $overQuarter, \Closure $under): int
    {
        if ($me->stages['eva'] >= Formulas::MAX_STAGE) {
            return self::DISMISS;
        }
        if ($whole($me)) {
            return $under(self::EVADE_WHOLE) ? -2 : 1;
        }
        if (!$overQuarter($me)) {
            return 2 + 1;
        }
        if ($under(self::EVADE_HURT)) {
            return -2;
        }
        if ($overHalf($me)) {
            return $under(self::FIFTH) ? -2 : 1;
        }
        return ($under(self::HALF) ? 0 : 2) + 1;
    }

    /** With half its hp or less, a move that only changes a stat other than accuracy and evasion is less likely: under a quarter always, over it as often as not. */
    private function opportunist(Battle $b, Rng $rng, array &$scores): void
    {
        $me = $b->fighter(1);
        if (2 * $me->hp > $me->maxHp || (4 * $me->hp > $me->maxHp && $rng->byte() < self::HALF)) {
            return;
        }
        foreach ($this->each($b) as [$i, $m, $effect, $sole]) {
            $scores[$i] += $sole && $effect !== null && preg_match('/^(up|down):(atk|def|spa|spd|spe):/', $effect) ? 1 : 0;
        }
    }

    /** Every move that does damage but the one that would do the most is less likely. */
    private function aggressive(Battle $b, Rng $rng, array &$scores): void
    {
        [$me, $you] = [$b->fighter(1), $b->fighter(0)];
        [$most, $best] = [0, null];
        foreach ($this->each($b) as [$i, $m]) {
            if ($m['power'] > 0 && ($d = $b->would($me, $you, $i)) >= $most) {
                [$most, $best] = [$d, $i];
            }
        }
        foreach ($this->each($b) as [$i, $m]) {
            $scores[$i] += $best !== null && $i !== $best && $m['power'] > 0 ? 1 : 0;
        }
    }

    /**
     * After its beast's first turn out, a move that only poisons or paralyzes is less likely, nine times in ten. As in
     * the reference, the one time in ten ends the layer: the moves after that one are not looked at.
     */
    private function cautious(Battle $b, Rng $rng, array &$scores): void
    {
        if ($this->turns[1] === 0) {
            return;
        }
        foreach ($this->each($b) as [$i, $m, $effect, $sole]) {
            if ($sole && ($effect === 'poison' || $effect === 'paralyze')) {
                if ($rng->byte() >= self::CAUTIOUS) {
                    return;
                }
                $scores[$i]++;
            }
        }
    }

    /** A move that would fell the other's beast is far likelier. */
    private function risky(Battle $b, Rng $rng, array &$scores): void
    {
        [$me, $you] = [$b->fighter(1), $b->fighter(0)];
        foreach ($this->each($b) as [$i, $m]) {
            $scores[$i] -= $m['power'] > 0 && $b->would($me, $you, $i) > $you->hp ? 5 : 0;
        }
    }

    /**
     * The beast it would send in, or null: it looks for one only when the one out stands badly — the weighing starts
     * at SWITCH_START and must end under it — and sends only one with over a quarter of its hp, that what the
     * player's beast last struck with (or, it having struck with nothing, its elements) does not overcome, and that
     * has a move that does damage and overcomes the player's beast: the first such of the party.
     */
    public function another(Battle $b): ?int
    {
        [$me, $you] = [$b->fighter(1), $b->fighter(0)];
        $score = self::SWITCH_START;
        if ($this->used !== []) {
            $seen = 0; // 2: one of them is even against it; 1: none is but one is weak; 0: none does damage
            foreach ($this->used as $id) {
                $m = $this->moves[$id];
                if ($m['power'] === 0) {
                    continue;
                }
                $x = $b->matchup($m['element'], $me);
                if ($x > 1) {
                    $seen = null;
                    break;
                }
                $seen = $x === 1.0 ? 2 : max($seen, 1);
            }
            $score += $seen === null ? -1 : 2 - $seen;
        } else {
            foreach (array_unique($you->species['elements']) as $e) {
                $score -= $b->matchup($e, $me) > 1 ? 1 : 0;
            }
        }
        $mine = 0;
        foreach ($me->moves as $known) {
            $m = $this->moves[$known['id']];
            if ($m['power'] > 0) {
                $x = $b->matchup($m['element'], $you);
                $mine = $x > 1 ? 100 : $mine + ($x < 1 ? 1 : 6);
            }
        }
        $score += $mine === 0 ? -2 : ($mine < 5 ? -1 : ($mine >= 100 ? 1 : 0));
        if ($score >= self::SWITCH_START) {
            return null;
        }
        $struck = $this->last !== null && $this->moves[$this->last]['power'] > 0 ? [$this->moves[$this->last]['element']] : $you->species['elements'];
        foreach ($b->parties[1] as $i => $f) {
            if ($i === $b->active[1] || $f->fainted() || 4 * $f->hp <= $f->maxHp || array_filter($struck, fn(?string $e) => $b->matchup($e, $f) > 1) !== []) {
                continue;
            }
            foreach ($f->moves as $known) {
                $m = $this->moves[$known['id']];
                if ($m['power'] > 0 && $b->matchup($m['element'], $you) > 1) {
                    return $i;
                }
            }
        }
        return null;
    }

    /**
     * The thing it uses on its beast now, or null. Only on the highest of its party. One that restores hp: with a
     * quarter of its hp or less, always; with half or less, a time in five. The one that ends any status: on one
     * asleep. The one that does both: for either.
     */
    private function thing(Battle $b, Rng $rng): ?string
    {
        $me = $b->fighter(1);
        if ($this->has === [] || $me->level < max(array_map(fn(Fighter $f) => $f->level, $b->parties[1]))) {
            return null;
        }
        foreach (self::THINGS as $id) {
            if (!in_array($id, $this->has, true)) {
                continue;
            }
            $use = $this->things[$id]['use'];
            $hurt = $use !== 'cure' && 2 * $me->hp <= $me->maxHp && (4 * $me->hp <= $me->maxHp || $rng->byte() < self::FIFTH);
            if ($hurt || ($use !== 'heal' && $me->status === 'sleep')) {
                return $id;
            }
        }
        return null;
    }
}
