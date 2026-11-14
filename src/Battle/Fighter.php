<?php
declare(strict_types=1);

namespace Shanhai\Battle;

/** A beast in a battle: what it is, and what the battle has done to it. */
final class Fighter
{
    public int $maxHp, $hp;
    /** @var array{atk: int, def: int, spa: int, spd: int, spe: int} as its level makes them, before stages and statuses */
    public array $stats;
    /** @var list<array{id: string, pp: int}> */
    public array $moves = [];
    /** poison, burn, paralyze or sleep; one at a time */
    public ?string $status = null;
    /** Turns of sleep left, counting the one it wakes on. */
    public int $sleep = 0;
    /** Turns of confusion left, counting the one it ends on. */
    public int $confused = 0;
    public bool $flinched = false;
    /** @var array<string, int> -6 to +6: what leaves with the beast when it is switched out */
    public array $stages = ['atk' => 0, 'def' => 0, 'spa' => 0, 'spd' => 0, 'spe' => 0, 'acc' => 0, 'eva' => 0];

    /**
     * @param array $species its entry of data/beasts.php
     * @param list<array{0: int, 1: string}> $learnset its moves by level; it knows the last four it has learned,
     *        unless $keeps says which four of those it has learned it kept
     * @param array<string, array> $moves data/moves.php
     * @param array{atk: int, def: int, spe: int, spc: int} $dvs
     */
    public function __construct(
        public readonly string $id,
        public readonly array $species,
        public readonly int $level,
        array $learnset,
        array $moves,
        array $dvs = ['atk' => 8, 'def' => 8, 'spe' => 8, 'spc' => 8],
        int $statExp = 0,
        ?array $keeps = null,
    ) {
        $base = $species['stats'];
        $this->maxHp = $this->hp = Formulas::stat($base['hp'], Formulas::hpDv($dvs['atk'], $dvs['def'], $dvs['spe'], $dvs['spc']), $statExp, $level, true);
        $this->stats = [
            'atk' => Formulas::stat($base['atk'], $dvs['atk'], $statExp, $level, false),
            'def' => Formulas::stat($base['def'], $dvs['def'], $statExp, $level, false),
            'spa' => Formulas::stat($base['spa'], $dvs['spc'], $statExp, $level, false),
            'spd' => Formulas::stat($base['spd'], $dvs['spc'], $statExp, $level, false),
            'spe' => Formulas::stat($base['spe'], $dvs['spe'], $statExp, $level, false),
        ];
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

    /** What switching out leaves behind: stages, confusion, a flinch. The status stays. */
    public function leave(): void
    {
        $this->stages = array_fill_keys(array_keys($this->stages), 0);
        $this->confused = 0;
        $this->flinched = false;
    }
}
