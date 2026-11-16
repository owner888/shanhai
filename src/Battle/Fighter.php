<?php
declare(strict_types=1);

namespace Shanhai\Battle;

/** A beast: what it is, how far it has grown, and what its battles have done to it. */
final class Fighter
{
    /** The stats that grow with what a beast defeats (docs/numbers.md 7.3). */
    public const STAT_EXP = ['hp', 'atk', 'def', 'spe', 'spc'];
    public const MAX_STAT_EXP = 65535, MOVES = 4;

    /** Its entry of data/beasts.php as the rites it has received leave it (docs/numbers.md 27): its name and its base stats. */
    public array $species;
    /** The last rite it has received, a key of Formulas::RITES; 0 with none. */
    public int $rank = 0;
    public int $level;
    /** Its experience in all. */
    public int $exp;
    /** @var array{hp: int, atk: int, def: int, spe: int, spc: int} */
    public array $statExp;
    public int $maxHp, $hp;
    /** @var array{atk: int, def: int, spa: int, spd: int, spe: int} as its level makes them, before stages and statuses */
    public array $stats;
    /** @var list<array{id: string, pp: int}> */
    public array $moves = [];
    /** poison, burn, paralyze or sleep; one at a time */
    public ?string $status = null;
    /** How close it is to the player (docs/numbers.md 25), 0 to Formulas::BOND_MAX. */
    public int $bond = Formulas::BOND_BASE;
    /** Whether the move of the bond has been put to it already: it is put once. */
    public bool $offered = false;
    /** The thing it holds, an id of data/items.php; null with none. */
    public ?string $item = null;
    /** Turns of sleep left, counting the one it wakes on. */
    public int $sleep = 0;
    /** Turns of confusion left, counting the one it ends on. */
    public int $confused = 0;
    public bool $flinched = false;
    /** @var array<string, int> -6 to +6: what leaves with the beast when it is switched out */
    public array $stages = ['atk' => 0, 'def' => 0, 'spa' => 0, 'spd' => 0, 'spe' => 0, 'acc' => 0, 'eva' => 0];

    /**
     * @param array $born its entry of data/beasts.php
     * @param int $level the level it is made at, with the experience that level begins at
     * @param list<array{0: int, 1: string}> $learnset its moves by level; it knows the last four it has learned,
     *        unless $keeps says which four of those it has learned it kept
     * @param array<string, array> $moves data/moves.php
     * @param array{atk: int, def: int, spe: int, spc: int} $dvs
     */
    public function __construct(
        public readonly string $id,
        private readonly array $born,
        int $level,
        private readonly array $learnset,
        array $moves,
        private readonly array $dvs = ['atk' => 8, 'def' => 8, 'spe' => 8, 'spc' => 8],
        int $statExp = 0,
        ?array $keeps = null,
    ) {
        $this->species = $born;
        $this->level = $level;
        $this->exp = Formulas::expAtLevel($born['growth'], $level);
        $this->statExp = array_fill_keys(self::STAT_EXP, $statExp);
        $this->measure();
        $this->hp = $this->maxHp;
        $known = [];
        foreach ($learnset as [$at, $move]) {
            if ($at <= $level) {
                $known[] = $move;
            }
        }
        foreach ($keeps ?? array_slice($known, -4) as $move) {
            if (!in_array($move, $known, true)) {
                throw new \InvalidArgumentException("$id has not learned $move by level $level");
            }
            $this->moves[] = ['id' => $move, 'pp' => $moves[$move]['pp']];
        }
    }

    /** What a saved game keeps of it: all that is not its species' or its level's making. */
    public function state(): array
    {
        return ['id' => $this->id, 'level' => $this->level, 'exp' => $this->exp, 'statExp' => $this->statExp, 'dvs' => $this->dvs,
            'hp' => $this->hp, 'status' => $this->status, 'sleep' => $this->sleep, 'moves' => $this->moves, 'item' => $this->item, 'bond' => $this->bond, 'offered' => $this->offered, 'rank' => $this->rank];
    }

