<?php
declare(strict_types=1);

namespace Shanhai;

use Shanhai\World\{Dir, Map, World};

/** Reads the game's data files (data/). */
final class Data
{
    public const DIR = __DIR__ . '/../data';

    /** @return array<string, Map> by id */
    public static function maps(): array
    {
        $maps = [];
        foreach (glob(self::DIR . '/maps/*.php') as $file) {
            $map = Map::fromArray(require $file);
            $maps[$map->id] = $map;
        }
        return $maps;
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

    /** @return array<string, array> beast id => its entry */
    public static function beasts(): array
    {
        return require self::DIR . '/beasts.php';
    }

    /** A new game's world, the player where data/game.php starts them. */
    public static function newWorld(): World
    {
        $start = (require self::DIR . '/game.php')['start'];
        return new World(self::maps(), $start['map'], $start['x'], $start['y'], Dir::from($start['dir']));
    }
}
