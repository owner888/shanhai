<?php
declare(strict_types=1);

namespace Shanhai;

use RuntimeException;
use Shanhai\Battle\Formulas;
use Shanhai\World\Pen;

/** The one saved game, a file of JSON: World::state() written, and read for World::restore(). */
final class Save
{
    /** The file's format: one written by another is refused, not guessed at; an older one is read by what is known to have changed. */
    public const VERSION = 5;

    /** Writes $state to $file, whole or not at all: to a file beside it first, which then takes its place. */
    public static function write(string $file, array $state): void
    {
        $json = json_encode(['version' => self::VERSION, 'state' => $state], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        $part = $file . '.part';
        if (file_put_contents($part, $json) !== strlen($json) || !rename($part, $file)) {
            throw new RuntimeException("the game could not be saved to $file");
        }
    }

    /** The state saved in $file; null when there is no such file. A file that is not a save of this format throws. */
    public static function read(string $file): ?array
    {
        if (!is_file($file)) {
            return null;
        }
        $json = file_get_contents($file);
        if ($json === false) {
            throw new RuntimeException("the saved game $file could not be read");
        }
        $data = json_decode($json, true, 64, JSON_THROW_ON_ERROR);
        if (!is_array($data) || !in_array($data['version'] ?? null, [1, 2, 3, 4, self::VERSION], true) || !is_array($data['state'] ?? null)) {
            throw new RuntimeException("$file is not a saved game of format " . self::VERSION);
        }
        // Format 1 had no place of rest: a game saved in it had rested nowhere the world would wake it but the game's own.
        // Formats 1 and 2 had no pen: a game saved in them had nothing in it.
        // Formats 1 to 3 had no money: a game saved in them has what a new game begins with.
        $state = $data['state'] + ($data['version'] === 1 ? ['rest' => null] : []) + ($data['version'] < 3 ? ['boxes' => Pen::emptied(), 'box' => 0] : [])
            + ($data['version'] < 4 ? ['money' => Formulas::START_MONEY] : []);
        if ($data['version'] < 5) { // formats 1 to 4 had nothing held: their beasts hold nothing
            $empty = fn(array $beasts) => array_map(fn(array $f) => $f + ['item' => null], $beasts);
            [$state['party'], $state['boxes']] = [$empty($state['party']), array_map($empty, $state['boxes'])];
        }
        return $state;
    }
}