    /** 'female', 'male', or null for a kind with neither (docs/numbers.md 29): its kind's `gender`, half and half when it gives none. */
    public function gender(): ?string
    {
        $ratio = $this->born['gender'] ?? Formulas::GENDER_EVEN;
        return Formulas::gender($ratio === 'none' ? null : $ratio, $this->dvs['atk'], $this->dvs['spe']);
    }

    /** It receives the rite $rank (docs/numbers.md 27): its base stats rise, and its hp by as much as its most does. */
    public function rite(int $rank): void
    {
        if (Formulas::nextRite($this->born['kind'], $this->born['tier'], $this->rank) !== $rank) {
            throw new \LogicException("{$this->id} does not receive the rite $rank now");
        }
        $most = $this->maxHp;
        [$this->rank, $this->species] = [$rank, Formulas::risen($this->born, $rank)];
        $this->measure();
        $this->hp += $this->fainted() ? 0 : $this->maxHp - $most;
    }

    /**
     * The beast a saved game kept (state()).
     * @param array<string, array> $beasts data/beasts.php
     * @param array<string, list<array{0: int, 1: string}>> $learnsets data/learnsets.php
     * @param array<string, array> $moves data/moves.php
     */
    public static function fromState(array $s, array $beasts, array $learnsets, array $moves): self
    {
        $species = $beasts[$s['id']] ?? throw new \InvalidArgumentException("the saved game has a beast there is none of: '{$s['id']}'");
        $f = new self($s['id'], $species, $s['level'], $learnsets[$s['id']], $moves, $s['dvs']);
        [$f->exp, $f->statExp] = [$s['exp'], $s['statExp']];
        if ($s['rank'] !== 0) { // one that had no rite to receive, or not this one, is refused by risen()
            $first = Formulas::nextRite($species['kind'], $species['tier'], 0);
            if (!is_int($s['rank']) || $first === null || $s['rank'] < $first || $s['rank'] > Formulas::LAST_RITE) {
                throw new \InvalidArgumentException("the saved game's {$s['id']} has received a rite it cannot have");
            }
            [$f->rank, $f->species] = [$s['rank'], Formulas::risen($species, $s['rank'])];
        }
        $f->measure();
        if ($s['hp'] < 0 || $s['hp'] > $f->maxHp || $s['moves'] === [] || count($s['moves']) > self::MOVES) {
            throw new \InvalidArgumentException("the saved game's {$s['id']} is not as a beast can be");
        }
        foreach ($s['moves'] as $m) {
            if (!isset($moves[$m['id']]) || $m['pp'] < 0 || $m['pp'] > $moves[$m['id']]['pp']) {
                throw new \InvalidArgumentException("the saved game's {$s['id']} has a move that cannot be: '{$m['id']}'");
            }
        }
        [$f->hp, $f->status, $f->sleep, $f->moves, $f->item, $f->bond, $f->offered] = [$s['hp'], $s['status'], $s['sleep'], $s['moves'], $s['item'], $s['bond'], $s['offered']];
        return $f;
    }

    /** Its hp at most and its stats, from its level and what it has grown by (docs/numbers.md 2). Both special stats grow by the one stat experience. */
    private function measure(): void
    {
        [$base, $dvs, $exp] = [$this->species['stats'], $this->dvs, $this->statExp];
        $this->maxHp = Formulas::stat($base['hp'], Formulas::hpDv($dvs['atk'], $dvs['def'], $dvs['spe'], $dvs['spc']), $exp['hp'], $this->level, true);
        $this->stats = [
            'atk' => Formulas::stat($base['atk'], $dvs['atk'], $exp['atk'], $this->level, false),
            'def' => Formulas::stat($base['def'], $dvs['def'], $exp['def'], $this->level, false),
            'spa' => Formulas::stat($base['spa'], $dvs['spc'], $exp['spc'], $this->level, false),
            'spd' => Formulas::stat($base['spd'], $dvs['spc'], $exp['spc'], $this->level, false),
            'spe' => Formulas::stat($base['spe'], $dvs['spe'], $exp['spe'], $this->level, false),
        ];
    }

