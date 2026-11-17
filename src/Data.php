<?php
declare(strict_types=1);

namespace Shanhai;

use Shanhai\Battle\Rng;
use Shanhai\World\{Dir, Map, Title, World};

/** Reads the game's data files (data/). */
final class Data
{
    public const DIR = __DIR__ . '/../data';

    /** @return array<string, Map> by id */
    public static function maps(): array
    {
        $maps = [];
        $foes = self::opponents();
        foreach (glob(self::DIR . '/maps/*.php') as $file) {
            $a = require $file;
            // Those met on the map's road (data/opponents.php) stand on it as people do, and watch.
            foreach ($foes['at'][$a['id']] ?? [] as $i => [$x, $y, $dir, $sight, $who, $team]) {
                [$id, $flag] = ["foe$i", "beat.{$a['id']}.$i"];
                [$hail, $lost, $after] = $foes['says'][$i % count($foes['says'])];
                $a['npcs'][] = [$id, $x, $y, $dir, 'foe', [
                    ['ifFlag', $flag, 'after'],
                    ['ifFlag', $foes['needs'], 'ready'],
                    ['say', $foes['unready']],
                    ['end'],
                    ['label', 'ready'],
                    ['say', $hail],
                    ['duel', $who, $team],
                    ['setFlag', $flag],
                    ['say', $lost],
                    ['end'],
                    ['label', 'after'],
                    ['say', $after],
                ]];
                if ($sight > 0) {
                    $a['watch'][] = [$id, $sight, $flag];
                }
            }
            $map = Map::fromArray($a);
            $maps[$map->id] = $map;
        }
        return $maps;
    }

    /** @return array{needs: string, unready: string, says: list<array>, at: array<string, list<array>>} data/opponents.php */
    public static function opponents(): array
    {
        return require self::DIR . '/opponents.php';
    }

    /** @return array<string, array> element id => its entry */
    public static function elements(): array
    {
        return require self::DIR . '/elements.php';
    }

    /** @return array<int, array> tier => its bands */
    public static function tiers(): array
    {
        return require self::DIR . '/tiers.php';
    }

    /** @return array<string, array> move id => its entry */
    public static function moves(): array
    {
        return require self::DIR . '/moves.php';
    }

    /** @return array<string, list<array{0: int, 1: string}>> beast id => its moves by level */
    public static function learnsets(): array
    {
        return require self::DIR . '/learnsets.php';
    }

    /** @return array<int, array> the range's number => its entry */
    public static function ranges(): array
    {
        return require self::DIR . '/ranges.php';
    }

    /** @return array<string, array> god id => its entry */
    public static function gods(): array
    {
        return require self::DIR . '/gods.php';
    }

    /** @return array<string, array> item id => its entry */
    public static function items(): array
    {
        return require self::DIR . '/items.php';
    }

    /** @return array<string, array> beast id => its entry */
    public static function beasts(): array
    {
        return require self::DIR . '/beasts.php';
    }

    /**
     * A new game's world, the player where data/game.php starts them; its battles' dice from $seed. With $saveDir it
     * can be saved there (Save), and when a game is saved there already it begins at the title's list (World\Title).
     */
    public static function newWorld(int $seed, ?string $saveDir = null): World
    {
        if ($saveDir !== null) {
            Save::adopt($saveDir);
        }
        $game = require self::DIR . '/game.php';
        $start = $game['start'];
        $book = ['beasts' => self::beasts(), 'learnsets' => self::learnsets(), 'moves' => self::moves(), 'elements' => self::elements(), 'items' => self::items(), 'gods' => self::gods(), 'float' => $game['float'], 'rest' => $game['rest']];
        $world = new World(self::maps(), $start['map'], $start['x'], $start['y'], Dir::from($start['dir']), $book, Rng::seeded($seed), $saveDir);
        if ($saveDir !== null && array_filter($world->slots()) !== []) {
            $world->title = new Title($world->slots());
        }
        return $world;
    }
}
