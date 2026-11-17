<?php
declare(strict_types=1);

namespace Shanhai;

use RuntimeException;
use Shanhai\Battle\Formulas;
use Shanhai\World\Pen;

/**
 * The saved games, files of JSON in one folder: World::state() written, with when, and read for World::restore().
 * The player saves to three (SLOTS); the game saves itself to one more (AUTO) as the player comes onto another map and
 * before a god's trial. The reference keeps one; the developer asked for these, so a rare beast or a god can be met
 * with a game saved just before.
 */
final class Save
{
    /** The file's format: one written by another is refused, not guessed at; an older one is read by what is known to have changed. */
    public const VERSION = 9;
    /** The saves the player chooses among, and the one the game keeps itself; each slot's name as shown. */
    public const SLOTS = ['1', '2', '3'];
    public const AUTO = 'auto';
    public const NAMES = ['auto' => '自动记录', '1' => '记录一', '2' => '记录二', '3' => '记录三'];

    /** The file of a slot in the folder of saves. */
    public static function file(string $dir, string $slot): string
    {
        return "$dir/save-$slot.json";
    }

    /** The one save the game kept before it kept several (save.json) becomes the first slot's, unless that has one already. */
    public static function adopt(string $dir): void
    {
        if (is_file("$dir/save.json") && !is_file(self::file($dir, self::SLOTS[0])) && !rename("$dir/save.json", self::file($dir, self::SLOTS[0]))) {
            throw new RuntimeException("the saved game $dir/save.json could not be moved to the first slot");
        }
    }

    /** Writes $state to $file, whole or not at all: to a file beside it first, which then takes its place. $at: when, a Unix time. */
    public static function write(string $file, array $state, ?int $at = null): void
    {
        $json = json_encode(['version' => self::VERSION, 'saved' => $at ?? time(), 'state' => $state], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        $part = $file . '.part';
        if (file_put_contents($part, $json) !== strlen($json) || !rename($part, $file)) {
            throw new RuntimeException("the game could not be saved to $file");
        }
    }

    /** When $file was saved, a Unix time: as it says, or, written before it said (format 8 and before), as the file system does. Null with no file. */
    public static function saved(string $file): ?int
    {
        if (!is_file($file)) {
            return null;
        }
        clearstatcache(true, $file);
        return self::data($file)['saved'] ?? (filemtime($file) ?: null);
    }

    /** The state saved in $file; null when there is no such file. A file that is not a save of this format throws. */
    public static function read(string $file): ?array
    {
        if (!is_file($file)) {
            return null;
        }
        $data = self::data($file);
        // Format 9 says when it was saved, beside the state, which is as format 8's.
        // Format 1 had no place of rest: a game saved in it had rested nowhere the world would wake it but the game's own.
        // Formats 1 and 2 had no pen: a game saved in them had nothing in it.
        // Formats 1 to 3 had no money: a game saved in them has what a new game begins with.
        $state = $data['state'] + ($data['version'] === 1 ? ['rest' => null] : []) + ($data['version'] < 3 ? ['boxes' => Pen::emptied(), 'box' => 0] : [])
            + ($data['version'] < 4 ? ['money' => Formulas::START_MONEY] : []);
        if ($data['version'] < 5) { // formats 1 to 4 had nothing held: their beasts hold nothing
            $empty = fn(array $beasts) => array_map(fn(array $f) => $f + ['item' => null], $beasts);
            [$state['party'], $state['boxes']] = [$empty($state['party']), array_map($empty, $state['boxes'])];
        }
        if ($data['version'] < 6) { // formats 1 to 5 had no record of beasts: theirs has the kinds they have with them
            $has = array_values(array_unique(array_column([...$state['party'], ...array_merge(...$state['boxes'])], 'id')));
            sort($has);
            $state += ['seen' => $has, 'caught' => $has];
        }
        if ($data['version'] < 7) { // formats 1 to 6 had no bond: their beasts have what one begins with
            $new = fn(array $beasts) => array_map(fn(array $f) => $f + ['bond' => Formulas::BOND_BASE, 'offered' => false], $beasts);
            [$state['party'], $state['boxes']] = [$new($state['party']), array_map($new, $state['boxes'])];
            $state += ['steps' => 0];
        }
        if ($data['version'] < 8) { // formats 1 to 7 had no rites: their beasts have received none
            $new = fn(array $beasts) => array_map(fn(array $f) => $f + ['rank' => 0], $beasts);
            [$state['party'], $state['boxes']] = [$new($state['party']), array_map($new, $state['boxes'])];
        }
        return $state;
    }

    /** What $file holds, its format checked. */
    private static function data(string $file): array
    {
        $json = file_get_contents($file);
        if ($json === false) {
            throw new RuntimeException("the saved game $file could not be read");
        }
        $data = json_decode($json, true, 64, JSON_THROW_ON_ERROR);
        if (!is_array($data) || !in_array($data['version'] ?? null, range(1, self::VERSION), true) || !is_array($data['state'] ?? null)) {
            throw new RuntimeException("$file is not a saved game of format " . self::VERSION);
        }
        return $data;
    }
}