    /**
     * What defeating a beast gives (docs/numbers.md 7.3): $exp experience, and to each stat's experience the fallen's
     * base value of it ($share: its base stats, as divided among those that fought). The level is the one the
     * experience makes; when it rises the stats are measured again, and the hp rises by as much as its most did.
     * @param array{hp: int, atk: int, def: int, spa: int, spe: int} $share
     * @return array{0: int, 1: int} the level before and after
     */
    public function gain(int $exp, array $share): array
    {
        foreach (['hp' => 'hp', 'atk' => 'atk', 'def' => 'def', 'spe' => 'spe', 'spc' => 'spa'] as $stat => $base) {
            $this->statExp[$stat] = min(self::MAX_STAT_EXP, $this->statExp[$stat] + $share[$base]);
        }
        $growth = $this->species['growth'];
        $this->exp = min(Formulas::expAtLevel($growth, Formulas::MAX_LEVEL), $this->exp + $exp);
        $from = $this->level;
        $to = Formulas::levelAt($growth, $this->exp);
        if ($to > $from) {
            $was = $this->maxHp;
            $this->level = $to;
            $this->measure();
            $this->hp += $this->hp > 0 ? $this->maxHp - $was : 0; // one that has fallen stays fallen
        }
        return [$from, $to];
    }

    /** The moves of the levels after $from up to $to that it does not know, in the order it comes to them. @return list<string> */
    public function learnable(int $from, int $to): array
    {
        $out = [];
        foreach ($this->learnset as [$at, $move]) {
            if ($at > $from && $at <= $to && !in_array($move, array_column($this->moves, 'id'), true) && !in_array($move, $out, true)) {
                $out[] = $move;
            }
        }
        return $out;
    }

    /** Learns $move, with all its uses: in the place of the move at $slot, or after those it knows when it knows fewer than four. */
    public function learn(string $move, array $moves, ?int $slot = null): void
    {
        $new = ['id' => $move, 'pp' => $moves[$move]['pp']];
        if ($slot !== null) {
            if (!isset($this->moves[$slot])) {
                throw new \InvalidArgumentException("{$this->id} has no move $slot to forget");
            }
            $this->moves[$slot] = $new;
        } elseif (count($this->moves) >= self::MOVES) {
            throw new \InvalidArgumentException("{$this->id} knows four moves already");
        } else {
            $this->moves[] = $new;
        }
    }

    public function fainted(): bool
    {
        return $this->hp === 0;
    }

    /** A stat as the battle has it now: its stage, and what burn does to attack and paralysis to speed (docs/numbers.md 5, 11.2). */
    public function stat(string $key): int
    {
        $n = Formulas::staged($this->stats[$key], $this->stages[$key]);
        if ($key === 'atk' && $this->status === 'burn') {
            $n = max(1, intdiv($n, 2));
        }
        if ($key === 'spe' && $this->status === 'paralyze') {
            $n = max(1, intdiv($n, 4));
        }
        return $n;
    }

    /** Whole again, as rest makes it: its hp, no status, every move's uses. @param array<string, array> $moves data/moves.php */
    public function restore(array $moves): void
    {
        [$this->hp, $this->status, $this->sleep] = [$this->maxHp, null, 0];
        foreach ($this->moves as $i => $m) {
            $this->moves[$i]['pp'] = $moves[$m['id']]['pp'];
        }
        $this->leave();
    }

    /** What switching out leaves behind: stages, confusion, a flinch. The status stays. */
    public function leave(): void
    {
        $this->stages = array_fill_keys(array_keys($this->stages), 0);
        $this->confused = 0;
        $this->flinched = false;
    }
}
